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

</main>
</body>

</html>