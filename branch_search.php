<?php
require_once('function.php');
require_once('not_login.php');

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

require(__DIR__ . '/entities/branch_class.php');

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
        $branches = $branches->fetchAll(PDO::FETCH_ASSOC);
        $branches = array_map(function ($branch) {
            return new Branch($branch);
        }, $branches);        

    } else {
        $branches = $pdo->prepare($base_sql);
        $branches->execute();
        $search_count = $branches->rowCount();

        $limit_sql = "SELECT * FROM `branches` ORDER BY order_list LIMIT {$start},5";
        $branches = $pdo->query($limit_sql);
        $branches = $branches->fetchAll(PDO::FETCH_ASSOC);
        $branches = array_map(function ($branch) {
            return new Branch($branch);
        }, $branches);        
    }

    include(__DIR__ . '/pagenation/page_gene_search.php');

}catch(PDOException $e) {
    echo $e->getMessage();
}

include(__DIR__ . '/pages/branch-search.view.php');