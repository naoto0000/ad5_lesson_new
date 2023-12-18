<?php
    // /**
    //  * 生年月日から年齢を算出
    //  *
    //  * @param string|null $birthday
    //  * @return float|null
    //  */
    // function ageFromBirthday(?string $birthday): ?float
    // {
    //     if ($birthday === null) {
    //         return null;
    //     }

    //     $birthday = str_replace("-", "", $birthday);
    //     $age = floor((date('Ymd') - $birthday) / 10000);
    //     if ($age === false) {
    //         return null;
    //     }

    //     return $age;
    // }
    
    // /**
    //  * 性別ラベル表示
    //  *
    //  * @param string $sex
    //  * @return string
    //  */
    // function sexLabel(string $sex): string
    // {
    //     $int_sex = (int)$sex;

    //     if ($int_sex === 1) {
    //         return '男';
    //     }

    //     if ($int_sex === 2) {
    //         return '女';
    //     }

    //     return '不明';
    // }
?>
<?php foreach ($employees as $employee) : ?>
<tr class="table_contents">
    <td><?php e($employee->name); ?></td>
    <td><?php e($employee->kana); ?></td>
    <td><?php e($employee->branch_name); ?></td>
    <td><?php e($employee->sex_label); ?></td>
    <td><?php e($employee->age); ?></td>
    <td><?php e($employee->birthdate); ?></td>
    <td>
        <button name="edit_button" class="edit_button">
            <a href="edit.php?id=<?php e($employee->id) ?>" class="edit_link">編集</a>
        </button>

        <button name="detail_button" class="edit_button">
            <a href="detail.php?id=<?php e($employee->id) ?>" class="edit_link">詳細</a>
        </button>
    </td>
</tr>
<?php endforeach; ?>
