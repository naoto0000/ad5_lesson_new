
<?php 

require_once('function.php');

// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php');
}

$prof_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL";
$prof = $pdo->query($prof_sql);

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

    if ($prof_img !== "" && $_POST['image'] == "" && $_POST['prof_delete'] == "") {
        $prof_get_sql = "UPDATE employees 
        SET prof_text = :prof_text WHERE id = :id";
    
        try{
        $prof_get_stmt = $pdo->prepare($prof_get_sql);
        $prof_get_stmt->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
        $prof_get_stmt->bindValue(':prof_text',$_POST['prof_text'],PDO::PARAM_STR);
    
        $prof_get_stmt->execute();
    
        
        echo "更新しました";
    
        }catch(PDOException $e){
        echo $e->getMessage();    
        }
    
    } elseif (isset($_POST['prof_delete']) && $_POST['prof_text'] !== "") {
        $prof_get_sql = "UPDATE employees 
        SET image = :image, prof_text = :prof_text WHERE id = :id";
    
        try{
        $prof_get_stmt = $pdo->prepare($prof_get_sql);
        $prof_get_stmt->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
        $prof_get_stmt->bindValue(':image',"",PDO::PARAM_STR);
        $prof_get_stmt->bindValue(':prof_text',$_POST['prof_text'],PDO::PARAM_STR);
    
        $prof_get_stmt->execute();
    
        
        echo "更新しました";
    
        }catch(PDOException $e){
        echo $e->getMessage();    
        }

    } elseif ($_POST['prof_delete'] !== "" && $_POST['prof_text'] == "") {

        $prof_get_sql = "UPDATE employees 
        SET image = :image WHERE id = :id";
    
        try{
        $prof_get_stmt = $pdo->prepare($prof_get_sql);
        $prof_get_stmt->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
        $prof_get_stmt->bindValue(':image',"",PDO::PARAM_STR);
    
        $prof_get_stmt->execute();
    
        echo "更新しました";
    
        }catch(PDOException $e){
        echo $e->getMessage();    
        }

    } else {
        $prof_get_sql = "UPDATE employees 
        SET image = :image, prof_text = :prof_text WHERE id = :id";
    
        try{
        $prof_get_stmt = $pdo->prepare($prof_get_sql);
        $prof_get_stmt->bindValue(':id',$_SESSION['id'],PDO::PARAM_INT);
        $prof_get_stmt->bindValue(':image',$_POST['image'],PDO::PARAM_STR);
        $prof_get_stmt->bindValue(':prof_text',$_POST['prof_text'],PDO::PARAM_STR);
    
        $prof_get_stmt->execute();
    
        
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
    <h1>プロフィール編集</h1>

</header>

<main>
    <form action="" method="post">
        <table class="prof_table">
            <tr>
                <td class="prof_title prof_list">氏名</td>
                <td class="prof_list"><?php echo $prof_name; ?></td>
                <td class="prof_title prof_list">生年月日</td>
                <td class="prof_list"><?php echo $prof_birthdate; ?>( <?php echo $prof_age; ?> 歳)</td>
            </tr>
        </table>

        <?php if ($prof_img !== "") : ?>
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

        <input type="submit" name="prof_submit" value="登録" class="prof_submit">

    </form>

</main>
</body>
</html>


