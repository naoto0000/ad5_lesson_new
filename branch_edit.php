<?php 
require_once('function.php');
require_once('not_login.php');

$id = $_GET['id'];

// 編集対象のデータをidをもとにとってくる
try{
    $edit_sql = "SELECT * FROM branches WHERE id = :id";
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(":id", $id,PDO::PARAM_INT);
    $edit_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}
// 取得できたデータを変数に入れておく
$edit_row = $edit_stmt->fetch(PDO::FETCH_ASSOC);
$branch_name = $edit_row['branch_name'];
$tel_number = $edit_row['tel_number'];
$pref = $edit_row['prefectures'];
$address = $edit_row['address'];
$address2 = $edit_row['address2'];
$address3 = $edit_row['address3'];
$order_list = $edit_row['order_list'];

if (isset($_POST['edit_submit'])) {

    function validate(array $post_data)
    {
        $_SESSION['validation_errors'] = [];

        if ($post_data['edit_branch_name'] === "") {
            $_SESSION['validation_errors']['branch_name'] = true;
        }
        if ($post_data['pref'] === "") {
            $_SESSION['validation_errors']['pref'] = true;
        }
        if ($post_data['edit_branch_tel'] === "") {
            $_SESSION['validation_errors']['branch_tel'] = true;
        }
        $tel_check = preg_match('/^0[0-9]{1,4}-[0-9]{1,4}-[0-9]{3,4}\z/', $post_data['edit_branch_tel']);
        if ($tel_check !== 1) {
            $_SESSION['validation_errors']['tel_check'] = true;
        }
        if ($post_data['edit_order'] === "") {
            $_SESSION['validation_errors']['branch_order'] = true;
        }
    }

    validate($_POST);

    if (count($_SESSION['validation_errors']) === 0) {
        if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {
            $sql = "UPDATE branches 
                    SET branch_name = :branch_name, tel_number = :tel_number, prefectures = :prefectures,
                        address = :address, address2 = :address2, address3 = :address3, order_list = :order_list 
                    WHERE id = :id";

            try{
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':branch_name' => $_POST['edit_branch_name'],
                ':tel_number' => $_POST['edit_branch_tel'],
                ':prefectures' => $_POST['pref'],
                ':address' => $_POST['address'],
                ':address2' => $_POST['address2'],
                ':address3' => $_POST['address3'],
                ':order_list' => $_POST['edit_order']
            ]);
        
            $_SESSION['message'] = 1;
            header("Location: branch.php");
    
            }catch(PDOException $e){
            echo $e->getMessage();
            }
        }
    } else {
    echo"ERROR：不正な登録処理です";
    }
}

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

include(__DIR__ . '/utilities/pref_cotegory.php');

include(__DIR__ . '/pages/branch-edit.view.php');
