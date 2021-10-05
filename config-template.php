<?php

// TEMPLATE

$config = [
  'ws_secret'=>'12345678', // Expected by the HTTP request variable: $_POST['s'] to prevent rogue injection (set this to something not easily guessed).
  'default_rate_limit_s'=> 30, // default rate limit in seconds (if not specified by request, use this rate limit)
  'telegram_notification_bot_token'=>'0123456789:AAgfsgS7353R-7t35Sh_zX-235GSogASgjAG', // secret token to control bot. Get from Telegram's BotFather
  'telegram_chat_id'=>'-1234567891234', // telegram group chat id the notification bot is in
  'mysql' => [
    'server'=>'localhost',
    'database'=>'MY_DATABASE_NAME',
    'username'=>'root',
    'password'=>''
  ],
  'debug_mysqli'=> true
];
