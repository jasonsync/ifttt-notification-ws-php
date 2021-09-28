<?php

$config = [
  'ifttt_key'=>'IFTTT_KEY', // get by clicking "Documentation" at: https://ifttt.com/maker_webhooks/
  'ws_secret'=>'WS_SECRET', // Expected by the HTTP request variable: $_POST['s'] to prevent rogue injection (set this to something not easily guessed).
  'default_rate_limit_s'=> 900, // default rate limit in seconds (if not specified by request, use this rate limit)
  'mysql' => [
    'server'=>'SERVER_URL',
    'database'=>'DATABASE_NAME',
    'username'=>'MYSQL_USERNAME',
    'password'=>'MYSQL_PASSWORD'
  ]
];
