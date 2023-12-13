
<?php 
require_once('function.php');

if ($_POST['csv_file_submit']) {
    if ($_POST['csv_file']) {

        $fileName = $_FILES['csv_file']['name'];
        $fileTmpName = $_FILES['csv_file']['tmp_name'];

        // ファイルパス
        $filePath = './csv/' . $fileName;
        
        // CSVファイルをcsvディレクトリに保存する
        move_uploaded_file($fileTmpName, $filePath);
        
        // csvディレクトリに保存したCSVファイルを読み込み、配列に置き換る。
        $data = array_map('str_getcsv', file($filePath));
        
        // データを挿入する
        foreach ($data as $key => $row) {
        
            $emp_id = $row[0];
            $name = $row[1];
            $kana = $row[2];
            $branch_name = $row[3];
            $sex = $row[4];
            $birthdate = $row[5];
            $mail = $row[6];
            $comm_time = $row[7];
            $blood_type = $row[8];
            $married = $row[9];

            // 支店名を支店IDに変換
            require_once('branch_get.php');

            foreach ($branch_row as $branch_row_get) {
                if ($branch_row_get['branch_name'] ===  $branch_name) {
                    $branch_id = $branch_row_get['id'];
                }
            }

            // 性別を数値型に変換
            if ($sex === "男") {
                $sex = 1;
            } elseif ($sex === "女") {
                $sex = 2;
            } elseif ($sex === "")  {
                $sex = 3;
            } else {
                $sex = 4;
            }

            // 血液型を数値型に変換
            if ($blood_type === "A型") {
                $blood_type = 1;
            } elseif ($blood_type === "B型") {
                $blood_type = 2;
            } elseif ($blood_type === "O型") {
                $blood_type = 3;
            } elseif ($blood_type === "AB型") {
                $blood_type = 4;
            } elseif ($blood_type === "") {
                $blood_type = 5;
            } else {
                $blood_type = 6;
            }

            $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $password = substr(str_shuffle(str_repeat($str, 10)), 0, 8);
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

            // 社員マスタのデータを取得
            $base_sql = "SELECT e.*, b.branch_name FROM employees AS e INNER JOIN branches AS b ON e.branch_id = b.id WHERE delete_flg IS NULL";
            $employees = $pdo->query($base_sql);

            foreach ($employees as $emp_get) {
                $get_emp_id = $emp_get['id'];
                $get_branch_name = $emp_get['branch_name'];
            }

            // 日付の形式チェック
            if ($birthdate !== "") {
                //19xx,20xx年が有効、ここは月と日の桁数だけを制御し、存在チェックは次のcheckdate関数で行う 
                if(!preg_match('/^(19|20)[0-9]{2}\/\d{2}\/\d{2}$/', $birthdate)){
                    $birthdate_check = 0;
                }
            
                list($y, $m, $d) = explode('/', $birthdate);
            
                if(!checkdate($m, $d, $y)){
                    $birthdate_check = 0;
                }
                $birthdate_check = 1;
            }

            // メールアドレスチェック
            $mail_result = preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail);

            require_once('mail_get.php');

            foreach ($mail_match_row as $mail_match) {
                if ($mail_match['email'] == $mail) {
                    $mail_match_result = 1;
                }
            }

            // 通勤時間チェック
            if (is_int($comm_time) && $comm_time >= 0) {
                $comm_time_check = 1;
            } else {
                $comm_time_check = 2;
            }
            
            // 既婚チェック
            if ($married === "既婚") {
                $married = 1;
            } elseif ($married === "未婚") {
                $married = null;
            } else {
                $married = 2;
            }

            // idの入力判定
            if ($emp_id === "") {
                if ($name !== "" && $kana !== "" && $get_branch_name !== $branch_name && $sex !== 4 && 
                    $birthdate_check === 1 && $mail_result === 1 && $mail_match_result === 1 && $comm_time_check === 1 && 
                    $blood_type !== 6 && $married !== 2) {

                    $csv_stmt = $pdo->prepare("
                    INSERT INTO employees 
                    (name, kana, sex, birthdate, email, password, comm_time, blood_type, married, branch_id, quali) 
                    VALUES 
                    (:name, :kana, :sex, :birthdate, :email, :password, :comm_time, :blood_type, :married, :branch_id, :quali)");
                    $csv_stmt->bindValue(':name', $name,PDO::PARAM_STR);
                    $csv_stmt->bindValue(':kana', $kana,PDO::PARAM_STR);
                    $csv_stmt->bindValue(':sex', $sex,PDO::PARAM_INT);
                    $csv_stmt->bindValue(':birthdate', $birthdate,PDO::PARAM_STR);
                    $csv_stmt->bindValue(':email', $mail,PDO::PARAM_STR);
                    $csv_stmt->bindValue(':password', $hashed_pass,PDO::PARAM_STR);
                    $csv_stmt->bindValue(':comm_time', $comm_time,PDO::PARAM_STR);
                    $csv_stmt->bindValue(':blood_type', $blood_type,PDO::PARAM_INT);
                    $csv_stmt->bindValue(':married', $married,PDO::PARAM_INT);
                    $csv_stmt->bindValue(':branch_id', $branch_id,PDO::PARAM_INT);
                    $csv_stmt->bindValue(':quali', $quali,PDO::PARAM_STR);
                    $csv_stmt->execute();
                
                    echo "ファイルアップロード完了しました";        
                } else {
                    echo "正しい形式でご入力ください";
                }
            } else {
                if ($get_emp_id !== $emp_id && $name !== "" && $kana !== "" && $get_branch_name !== $branch_name && $sex !== 4 && 
                $birthdate_check === 1 && $mail_result === 1 && $mail_match_result === 1 && $comm_time_check === 1 && 
                $blood_type !== 6 && $married !== 2) {

                $csv_sql = "UPDATE employees 
                SET name = :name, kana = :kana, sex = :sex, birthdate = :birthdate, 
                    email = :email, comm_time = :comm_time, blood_type = :blood_type, married = :married, branch_id = :branch_id, quali = :quali
                WHERE id = :id";

                $csv_stmt = $pdo->prepare($csv_sql);
                $csv_stmt->bindValue(':id', $emp_id,PDO::PARAM_INT);
                $csv_stmt->bindValue(':name', $name,PDO::PARAM_STR);
                $csv_stmt->bindValue(':kana', $kana,PDO::PARAM_STR);
                $csv_stmt->bindValue(':sex', $sex,PDO::PARAM_INT);
                $csv_stmt->bindValue(':birthdate', $birthdate,PDO::PARAM_STR);
                $csv_stmt->bindValue(':email', $mail,PDO::PARAM_STR);
                $csv_stmt->bindValue(':comm_time', $comm_time,PDO::PARAM_STR);
                $csv_stmt->bindValue(':blood_type', $blood_type,PDO::PARAM_INT);
                $csv_stmt->bindValue(':married', $married,PDO::PARAM_INT);
                $csv_stmt->bindValue(':branch_id', $branch_id,PDO::PARAM_INT);
                $csv_stmt->bindValue(':quali', $quali,PDO::PARAM_STR);
                $csv_stmt->execute();
            
                echo "ファイルアップロード完了しました";    
                } else {
                    echo "正しい形式でご入力ください";
                }
            }
        }
    } else {
        echo "ファイルを選択してください";
    }
} 

?>

<?php require_once('header.html'); ?>

<?php require_once('menu.php');?>

<header>
    <h1>CSVインポート</h1>
</header>

<main>

    <div class="csv_import">
        <form action="" method="post">
            <input type="file" name="csv_file">
            <input type="submit" name="csv_file_submit" value="インポート実行" class="csv_import_submit">
        </form>
    </div>
    <p class="import_text">※形式は社員ID(新規登録の場合は空欄)、名前、ふりがな、支店名、性別、生年月日、メールアドレス、通勤時間、血液型、既婚(未婚の場合は空欄)</br>
    以上の順でご記載ください。</p>

</main>
</body>
</html>