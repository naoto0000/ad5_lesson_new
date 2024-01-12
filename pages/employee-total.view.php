<?php require_once('header.html'); ?>
<?php require_once('menu.php');?>

<header id="header">
    <h1>社員集計</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
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