
<?php 
require_once('function.php'); 

$login_name = $_SESSION['name'];

if (isset($_POST['logout_submit'])) {
        $_SESSION = array();//セッションの中身をすべて削除
        session_destroy();//セッションを破壊
        
        header('Location: login.php');
}
?>

<div class="menu">
        <div class="menu_class">
                <div class="menu_item"><a href="index.php" class="menu_text">社員一覧</a></div>
                <div class="menu_item"><a href="registration.php" class="menu_text">社員登録</a></div>
                <div class="menu_item"><a href="emp_total.php" class="menu_text">社員集計</a></div>
                <div class="menu_item"><a href="branch.php" class="menu_text">支店一覧</a></div>
                <div class="menu_item"><a href="branch_regi.php" class="menu_text">支店登録</a></div>
                <div class="menu_item"><a href="quali.php" class="menu_text">資格マスタ</a></div>
                <!-- <div class="menu_item"><a href="group.php" class="menu_text">部門一覧</a></div>
                <div class="menu_item"><a href="group_regi.php" class="menu_text">部門登録</a></div> -->
                <div class="menu_item"><a href="profile.php" class="menu_text">プロフィール</a></div>
        </div>

        <div class="menu_class_name">
                <form action="" method="post">
                        <input type="submit" name="logout_submit" value="ログアウト" class="logout_submit">
                </form>
                <div class="menu_name"><?php echo $login_name; ?>さん　ログイン中</div>
        </div>
</div>
