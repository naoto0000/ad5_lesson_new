
<?php foreach ($employees as $employee) : ?>

<?php
 
    // 現在日付
    $now = date('Ymd');

    if ($employee['birthdate'] !== null) {
        // 誕生日
        $birthday = $employee['birthdate'] ;
        $birthday = str_replace("-", "", $birthday);

        // 年齢
        $age = floor(($now - $birthday) / 10000);

    } else {
        $age = "";
    }

    // 性別
    $sex = $employee['sex'];

    if ($sex === '1') {
        $sex = '男';
    } elseif ($sex === '2') {
        $sex = '女';
    } elseif ($sex === '3') {
        $sex = '不明';
    }
?>

<tr class="table_contents">
    <td><?php echo htmlspecialchars($employee['name'], ENT_QUOTES); ?></td>
    <td><?php echo htmlspecialchars($employee['kana'], ENT_QUOTES); ?></td>
    <td><?php echo htmlspecialchars($employee['branch_name'], ENT_QUOTES); ?></td>
    <td><?php echo htmlspecialchars($sex, ENT_QUOTES);?></td>
    <td><?php echo htmlspecialchars($age, ENT_QUOTES); ?></td>
    <td><?php echo htmlspecialchars($employee['birthdate'], ENT_QUOTES); ?></td>
    <td>
        <button name="edit_button" class="edit_button">
            <a href="edit.php?id=<?php echo htmlspecialchars($employee['id'], ENT_QUOTES) ?>" class="edit_link">編集</a>
        </button>

        <button name="detail_button" class="edit_button">
            <a href="detail.php?id=<?php echo htmlspecialchars($employee['id'], ENT_QUOTES) ?>" class="edit_link">詳細</a>
        </button>
    </td>
</tr>

<?php endforeach; ?>
