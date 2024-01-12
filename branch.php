<?php 
require_once('function.php');
require_once('not_login.php');

// データ取得のための変数
$count_sql = 'SELECT COUNT(*) as cnt FROM branches';

include(__DIR__ . '/pagenation/page_get.php');
include(__DIR__ . '/pagenation/page_gene.php');

require(__DIR__ . '/entities/branch_class.php');

// 取得データを５件のみ表示
$base_sql = "SELECT * FROM `branches` ORDER BY order_list LIMIT {$start},5";
$branches = $pdo->query($base_sql)->fetchAll(PDO::FETCH_ASSOC);
$branches = array_map(function ($branch) {
    return new Branch($branch);
}, $branches);

// 編集メッセージ表示
if (isset($_SESSION['message']) && $_SESSION['message'] === 1) {
    echo "更新しました";
    $_SESSION['message'] = "";
}

include(__DIR__ . '/pages/branch-list.view.php');