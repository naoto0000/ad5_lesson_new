
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
        $prof_kana = $prof_row['kana'];       
        $prof_img =  $prof_row['image'];
        $prof_text =  $prof_row['prof_text'];
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
        <h1>社員詳細 : <?php echo $prof_name; ?>さん</h1>

    </header>

    <main>

        <div class="detail_class">
            <img src="img/<?php echo $prof_img; ?>" alt="" class="detail_img">

            <section>
                <div class="detail_contents">
                    <h2 class="detail_title">氏名</h2>
                    <p class="detail_text"><?php echo $prof_name; ?>(<?php echo $prof_kana; ?>)</p>
                </div>
                <div class="detail_contents">
                    <h2 class="detail_title">生年月日</h2>
                    <p class="detail_text"></p>
                </div>
                <div class="detail_contents">
                    <h2 class="detail_title">支店</h2>
                    <p class="detail_text"></p>
                </div>
                <div class="detail_contents">
                    <h2 class="detail_title">血液型</h2>
                    <p class="detail_text"></p>
                </div>
                <div class="detail_contents">
                    <h2 class="detail_title">資格</h2>
                    <p class="detail_text"></p>
                </div>
                <div class="detail_contents">
                    <h2 class="detail_title">紹介文</h2>
                    <p class="detail_text"></p>
                </div>
            </section>

        </div>


    </main>

</body>
</html>
