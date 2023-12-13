
<?php 

require_once('function.php');

require_once('not_login.php');

// データ取得のための変数
$count_sql = "SELECT COUNT(*) as cnt FROM group_table";

require_once('page_get.php');

require_once('page_gene.php');

// 取得データを５件のみ表示
$base_sql = "SELECT * FROM `group_table` ORDER BY group_order LIMIT {$start},5";
$group_row = $pdo->query($base_sql);


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
    <h1>部門一覧</h1>

</header>

<main>
    
    <table>
        <tr class="table_title">
            <th>部門コード</th>
            <th>部門名</th>
            <th>親部門</th>
            <th>所属人数</th>
            <th></th>
        </tr>

        <?php foreach ($group_row as $group) : ?>

        <tr>
            <td><?php echo htmlspecialchars($group['group_code'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($group['group_name'], ENT_QUOTES); ?></td>

            <?php if ($group['parent_group'] == "") : ?>
                <td>なし</td>
            <?php else : ?>
                <td><?php echo htmlspecialchars($group['parent_group'], ENT_QUOTES); ?></td>
            <?php endif; ?>

            <td class="group_count">10</td>

            <td>
                <button name="edit_group_button" class="edit_button">
                    <a href="group_edit.php?id=<?php echo htmlspecialchars($group['id'], ENT_QUOTES) ?>" class="edit_link">編集</a>
                </button>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>

    <?php require_once('page_display.php'); ?>

</main>
</body>
</html>