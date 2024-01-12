<?php 
// ログインしてない時の処理
if (empty($_SESSION['id'])) {

    session_start();

    // 現在のURLを保存
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    header('Location: login.php');
}
?>