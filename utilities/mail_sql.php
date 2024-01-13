<?php 
try {
    $pdo = new PDO('mysql:host=localhost;dbname=xs010968_lesson', "xs010968_fukuoka", "fukuoka7010");
} catch (PDOException $e) {
    echo $e->getMessage();
}

// 削除された人のメアドを使えるようにしている
try{
    $mail_sql = "SELECT email FROM employees WHERE delete_flg IS NULL";
    $mail_stmt = $pdo->prepare($mail_sql);
    $mail_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}
// 取得できたデータを変数に入れておく
$mail_match_row = $mail_stmt->fetchAll(PDO::FETCH_ASSOC);

$mail_match_result = "";
?>