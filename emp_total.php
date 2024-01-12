<?php 
require_once('function.php');
require_once('not_login.php');

$total_sex_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL";
$employees_total = $pdo->query($total_sex_sql);

$man = 0;
$woman = 0;
$unknown = 0;

foreach ($employees_total as $emp_sex) {
    if ($emp_sex['sex'] == 1) {
        $man += 1 ;
    } elseif ($emp_sex['sex'] == 2) {
        $woman += 1 ;
    } else {
        $unknown += 1 ;
    }
}

$total_sex = $man + $woman + $unknown;

// 支店集計
// ＝＝＝＝
$total_branch_sql =
"SELECT
b.branch_name as branch_name,
COUNT(e.branch_id) as emp_count
FROM
employees as e
INNER JOIN
branches as b
ON
e.branch_id = b.id
WHERE delete_flg IS NULL
GROUP BY
e.branch_id
ORDER BY b.order_list";

$branches_total = $pdo->query($total_branch_sql);

$branches_total_row = $branches_total->fetchAll(PDO::FETCH_ASSOC);

include(__DIR__ . '/pages/employee-total.view.php');