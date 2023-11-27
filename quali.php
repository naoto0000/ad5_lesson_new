
<?php

require_once('function.php');

$quali_get_sql = "SELECT * FROM `quali`";
$quali_masta = $pdo->query($quali_get_sql);

if ($_POST['quali_submit']) {
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

    } elseif ($_POST['quali_edit'] == "") {
        $quali_delete_sql = "DELETE FROM quali WHERE id = :id";

        $quali_delete_stmt = $pdo->prepare($quali_delete_sql);
        $quali_delete_stmt->bindValue(':id',$_POST['quali_id'],PDO::PARAM_INT);

        $quali_delete_stmt->execute();

        echo "削除しました";

    } else {
        $quali_edit_sql = "UPDATE quali 
        SET quali_name = :quali_name WHERE id = :id";

        try{
        $quali_edit_stmt = $pdo->prepare($quali_edit_sql);
        $quali_edit_stmt->bindValue(':id',$_POST['quali_id'],PDO::PARAM_INT);
        $quali_edit_stmt->bindValue(':quali_name',$_POST['quali_edit'],PDO::PARAM_STR);
        $quali_edit_stmt->execute();
    
        echo "更新しました";

        }catch(PDOException $e){
        echo $e->getMessage();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AD5 lesson</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
</head>
<body>

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

            <?php foreach ($quali_masta as $quali) : ?>
                <tr class="table_contents  quali_title">
                    <input type="hidden" name="quali_id" value="<?php echo $quali['id']; ?>">
                    <td class="table_id quali_id"><?php echo $quali['id']; ?></td>
                    <td class="quali_table_name"><input type="text" name="quali_edit" value="<?php echo $quali['quali_name']; ?>" class="quali_input"></td>
                </tr>
            <?php endforeach ; ?>

                <tr class="table_contents  quali_title">
                    <td class="table_id quali_id"></td>
                    <td class="quali_table_name"><input type="text" name="quali_new" value="" class="quali_input"></td>
                </tr>



            </table>
        </div>

        <input type="submit" name="quali_submit" value="保存" class="regi_submit quali_submit">


    </form>    


</main>


</body>
</html>