
<?php 
$quali_get_sql = "SELECT * FROM `quali`";
$quali_masta = $pdo->query($quali_get_sql)->fetchAll(PDO::FETCH_ASSOC);
?>