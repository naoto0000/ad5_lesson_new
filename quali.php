<?php
require_once('function.php');
require_once('not_login.php');
include(__DIR__ . '/utilities/quali_get.php');

if (isset($_POST['quali_submit'])) {
    if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

        // 新規登録の場合
        if ($_POST['quali_new'] !== "") {
            $quali_insert_sql = 'INSERT INTO quali (quali_name) VALUES (:quali_name)';
                
            try{
            $quali_stmt = $pdo->prepare($quali_insert_sql);
            $quali_stmt->execute([
                ':quali_name' => $_POST['quali_new']
            ]);

            $_SESSION['message'] = 1;

            }catch(PDOException $e){
            echo $e->getMessage();
            }
        } 

        $qualis = $_POST['quali'];

        foreach ($qualis as $quali) {
            if ($quali['quali_name'] === "") {

                try {
                    $pdo->beginTransaction();

                    $quali_delete_sql = "DELETE FROM quali WHERE id = :id";
            
                    $quali_delete_stmt = $pdo->prepare($quali_delete_sql);
                    $quali_delete_stmt->bindValue(':id',$quali['id'],PDO::PARAM_INT);
            
                    $quali_delete_stmt->execute();

                    // エスケープ処理
                    $quali_id = htmlspecialchars($quali['id'], ENT_QUOTES);
            
                    $delete_sql = 
                    "UPDATE employees SET quali = TRIM(BOTH ',' FROM REPLACE (CONCAT (',' , quali , ',' ), ',$quali_id,' , ',')) 
                    WHERE quali LIKE '%$quali_id%' AND delete_flg IS NULL";
            
                    $delete_stmt = $pdo->prepare($delete_sql);
                    $delete_stmt->execute();
        
                    $pdo->commit();

                    $_SESSION['message'] = 1;
            
                } catch(Exception $e){
                    $pdo->rollBack();
                }
        
            } else {
                $quali_edit_sql = "UPDATE quali 
                SET quali_name = :quali_name WHERE id = :id";
        
                try{
                $quali_edit_stmt = $pdo->prepare($quali_edit_sql);
                $quali_edit_stmt->bindValue(':id',$quali['id'],PDO::PARAM_STR);
                $quali_edit_stmt->bindValue(':quali_name',$quali['quali_name'],PDO::PARAM_STR);
                $quali_edit_stmt->execute();
            
                $_SESSION['message'] = 1;
        
                }catch(PDOException $e){
                echo $e->getMessage();
                }
            }
        }
    }
}

// 編集メッセージ表示
if (isset($_SESSION['message']) && $_SESSION['message'] === 1) {
    echo "更新しました";
    $_SESSION['message'] = "";
}

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

include(__DIR__ . '/pages/quali.view.php');