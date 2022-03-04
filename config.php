<?php

// DEV SERVER

$config = [
  'ws_secret'=>'SECRET', // Expected by the HTTP request variable: $_POST['s'] to prevent rogue injection (set this to something not easily guessed).
  'default_rate_limit_s'=> 30, // default rate limit in seconds (if not specified by request, use this rate limit)
  'telegram_notification_bot_token'=>'TOKEN', // secret token to control bot
  'telegram_chat_id'=>'-1001536872487', // telegram group chat id the notification bot is in
  'telegram'=>[
    'notification_bot_token'=>'TOKEN', // secret token to control bot
    'chat_ids'=>[
      'default'=>'-1001536872487',
      'EDP'=> '-1001610560473',
      'KN'=> null,
      'KRW'=> null,
      'KS'=> null,
      'LRC'=> '-1001780464200',
      'OKD'=> null,
      'PHE'=> null,
      'PHW'=> null
    ]
  ],
  'mysql' => [
    'server'=>'localhost',
    'database'=>'asounhuo_notifications',
    'username'=>'root',
    'password'=>''
  ],
  'debug_mysqli'=> true
];
