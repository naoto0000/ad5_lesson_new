<?php foreach ($employees as $employee) : ?>
<tr class="table_contents">
    <td><?php e($employee->name); ?></td>
    <td><?php e($employee->kana); ?></td>
    <td><?php e($employee->branch_name); ?></td>
    <td class="table_responsive"><?php e($employee->sex_label); ?></td>
    <td class="table_responsive"><?php e($employee->age); ?></td>
    <td class="table_responsive"><?php e($employee->birthdate); ?></td>
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
