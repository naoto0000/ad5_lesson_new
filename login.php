
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AD5 lesson</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
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