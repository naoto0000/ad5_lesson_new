
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

?>

<?php require_once('header.html'); ?>

<?php require_once('menu.php');?>

<header>
    <h1>社員集計</h1>
</header>

<main>

    <div class="emp_total">
        <h2 class="total_title">表1  : 男女別社員数</h2>

        <table class="total_table">
            <tr class="table_title">
                <th>性別</th>
                <th>社員数</th>
            </tr>

            <tr class="table_contents">
                <td class="total_subtitle">男性</td>
                <td class="total_count"><?php echo $man; ?></td>
            </tr>
            <tr class="table_contents">
                <td class="total_subtitle">女性</td>
                <td class="total_count"><?php echo $woman; ?></td>
            </tr>
            <tr class="table_contents">
                <td class="total_subtitle">未登録</td>
                <td class="total_count"><?php echo $unknown; ?></td>
            </tr>
            <tr class="table_contents">
                <td class="total_subtitle">合計</td>
                <td class="total_count"><?php echo $total_sex; ?></td>
            </tr>

        </table>

    </div>

    <div class="emp_total">
        <h2 class="total_title">表2  : 部門別社員数</h2>

        <table class="total_table2">
            <tr class="table_title">
                <th>部門</th>
                <th>社員数</th>
            </tr>

                <?php foreach ($branches_total_row as $branches_total): ?>
                    <tr class="table_contents">
                        <td class="total_subtitle"><?php echo htmlspecialchars($branches_total['branch_name'], ENT_QUOTES); ?></td>
                        <td class="total_count"><?php echo $branches_total['emp_count']; ?></td>
                    </tr>
                <?php endforeach; ?>
        </table>
    </div>
</main>
</body>
</html>