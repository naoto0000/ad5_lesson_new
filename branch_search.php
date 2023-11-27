<?php

require_once('function.php');


// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php');
}


if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if ($page > 1) {
    $start = ($page * 5) - 5;
} else {
    $start = 0;
}

try{
    // 入力値を取得 文字前後の空白除去&エスケープ処理
    $branch_name = trim(htmlspecialchars($_GET['search_branch_name'],ENT_QUOTES));
    // 文字列の中の「　」(全角空白)を「」(何もなし)に変換
    $branch_name = str_replace("　","",$branch_name);

    $base_sql = "SELECT * FROM `branches` ";
    $where_sql = "";
    $limit_sql = "";

    if (isset($branch_name)) {
        $where_sql .= "WHERE (branch_name LIKE :word )";
        $base_sql .= $where_sql;
        $branches = $pdo->prepare($base_sql);
        $branches->bindValue(':word',"%{$branch_name}%",PDO::PARAM_STR);
        $branches->execute();

        $search_count = $branches->rowCount();

        $limit_sql = "SELECT * FROM `branches` WHERE (branch_name LIKE :word) ORDER BY order_list LIMIT {$start},5";
        $branches = $pdo->prepare($limit_sql);
        $branches->bindValue(':word',"%{$branch_name}%",PDO::PARAM_STR);
        $branches->execute();

    } else {
        $branches = $pdo->prepare($base_sql);
        $branches->execute();
        $search_count = $branches->rowCount();

        $limit_sql = "SELECT * FROM `branches` ORDER BY order_list LIMIT {$start},5";
        $branches = $pdo->query($limit_sql);
    }

    require_once('page_gene_search.php');

}catch(PDOException $e) {
    echo $e->getMessage();
}

$sexCotegory = [
    ['value' => '', 'text' => '全て'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女'],
    ['value' => '3', 'text' => '不明'],
];

$pdo = null;

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
        <form action="" method="get">
            <label for="">支店名</label>
                <input type="text" name="search_branch_name" class="input_branch_name" value="<?php  echo $branch_name ?>">

            <input type="submit" name="search_submit" value="検索" class="search_submit">
        </form>
    </div>

    <?php if ($search_count == 0): ?>
        <p class="search_none">該当する社員がいません</p>
    <?php else: ?>
        <table class="branch_table">
        <tr class="table_title">
            <th>支店名</th>
            <th>電話番号</th>
            <th>住所</th>
            <th></th>
        </tr>

        <?php require_once('branch_escape.php'); ?>
        
        </table>
    <?php endif; ?>

<!-- 検索結果が５件未満の場合ページネーションを表示させない -->
<?php if ($search_count > 6): ?>

    <?php require_once('page_display_search.php'); ?>

<?php endif; ?>

</main>
</body>
</html>