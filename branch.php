<?php 
require_once('function.php');
// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php');
}


// データ取得のための変数
$count_sql = 'SELECT COUNT(*) as cnt FROM branches';

// ページ数を取得する。GETでページが渡ってこなかった時（最初のページ）は$pageに１を格納する。
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$counts = $pdo -> query($count_sql);
$count = $counts -> fetch(PDO::FETCH_ASSOC);

require_once('page_gene.php');

// 取得データを５件のみ表示
$base_sql = "SELECT * FROM `branches` ORDER BY order_list LIMIT {$start},5";
$branches = $pdo->query($base_sql);
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
    <h1>支店一覧</h1>

</header>

<main>
        <div class="search">
        <form action="branch_search.php" method="get">

            <label for="">支店名</label>
            <input type="text" name="search_branch_name" class="input_branch_name">

            <input type="submit" name="search_submit" value="検索" class="search_submit">

        </form>

    </div>

    <table class="branch_table">
        <tr class="table_title">
            <th>支店名</th>
            <th>電話番号</th>
            <th>住所</th>
            <th></th>
        </tr>

        <?php require_once('branch_escape.php'); ?>

    </table>

    <?php require_once('page_display.php'); ?>

</main>
</body>
</html>
