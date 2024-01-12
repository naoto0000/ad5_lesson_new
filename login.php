<?php 
require_once('function.php');

if (isset($_POST['login_mail'])) {
    $login_mail = $_POST['login_mail'];
}
if (isset($_POST['login_pass'])) {
    $login_pass = $_POST['login_pass'];
}

if (isset($_POST['login_submit'])) {
    $login_sql = "SELECT * FROM employees WHERE email = :email AND delete_flg IS NULL";
    $login_stmt = $pdo->prepare($login_sql);
    $login_stmt->bindValue(':email',$login_mail,PDO::PARAM_STR);
    $login_stmt->execute();
    $login_result = $login_stmt->fetch();
    
    // 指定したハッシュがパスワードにマッチしているかチェック
    if (password_verify($login_pass, $login_result['password'])) {
        //DBのユーザー情報をセッションに保存
        $_SESSION['id'] = $login_result['id'];
        $_SESSION['name'] = $login_result['name'];
        $_SESSION['birthdate'] = $login_result['birthdate'];
        $_SESSION['img'] = $login_result['image'];
        $_SESSION['prof_text'] = $login_result['prof_text'];

        // 現在日付
        $now = date('Ymd');

        // 誕生日
        $birthday = $_SESSION['birthdate'] ;
        $birthday_login = str_replace("-", "", $birthday);

        $_SESSION['birthdate_prof'] = str_replace("-", ".", $birthday);

        // 年齢
        $_SESSION['age'] = floor(($now - $birthday_login) / 10000);

        // 保存されたURLがあればそこにリダイレクト
        if (isset($_SESSION['redirect_url'])) {
            $redirect_url = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']); // 一度使用したら削除しておく
            header("Location: $redirect_url");
            exit;
        } else {
            // 保存されたURLがない場合、デフォルトのページにリダイレクト
            header("Location: index.php");
            exit;
        }

    } else {
        echo "メールアドレスもしくはパスワードが間違っています";
    }
}

include(__DIR__ . '/pages/login.view.php');