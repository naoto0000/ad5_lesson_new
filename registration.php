<?php
require_once('function.php');
require_once('not_login.php');

if (isset($_POST['regi_pass'])) {
    $hashed_pass = password_hash($_POST['regi_pass'], PASSWORD_DEFAULT);
}

if (isset($_POST['regi_submit'])) {

    function validate(array $post_data)
    {
        $_SESSION['validation_errors'] = [];

        if ($post_data['regi_name'] === "") {
            $_SESSION['validation_errors']['name'] = true;
        }

        if ($post_data['regi_kana'] === "") {
            $_SESSION['validation_errors']['kana'] = true;
        }

        if ($post_data['regi_branch'] === "") {
            $_SESSION['validation_errors']['branch'] = true;
        }

        if ($post_data['regi_mail'] === "") {
            $_SESSION['validation_errors']['mail'] = true;
        }

        // メールアドレスの形式チェック
        $mail_result =
            preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $post_data['regi_mail']);
        if ($mail_result === 0) {
            $_SESSION['validation_errors']['mail_check'] = true;
        }

        // メールアドレスの重複チェック
        include(__DIR__ . '/utilities/mail_sql.php');

        foreach ($mail_match_row as $mail_match) {
            if ($mail_match['email'] == $post_data['regi_mail']) {
                $mail_match_result = 1;
            }
        }
        if ($mail_match_result === 1) {
            $_SESSION['validation_errors']['mail_match'] = true;
        }

        $pass_result = preg_match('/^[a-zA-Z0-9]{8,}$/', ($post_data['regi_pass']));
        if ($pass_result === 0) {
            $_SESSION['validation_errors']['pass_check'] = true;
        }

        if ($post_data['regi_pass'] === "") {
            $_SESSION['validation_errors']['pass'] = true;
        }

        if (!isset($post_data['regi_blood']) || $post_data['regi_blood'] === NULL) {
            $_SESSION['validation_errors']['blood'] = true;
        }
    }

    validate($_POST);

    $quali = "";
    if (isset($_POST['regi_quali'])) {
        // 資格をカンマ区切りに変換
        $quali = implode(',', $_POST['regi_quali']);
    }    

    // 生年月日を空で送信した時の処理
    if ($_POST['regi_birth'] === "") {
        $_POST['regi_birth'] = null;
    }

        if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) {
            // 登録処理
            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

                $sql = 'INSERT INTO `employees` 
                (`name`, `kana`, `sex`, `birthdate`, `email`, `password`, `comm_time`, `blood_type`, `married`, `branch_id`, `quali`) 
                VALUES (:name, :kana, :sex, :birthdate, :email, :password, :comm_time, :blood_type, :married, :branch_id, :quali)';
    
                try {
                    $stmt = $pdo->prepare($sql);
    
                    $stmt->execute([
                        ':name' => $_POST['regi_name'],
                        ':kana' => $_POST['regi_kana'],
                        ':sex' => $_POST['regi_sex'],
                        ':birthdate' => $_POST['regi_birth'],
                        ':email' => $_POST['regi_mail'],
                        ':password' => $hashed_pass,
                        ':comm_time' => $_POST['regi_com'],
                        ':blood_type' => $_POST['regi_blood'],
                        ':married' => isset($_POST['regi_married']) ? $_POST['regi_married'] : null,
                        ':branch_id' => $_POST['regi_branch'],
                        ':quali' => $quali
                    ]);
    
                    echo "登録しました";
                } catch (PDOException $e) {
                    echo "エラーが発生しました: " . $e->getMessage();
                }
            }
        } else {
            echo "ERROR：不正な登録処理です";
        }
}

include(__DIR__ . '/utilities/branch_sql.php');
include(__DIR__ . '/utilities/quali_get.php');


$sexCotegory = [
    ['value' => '3', 'text' => '選択'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女']
];

include(__DIR__ . '/utilities/blood_category.php');

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

include(__DIR__ . '/pages/employee-regi.view.php');
