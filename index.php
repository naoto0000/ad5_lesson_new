<?php
require_once('function.php');
require_once('not_login.php');

// データ取得のための変数
$count_sql = "SELECT COUNT(*) as cnt FROM employees WHERE delete_flg IS NULL";

include(__DIR__ . '/pagenation/page_get.php');
include(__DIR__ . '/pagenation/page_gene.php');
require(__DIR__ . '/entities/employee_class.php');

// 取得データを５件のみ表示
$base_sql = "SELECT e.*, b.branch_name 
FROM employees AS e 
INNER JOIN branches AS b 
ON e.branch_id = b.id 
WHERE delete_flg IS NULL LIMIT {$start},5";

$employees = $pdo->query($base_sql)->fetchAll(PDO::FETCH_ASSOC);
$employees = array_map(function ($employee) {
    return new Employee($employee);
}, $employees);

include(__DIR__ . '/utilities/branch_sql.php');

if (isset($_SESSION['message']) && $_SESSION['message'] === 1) {
    echo "更新しました";
    $_SESSION['message'] = "";
}

include(__DIR__ . '/pages/employee-list.view.php');