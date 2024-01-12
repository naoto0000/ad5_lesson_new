<?php
require_once('function.php');
require_once('not_login.php');

$id = $_GET['id'];

$errar_mes = "";

if (!$id) {
    // 一覧画面へ戻る
    header('Location: index.php');
}

// 編集対象のデータをidをもとにとってくる
try {
    $edit_sql = "SELECT * FROM employees WHERE id = :id";
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $edit_stmt->execute();
} catch (PDOException $e) {
    echo $e->getMessage();
}
// 取得できたデータを変数に入れておく
$edit_row = $edit_stmt->fetch(PDO::FETCH_ASSOC);

if ($edit_row === false) {
    $errar_mes = "URLが間違っています";
} else {
    $name = $edit_row['name'];
    $kana = $edit_row['kana'];
    $sex = $edit_row['sex'];
    $birthdate = $edit_row['birthdate'];
    $email = $edit_row['email'];
    $comm_time = (int)$edit_row['comm_time'];
    $blood_type = $edit_row['blood_type'];
    $married = $edit_row['married'];
    $branch_id = $edit_row['branch_id'];
    $quali_id = $edit_row['quali'];
}

include(__DIR__ . '/utilities/branch_sql.php');
include(__DIR__ . '/utilities/quali_get.php');
include(__DIR__ . '/utilities/blood_category.php');

if (isset($_POST['edit_submit'])) {

    function validate(array $post_data)
    {
        $_SESSION['validation_errors'] = [];
        if ($post_data['edit_name'] === "") {
            $_SESSION['validation_errors']['name'] = true;
        }
        if ($post_data['edit_kana'] === "") {
            $_SESSION['validation_errors']['kana'] = true;
        }
        if ($post_data['edit_branch'] === "") {
            $_SESSION['validation_errors']['branch'] = true;
        }
        if ($post_data['edit_mail'] === "") {
            $_SESSION['validation_errors']['mail'] = true;
        }

        $mail_result = preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $post_data['edit_mail']);
        if ($mail_result === 0) {
            $_SESSION['validation_errors']['mail_check'] = true;
        }

        include(__DIR__ . '/utilities/mail_sql.php');
        // メールアドレスが他の従業員とかぶっていないかチェック
        foreach ($mail_match_row as $mail_match) {
            if ($mail_match['email'] == $post_data['edit_mail']) {
                $mail_match_result = 1;
            }
        }
        // 関数外で記載の既存データを取得
        global $email;
        // メールアドレスを変えずに送信しても大丈夫にしてる
        if ($email == $post_data['edit_mail']) {
            $mail_match_result = "";
        }
        if ($mail_match_result === 1) {
            $_SESSION['validation_errors']['mail_match'] = true;
        }

        // パスワードが入力されていた場合のみ、パスワードのチェックを行う
        if ($post_data['edit_pass'] !== "") {
            $pass_result = preg_match('/^[a-zA-Z0-9]{8,}$/', ($post_data['edit_pass']));
            if ($pass_result === 0) {
                $_SESSION['validation_errors']['pass_check'] = true;
            }    
        }

        if ($post_data['edit_blood'] === "") {
            $_SESSION['validation_errors']['blood'] = true;
        }
    }

    validate($_POST);    

    $quali = "";
    if (isset($_POST['edit_quali'])) {
        // 資格をカンマ区切りに変換
        $quali = implode(',', $_POST['edit_quali']);
    }    

    // 生年月日を空で送信した時の処理
    if ($_POST['edit_birth'] === "") {
        $_POST['edit_birth'] = null;
    }

    $edit_com_int = (int)$_POST['edit_com'];

    // パスワードのハッシュ化
    $hashed_pass = password_hash($_POST['edit_pass'], PASSWORD_DEFAULT);

    // パスワードの入力有無の判定
    if ($_POST['edit_pass'] !== "") {
        if (count($_SESSION['validation_errors']) === 0) {
            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {
                    $sql = "UPDATE employees 
                    SET name = :name, kana = :kana, sex = :sex, birthdate = :birthdate, 
                        email = :email, password = :password, comm_time = :comm_time, blood_type = :blood_type, married = :married, branch_id = :branch_id, quali = :quali
                    WHERE id = :id";

                    try {
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':id' => $id,
                            ':name' => $_POST['edit_name'],
                            ':kana' => $_POST['edit_kana'],
                            ':sex' => $_POST['edit_sex'],
                            ':birthdate' => $_POST['edit_birth'],
                            ':email' => $_POST['edit_mail'],
                            ':password' => $hashed_pass,
                            ':comm_time' => $_POST['edit_com'],
                            ':blood_type' => $_POST['edit_blood'],
                            ':married' => $_POST['edit_married'],
                            ':branch_id' => $_POST['edit_branch'],
                            ':quali' => $quali
                            ]);

                        // TODO sessionに格納
                        $_SESSION['message'] = 1;

                        header("Location: index.php");
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
            }
        } else {
            echo "ERROR：不正な登録処理です";
        }
    } else {
        if (count($_SESSION['validation_errors']) === 0) {
            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

                $sql = "UPDATE employees 
                SET name = :name, kana = :kana, sex = :sex, birthdate = :birthdate, 
                    email = :email, comm_time = :comm_time, blood_type = :blood_type, married = :married, branch_id = :branch_id, quali = :quali
                WHERE id = :id";

                try {
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':id' => $id,
                        ':name' => $_POST['edit_name'],
                        ':kana' => $_POST['edit_kana'],
                        ':sex' => $_POST['edit_sex'],
                        ':birthdate' => $_POST['edit_birth'],
                        ':email' => $_POST['edit_mail'],
                        ':comm_time' => $_POST['edit_com'],
                        ':blood_type' => $_POST['edit_blood'],
                        ':married' => $_POST['edit_married'],
                        ':branch_id' => $_POST['edit_branch'],
                        ':quali' => $quali
                    ]);

                    $_SESSION['message'] = 1;

                    header("Location: index.php");
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
        } else {
            echo "ERROR：不正な登録処理です";
        }
    }
}

$sexCotegory = [
    ['value' => '3', 'text' => '選択'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女']
];

// 削除部分
if (isset($_POST['delete_submit'])) {
    if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

        $delete_sql = "UPDATE employees SET delete_flg = :delete_flg WHERE id = :id";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([
            ':id' => $id,
            ':delete_flg' => 1
        ]);

        $_SESSION['message'] = 1;

        header("Location: index.php");
    }
}

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

include(__DIR__ . '/pages/employee-edit.view.php');
