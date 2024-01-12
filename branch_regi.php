<?php 
require_once('function.php');
require_once('not_login.php');

if (isset($_POST['regi_submit'])) {

    function validate(array $post_data)
    {
        $_SESSION['validation_errors'] = [];

        if ($post_data['regi_branch_name'] === "") {
            $_SESSION['validation_errors']['branch_name'] = true;
        }
        if ($post_data['pref'] === "") {
            $_SESSION['validation_errors']['pref'] = true;
        }
        if ($post_data['regi_branch_tel'] === "") {
            $_SESSION['validation_errors']['branch_tel'] = true;
        }
        $tel_check = preg_match('/^0[0-9]{1,4}-[0-9]{1,4}-[0-9]{3,4}\z/', $post_data['regi_branch_tel']);
        if ($tel_check !== 1) {
            $_SESSION['validation_errors']['tel_check'] = true;
        }
        if ($post_data['regi_order'] === "") {
            $_SESSION['validation_errors']['branch_order'] = true;
        }
    }

    validate($_POST);

    if (count($_SESSION['validation_errors']) === 0) {
        if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

            $sql = 'INSERT INTO branches
            (branch_name, tel_number, prefectures, address, address2, address3, order_list) 
            VALUES (:branch_name, :tel_number, :prefectures, :address, :address2, :address3, :order_list)';
                
            try{
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':branch_name' => $_POST['regi_branch_name'],
                ':tel_number' => $_POST['regi_branch_tel'],
                ':prefectures' => $_POST['pref'],
                ':address' => $_POST['address'],
                ':address2' => $_POST['address2'],
                ':address3' => $_POST['address3'],
                ':order_list' => $_POST['regi_order']
            ]);
    
            echo "登録しました";

            }catch(PDOException $e){
            echo $e->getMessage();
            }
        } else {
            echo"ERROR：不正な登録処理です";
        }
    }
}

include(__DIR__ . '/utilities/pref_cotegory.php');

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

include(__DIR__ . '/pages/branch-regi.view.php');