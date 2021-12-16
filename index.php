<?php
include 'config.php';
include 'db.php';

$json = file_get_contents('php://input');

$data_json = json_decode($json, true);
$data = [];

// request validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strpos($_SERVER["CONTENT_TYPE"], "application/x-www-form-urlencoded") !== false) {
        $data = $_POST;
    } elseif (strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        $data = $data_json;
    } else {
        die("Request failed. Expected HTTP POST 'CONTENT_TYPE' to be 'application/x-www-form-urlencoded' OR 'application/json'.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $_GET;
} else {
    die("Request failed. Expected 'REQUEST_METHOD' to be 'POST' or 'GET'.");
}

// authentication with secret
if (!isset($data["secret"])) {
    die('Secret incorrect or not provided!');
}

if ($data["secret"] !== $config['ws_secret']) {
    die('Secret incorrect or not provided!');
}

// validation
if (!isset($data["device"])) {
    die('Missing "device" POST variable');
}
$device_type = 'generic';
if (isset($data["device_type"])) {
    $device_type = $data["device_type"];
}
if (!isset($data["value"])) {
    die('Missing "value" POST variable');
}
$time = '';
if (isset($data["time"])) {
    $time = $data["time"];
}

/*
  Determine telegram chat_id, with the following priority:
  - 1) If "chat_id" is provided in request, use its value
  - 2) If "chat" (chat name) is provided in the config file, use its value,
        e.g. $config['telegram']['chat_ids']['<<<SOME CHAT NAME>>>'] = "-1231424125";
  - 3) cross reference device against config's chat_ids keys.
  - 4) use default chat_id if still not found.
*/

$telegram_chat_id = "";
if (isset($data["chat_id"]) && $data["chat_id"] !== "") {
    // 1)
    $telegram_chat_id = $data["chat_id"];
} elseif (isset($data["chat"]) && $data["chat"] !== "") {
    // 2)
    if (isset($config['telegram']['chat_ids'][$data["chat"]])) {
        $telegram_chat_id = $config['telegram']['chat_ids'][$data["chat"]];
    }
} else {
    // 3)
    foreach ($config['telegram']['chat_ids'] as $chat_name => $chat_id) {
        if (strpos($data["device"], $chat_name)!== false) {
            $telegram_chat_id = $chat_id;
        }
    }
}

if (!$telegram_chat_id) {
    // 4)
    $telegram_chat_id = $config['telegram']['chat_ids']['default'];
}


/*
RATE LIMITING:
1. get rate limit from POST, or default from config.php
2. get last duplicate entry from database
3. proceed only if rate limit passes
*/
$threshold_s = $config['default_rate_limit_s'];
if (isset($data["rate"]) && $data["rate"] !== '') {
    $threshold_s = $data["rate"];
}
$stmt = $mysqli->prepare("SELECT timestamp_created, TIMESTAMPDIFF(SECOND,timestamp_created,NOW()) AS seconds_ago FROM notification_log WHERE device_name = ? AND state = ? ORDER BY timestamp_created DESC LIMIT 1");
$stmt->bind_param("ss", $data["device"], $data["value"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result->num_rows === 1) {
    while ($row = $result->fetch_assoc()) {
        if ($row['seconds_ago'] < $threshold_s) {
            die('Request ignored by rate limiter: '.$row['seconds_ago'].'s ago < '.$threshold_s.'s threshold');
        }
    }
}

/* INSERT ROW */
$inserted_id = 0; // placeholder variable for new row ID
$stmt = $mysqli->prepare("INSERT INTO notification_log SET device_name=?, device_type=?, state=?");//timestamp_reported
$stmt->bind_param("sss", $data["device"], $device_type, $data["value"]);// $time
$stmt->execute();
if (isset($stmt->insert_id)) {
    $inserted_id = $stmt->insert_id;
    $stmt->close();
    echo('- notification logged to database');
}

/* GET INSERTED TIME FROM NOTIFICATION RECORD */
$inserted_timestamp = '';
$stmt = $mysqli->prepare("SELECT timestamp_created FROM notification_log WHERE id = ?");
$stmt->bind_param("s", $inserted_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result->num_rows === 1) {
    while ($row = $result->fetch_assoc()) {
        $inserted_timestamp = $row['timestamp_created'];
    }
}

function build_notification_text($type)
{
    global $inserted_timestamp;
    global $data;
    $param_device = '';
    $param_event = '';
    $param_timestamp = '';

    $param_device .= urlencode($data["device"]);
    $param_timestamp .= urlencode($inserted_timestamp);

    switch ($type) {
    case 'door':
      $value = urlencode($data["value"]);
      if ($value == 'on') {
          $value = 'opened';
      } elseif ($value == 'off') {
          $value = 'closed';
      }
      $param_event .= $value;
      break;
    case 'temp':
      $value = urlencode($data["value"]);
      $param_event .= $value.'C';
      break;
    case 'test':
    default:
      $param_event .= urlencode($data["value"]);
      break;
  }
    return $param_device ." is now:\n". $param_event ."\n\nTime reported:\n". $param_timestamp;
}

function tg_bot_msg_post_body($chat_id, $text)
{
    return "chat_id=".$chat_id."&text=".$text;
}

/* Invoke Telegram Notification Bot */
$url = 'https://api.telegram.org/bot'.$config['telegram']['notification_bot_token'].'/sendMessage';
$post_body = tg_bot_msg_post_body($telegram_chat_id, build_notification_text($device_type));

echo '<br><br>- Telegram bot: send message...';

echo '<br><br>- BOT API response:';
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo curl_error($ch);
}
curl_close($ch);
echo $response;
echo '<br><br>';
echo '- end';
