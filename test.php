
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>test</title>
</head>

<body>
  <style>

  body {
    font-size: 20px;
  font-family: sans-serif;
  }

    label {
      display:inline-block;
      width:140px;
    }
    input,label,select {
      height:30px;
      font-size:18px;
    }

    input,select {
      text-align: center;
      width:270px;
    }
    input[type=submit] {
      text-align: center;
      width:300px;
    }

    .flex {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
    }
  </style>
  <div class="flex">

    <form class="" action="test.php" method="get">
      <h2>GET:</h2><br>
      <label for="secret">Secret: </label><input type="text" name="secret" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="secret">Rate Limit (s): </label><input type="text" name="rate" value="10" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="ifttt_hook">IFTTT webhook: </label><input type="text" name="ifttt_hook" value="tg_pop_notification" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="device">Device name: </label><input type="text" name="device" value="TEST_AIRCON">
      <br>
      <label for="device_type">Device Type: </label>
      <select class="" name="device_type">
        <option value="temp"selected>Thermometer</option>
        <option value="door">Door Sensor</option>
      </select>
      <br>
      <label for="value">Value: </label><input type="text" name="value" value="2" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="chat">(opt) TG chat: </label><input type="text" name="chat" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="chat">(opt) TG chat_id: </label><input type="text" name="chat_id" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br><br>
      <input type="submit" name="" value="Submit GET">
    </form>

    <form class="" action="test.php" method="post">
      <h2>POST:</h2><br>
      <label for="secret">Secret: </label><input type="text" name="secret" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="secret">Rate Limit (s): </label><input type="text" name="rate" value="10" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="ifttt_hook">IFTTT webhook: </label><input type="text" name="ifttt_hook" value="tg_pop_notification" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="device">Device name: </label><input type="text" name="device" value="TEST_AIRCON">
      <br>
      <label for="device_type">Device Type: </label>
      <select class="" name="device_type">
        <option value="temp"selected>Thermometer</option>
        <option value="door">Door Sensor</option>
      </select>
      <br>
      <label for="value">Value: </label><input type="text" name="value" value="2" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="chat">(opt) TG chat: </label><input type="text" name="chat" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="chat">(opt) TG chat_id: </label><input type="text" name="chat_id" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br><br>
      <input type="submit" name="" value="Submit POST">
    </form>
    <form class="" action="test.php" method="post" onsubmit="return mySubmitFunction(event)">
      <h2>JSON POST:</h2><br>
      <label for="secret">Secret: </label><input type="text" id="form_secret" name="secret" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="secret">Rate Limit (s): </label><input type="text" id="form_rate" name="rate" value="10" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="ifttt_hook">IFTTT webhook: </label><input type="text" id="form_ifttt_hook" name="ifttt_hook" value="tg_pop_notification" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="device">Device name: </label><input type="text" id="form_device" name="device" value="TEST_AIRCON">
      <br>
      <label for="device_type">Device Type: </label>
      <select class="" id="form_device_type" name="device_type">
        <option value="temp"selected>Thermometer</option>
        <option value="door">Door Sensor</option>
      </select>
      <br>
      <label for="value">Value: </label><input type="text" id="form_value" name="value" value="2" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="chat">(opt) TG chat: </label><input type="text" id="form_chat" name="chat" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br>
      <label for="chat">(opt) TG chat_id: </label><input type="text" id="form_chat_id" name="chat_id" value="" onClick="this.setSelectionRange(0, this.value.length)">
      <br><br>
      <input type="submit" name="" value="Submit JSON POST">
    </form>
  </div>
  <script type="text/javascript">
    function mySubmitFunction(e){
      e.preventDefault();

      json_post();

      return false;
    }

    async function json_post(){
      var form_secret = document.getElementById("form_secret").value;
      var form_rate = document.getElementById("form_rate").value;
      var form_ifttt_hook = document.getElementById("form_ifttt_hook").value;
      var form_device = document.getElementById("form_device").value;
      var form_device_type = document.getElementById("form_device_type").value;
      var form_value = document.getElementById("form_value").value;
      var form_chat = document.getElementById("form_chat").value;
      var form_chat_id = document.getElementById("form_chat_id").value;

      const rawResponse = await fetch('index.php', {
        method: 'POST',
        headers: {
          // 'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          secret: form_secret,
          rate: form_rate,
          ifttt_hook: form_ifttt_hook,
          device: form_device,
          device_type: form_device_type,
          value: form_value,
          chat: form_chat,
          chat_id: form_chat_id
        })
      });
      const content = await rawResponse.text();
      document.getElementById("div_output").innerHTML = content;
    }

  </script>

  <br><br><br>
  <div id="div_output">
    <?php
      if (isset($_POST['secret']) || isset($_GET['secret'])) {
          include 'index.php';
      }
    ?>
  </div>

</body>

</html>
