<?php
ini_set('log_errors', 'on');  //ログを取るか
ini_set('error_log', 'php_error.log');  //ログの出力ファイルを指定

// ini_set('display_errors','on');

require(__DIR__ . '/utilities/htmlspecialchars.php');
require(__DIR__ . '/utilities/error_message.php');
require(__DIR__ . '/utilities/initialize_validation_msg.php');

session_start();

session_regenerate_id(true);

$employees = array();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ad5_new', "root", "root");
} catch (PDOException $e) {
    echo $e->getMessage();
}
