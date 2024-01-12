<?php require_once('header.html'); ?>
<?php require_once('menu.php');?>

<header id="header">
    <h1>プロフィール編集</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
</header>

<main>
    <form method="post" enctype="multipart/form-data">
        <table class="prof_table">
            <tr>
                <td class="prof_title prof_list">氏名</td>
                <td class="prof_list"><?php echo $prof_name; ?></td>
                <td class="prof_title prof_list">生年月日</td>
                <td class="prof_list"><?php echo $prof_birthdate; ?>( <?php echo $prof_age; ?> 歳)</td>
            </tr>
        </table>

        <?php if ($prof_img !== null) : ?>
            <div class="prof_img">
                <p class="prof_contents_title">プロフィール画像</p>
                <span><img src="img/<?php echo $prof_img; ?>" alt="" class="img_area"></span>
                <div class="prof_img_input">
                    <input type="file" name="image" class="prof_contents">
                    <div>
                        <input type="checkbox" name="prof_delete" class="prof_contents">
                            <label for="" class="prof_contents">削除する</label>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <div class="prof_img">
                <p class="prof_contents_title">プロフィール画像</p>
                <div class="prof_img_input">
                    <input type="file" name="image" class="prof_contents">
                </div>
            </div>
        
        <?php endif ; ?>

        <div class="prof_text">
            <p class="prof_contents_title">紹介文</p>
            <textarea name="prof_text" class="prof_textarea" cols="50" rows="10"><?php echo $prof_text; ?></textarea>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="prof_submit" value="登録" class="prof_submit">

    </form>

</main>
</body>
</html>