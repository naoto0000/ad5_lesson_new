
<?php

require_once('function.php');

require_once('not_login.php');

require_once('quali_get.php');

if ($_POST['quali_submit']) {
    if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

        // 新規登録の場合
        if ($_POST['quali_new'] !== "") {
            $quali_insert_sql = 'INSERT INTO quali (quali_name) VALUES (:quali_name)';
                
            try{
            $quali_stmt = $pdo->prepare($quali_insert_sql);
            $quali_stmt->bindValue(':quali_name',$_POST['quali_new'],PDO::PARAM_STR);
            $quali_stmt->execute();

            echo "登録しました";

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
                    "UPDATE employees SET quali = TRIM(BOTH ',' FROM REPLACE (CONCAT (',' , quali , ',' ), ',$quali_id,' , ',')) WHERE quali LIKE '%$quali_id%' AND delete_flg IS NULL";
            
                    $delete_stmt = $pdo->prepare($delete_sql);
                    $delete_stmt->execute();
        
                    $pdo->commit();
            
                } catch(Exception $e){
                    $pdo->rollBack();
                }
                // echo "削除しました";
        
            } else {
                $quali_edit_sql = "UPDATE quali 
                SET quali_name = :quali_name WHERE id = :id";
        
                try{
                $quali_edit_stmt = $pdo->prepare($quali_edit_sql);
                $quali_edit_stmt->bindValue(':id',$quali['id'],PDO::PARAM_STR);
                $quali_edit_stmt->bindValue(':quali_name',$quali['quali_name'],PDO::PARAM_STR);
                $quali_edit_stmt->execute();
            
                // echo "更新しました";
        
                }catch(PDOException $e){
                echo $e->getMessage();
                }
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
    <h1>資格マスタ</h1>
</header>

<main>

    <form action="" method="post">

        <div class="quali">
            <table class="quali_table">
                <tr class="table_title">
                    <th class="table_id">ID</th>
                    <th class="quali_table_name">資格名</th>
                </tr>

            <?php foreach ($quali_masta as $index => $quali) : ?>
                <tr class="table_contents  quali_title">
                    <input type="hidden" name="quali[<?php echo $index ?>][id]" value="<?php echo $quali['id']; ?>">
                    <td class="table_id quali_id"><?php echo $quali['id']; ?></td>
                    <td class="quali_table_name"><input type="text" name="quali[<?php echo $index ?>][quali_name]" value="<?php echo $quali['quali_name']; ?>" class="quali_input"></td>
                </tr>
            <?php endforeach ; ?>

                <tr class="table_contents  quali_title">
                    <td class="table_id quali_id"></td>
                    <td class="quali_table_name"><input type="text" name="quali_new" value="" class="quali_input"></td>
                </tr>

            </table>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="quali_submit" value="保存" class="regi_submit quali_submit">

    </form>    

</main>
</body>
</html>