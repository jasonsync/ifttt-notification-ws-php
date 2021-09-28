<?php

// Create connection
if ($config['debug_mysqli']) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
$mysqli = new mysqli($config['mysql']['server'], $config['mysql']['username'], $config['mysql']['password'], $config['mysql']['database']);

$mysqli->set_charset("utf8mb4");
