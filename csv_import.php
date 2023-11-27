
<?php 

require_once('function.php');



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
    <h1>社員一括編集</h1>

</header>

<main>
    <div class="csv_import">
        <input type="file">
        <input type="submit" value="インポート実行" class="csv_import_submit">
    </div>

</main>
</body>
</html>