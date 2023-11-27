
<?php 
require_once('function.php');


// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php');
}

$total_sex_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL";
$employees_total = $pdo->query($total_sex_sql);

$man = "";
$woman = "";
$unknown = "";

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



$total_branch_sql = "SELECT * FROM `branches` ORDER BY order_list";
$branches_total = $pdo->query($total_branch_sql);



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

                <?php foreach ($branches_total as $emp_branch): ?>
                    <tr class="table_contents">
                        <td class="total_subtitle"><?php echo htmlspecialchars($emp_branch['branch_name'], ENT_QUOTES); ?></td>
                        <td class="total_count"><?php echo 1; ?></td>
                    </tr>
                <?php endforeach; ?>
        </table>


    </div>


</main>




</body>
</html>