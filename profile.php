
<?php 

require_once('function.php');

require_once('not_login.php');

$prof_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL";
$prof = $pdo->query($prof_sql);

// TODO whereで現在のログインユーザのidで絞り込む
foreach ($prof as $prof_row) {
    if ($_SESSION['id'] == $prof_row['id']) {
        $prof_birthdate_get = $prof_row['birthdate'];
        $prof_name = $prof_row['name'];       
        $prof_img =  $prof_row['image'];
        $prof_text =  $prof_row['prof_text'];
    }
}

// 現在日付
$now = date('Ymd');

// 誕生日
$birthday_age = str_replace("-", "", $prof_birthdate_get);

$prof_birthdate = str_replace("-", ".", $prof_birthdate_get);

// 年齢
$prof_age = floor(($now - $birthday_age) / 10000);

if ($_POST['prof_submit']) {
    if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

        $update_sql = "UPDATE employees SET image = :image, prof_text = :prof_text WHERE id = :id";
        $update_stmt = $pdo->prepare($update_sql);

        // 画像削除の場合
        if (isset($_POST['prof_delete'])) {
            try {
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([
                    ':id' => $_SESSION['id'],
                    ':prof_text' => $_POST['prof_text'],
                    ':image' => null,
                ]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } elseif ($_FILES['name'] === null) {
            $image = $prof_img ? $prof_img : null;
            try {
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([
                    ':id' => $_SESSION['id'],
                    ':prof_text' => $_POST['prof_text'],
                    ':image' => $image,
                ]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            $image = uniqid(mt_rand(), true);//ファイル名をユニーク化
            $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得 

            if (!empty($_FILES['image']['name'])) {//ファイルが選択されていれば$imageにファイル名を代入
                move_uploaded_file($_FILES['image']['tmp_name'], './img/' . $image);//imgディレクトリにファイル保存
                if (!exif_imagetype($file)) {//画像ファイルかのチェック
                    echo '画像ファイルではありません';
                }
            } else {
                echo "ファイルが正しくありません";
            }

            try {
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([
                    ':id' => $_SESSION['id'],
                    ':prof_text' => $_POST['prof_text'],
                    ':image' => $image,
                ]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

?>

<?php require_once('header.html'); ?>

<?php require_once('menu.php');?>

<header>
    <h1>プロフィール編集</h1>
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
            <textarea name="prof_text" class="prof_textarea" cols="60" rows="10"><?php echo $prof_text; ?></textarea>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="prof_submit" value="登録" class="prof_submit">

    </form>

</main>
</body>
</html>


