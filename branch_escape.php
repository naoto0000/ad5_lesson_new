<?php foreach ($branches as $branch) : ?>
    
<tr class="table_contents">
<td class="branch_td"><?php e($branch->branch_name); ?></td>
<td class="branch_td"><?php e($branch->tel_number); ?></td>
<td class="branch_td"><?php e($branch->full_address);?></td>
<td class="branch_edit_button"><button name="edit_button" class="edit_button">
    <a href="branch_edit.php?id=<?php e($branch->id) ?>" class="edit_link">編集</a>
</button></td>
</tr>

<?php endforeach; ?>
