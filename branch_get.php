
<?php 
// 支店マスタの情報取得
try{
    $branch_sql = "SELECT * FROM branches ORDER BY order_list";
    $branch_stmt = $pdo->prepare($branch_sql);
    $branch_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}

// 取得できたデータを変数に入れておく
$branch_row = $branch_stmt->fetchAll(PDO::FETCH_ASSOC);
?>