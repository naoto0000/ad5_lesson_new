<?php
require_once('function.php');

// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php');
}


// データ取得のための変数
$count_sql = "SELECT COUNT(*) as cnt FROM employees WHERE delete_flg IS NULL";

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
$base_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL LIMIT {$start},5";
$employees = $pdo->query($base_sql);

require_once('branch_get.php');

$branch_text = "";

// lesson14 削除メッセージ表示
$delete_msg = $_GET['delete_msg'];

if ($delete_msg == 1) {
    echo "削除しました";
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
    <h1>社員一覧</h1>

</header>

<main>
    
    <div class="search">
        <form action="search.php" method="get">

            <label for="">氏名</label>
            <input type="text" name="search_name" class="input_name">
            <label for="">性別</label>
            <select name="search_sex" class="select_sex">
                <option value="">全て</option>
                <option value="1">男</option>
                <option value="2">女</option>
                <option value="3">不明</option>
            </select>
            <label for="">支店</label>
            <select name="search_branch" class="select_branch" >

                    <option value="" selected>全て</option>

                    <?php 
                        foreach ($branch_row as $branch_name_search){
                            echo '<option value="'. $branch_name_search['id'] .'">' . $branch_name_search['branch_name'] . '</option>';
                        }
                    ?>
            </select>

            <input type="submit" name="search_submit" value="検索" class="search_submit">

            <input type="submit" name="csv_submit" value="CSVダウンロード" class="csv_submit">

        </form>

    </div>

    <table>
        <tr class="table_title">
            <th>氏名</th>
            <th>かな</th>
            <th>支店</th>
            <th>性別</th>
            <th>年齢</th>
            <th>生年月日</th>
            <th></th>
        </tr>

        <?php require_once('escape.php'); ?>

    </table>

    <?php require_once('page_display.php'); ?>

</main>
</body>
</html>