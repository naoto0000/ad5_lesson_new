
<?php 
// ページ数を取得する。GETでページが渡ってこなかった時（最初のページ）は$pageに１を格納する。
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$counts = $pdo -> query($count_sql);
$count = $counts -> fetch(PDO::FETCH_ASSOC);
?>