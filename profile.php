<?php 
require_once('function.php');
require_once('not_login.php');

$prof_sql = "SELECT * FROM `employees` WHERE id = :id AND delete_flg IS NULL";
$prof = $pdo->prepare($prof_sql);
$prof->execute([
    'id' => $_SESSION['id']
]);  

foreach ($prof as $prof_row) {
        $prof_birthdate_get = $prof_row['birthdate'];
        $prof_name = $prof_row['name'];       
        $prof_img =  $prof_row['image'];
        $prof_text =  $prof_row['prof_text'];
}

// 現在日付
$now = date('Ymd');

// 誕生日
$birthday_age = str_replace("-", "", $prof_birthdate_get);

$prof_birthdate = str_replace("-", ".", $prof_birthdate_get);

// 年齢
$prof_age = floor(($now - $birthday_age) / 10000);

if (isset($_POST['prof_submit'])) {
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
                    ':image' => null
                ]);
                echo "更新しました";
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        // 画像の新規登録がない場合
        } elseif ($_FILES['image']['name'] === "") {
            $image = $prof_img ? $prof_img : null;
            try {
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([
                    ':id' => $_SESSION['id'],
                    ':prof_text' => $_POST['prof_text'],
                    ':image' => $image
                ]);
                echo "更新しました";
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        // 画像の新規登録がある場合
        } else {
            $image = uniqid(mt_rand(), true);//ファイル名をユニーク化
            $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
            $file = "img/$image"; 

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
                    ':image' => $image
                ]);
                echo "更新しました";
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

include(__DIR__ . '/pages/profile.view.php');