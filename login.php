
<?php 

require_once('function.php');

// $_SESSION['access'] == "";

$login_mail = $_POST['login_mail'];
$login_pass = $_POST['login_pass'];

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

        // if ( $_SESSION['access'] == "on") {
        //     $backURL = $_SERVER['HTTP_REFERER'];
        //     header("Location: .'$backURL'");
        // } else {
        //     header('Location: index.php');
        // }

        header('Location: index.php');

    } else {
        echo "メールアドレスもしくはパスワードが間違っています";
    }
}
?>

<?php require_once('header.html'); ?>

<header>
    <h1>ログイン</h1>
</header>

<main>

<form action="" method="post">
    <div class="login">
        <label class="login_label">メールアドレス</label>
        <input type="text" class="login_mail" name="login_mail">
    </div>

    <div class="login">
        <label class="login_label">パスワード</label>
        <div class="login_pass_class">
            <input type="password" class="login_pass" name="login_pass">
            <i id="eye" class="fa-solid fa-eye"></i>
        </div>
    </div>

    <input type="submit" name="login_submit" value="ログイン" class="login_submit">

</form>

    <p class="new_regi_text">新規登録の方は<a href="new_regi.php" class="new_regi_a">こちら</a></p>

    <script>
          let eye = document.getElementById("eye");
          eye.addEventListener('click', function () {
               if (this.previousElementSibling.getAttribute('type') == 'password') {
                    this.previousElementSibling.setAttribute('type', 'text');
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
               } else {
                    this.previousElementSibling.setAttribute('type', 'password');
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
               }
          })
     </script>

</main>
</body>
</html>