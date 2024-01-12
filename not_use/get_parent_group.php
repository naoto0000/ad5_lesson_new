<?php 
// 親部門の選択肢表示に使用
$base_parent_sql = "SELECT * FROM `group_table`";
$group_parent_stmt = $pdo->query($base_parent_sql);

// 取得できたデータを変数に入れておく
$group_parent_row = $group_parent_stmt->fetchAll(PDO::FETCH_ASSOC);
?>