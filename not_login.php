
<?php 
$now_url = $_SERVER['REQUEST_URI'];

// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php?referrer=' . urlencode($now_url));
}
?>