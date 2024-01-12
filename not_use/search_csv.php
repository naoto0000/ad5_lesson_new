
<?php 
// csv出力処理
if (isset($_GET['csv_submit'])) {
    // ファイル名を指定してHTTPヘッダを設定
    header('Content-Type: text/csv; charset=Shift_JIS');
    header('Content-Disposition: attachment; filename="emp.csv"');
        
    if ($name == "" && $search_sex == "" && $search_branch == "") {

    $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali 
    FROM employees AS e 
    INNER JOIN branches AS b 
    ON e.branch_id = b.id WHERE e.delete_flg IS NULL";

    $csv_emp_get = $pdo->query($csv_sql)->fetchAll(PDO::FETCH_ASSOC);

    require_once('csv_download.php');

    } else {
            // 氏名のみ入力時
        if ($name !== "" && $search_sex === "" && $search_branch === "") {
            $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali
            FROM employees AS e 
            INNER JOIN branches AS b 
            ON e.branch_id = b.id WHERE e.delete_flg IS NULL AND (e.name LIKE :word OR e.kana LIKE :word2)";

            $csv_emp_row = $pdo->prepare($csv_sql);
            $csv_emp_row->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
            $csv_emp_row->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
            $csv_emp_row->execute();
            $csv_emp_get = $csv_emp_row->fetchAll(PDO::FETCH_ASSOC);
        
            require_once('csv_download.php');
            
        }
        // 性別検索時
        if ($search_sex !== "" && $search_branch === "") {
            // 氏名も入力されている場合
            if ($name) {
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali 
                FROM employees AS e 
                INNER JOIN branches AS b 
                ON e.branch_id = b.id WHERE e.delete_flg IS NULL AND (e.name LIKE :word OR e.kana LIKE :word2) AND sex = :sex";
    
                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $csv_emp_row->execute();
                $csv_emp_get = $csv_emp_row->fetchAll(PDO::FETCH_ASSOC);
            
                require_once('csv_download.php');

                // 性別のみ入力時
            } else {
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali 
                FROM employees AS e 
                INNER JOIN branches AS b 
                ON e.branch_id = b.id WHERE e.delete_flg IS NULL AND sex = :sex";

                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $csv_emp_row->execute();
                $csv_emp_get = $csv_emp_row->fetchAll(PDO::FETCH_ASSOC);

                require_once('csv_download.php');

            }
        }
        
        if ($search_branch) {
            if ($name !== "" && $search_sex === "") {
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali 
                FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE (e.name LIKE :word OR e.kana LIKE :word2) AND e.branch_id = :branch_id AND e.delete_flg IS NULL";
        
                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $csv_emp_row->execute();
                $csv_emp_get = $csv_emp_row->fetchAll(PDO::FETCH_ASSOC);

                require_once('csv_download.php');
        
            } elseif ($search_sex !== "" && $name === "") {        
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali 
                FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE sex = :sex AND branch_id = :branch_id AND delete_flg IS NULL";
                
                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':sex',$search_sex,PDO::PARAM_INT);
                $csv_emp_row->bindValue(':branch_id',$search_branch,PDO::PARAM_INT);
                $csv_emp_row->execute();
                $csv_emp_get = $csv_emp_row->fetchAll(PDO::FETCH_ASSOC);

                require_once('csv_download.php');
        
            } elseif ($name !== "" && $search_sex !== "") {
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali 
                FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE ((name LIKE :word OR kana LIKE :word2) AND sex = :sex) AND branch_id = :branch_id AND delete_flg IS NULL";
        
                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $csv_emp_row->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $csv_emp_row->execute();
                $csv_emp_get = $csv_emp_row->fetchAll(PDO::FETCH_ASSOC);

                require_once('csv_download.php');
        
            } else {
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married, e.quali 
                FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE e.branch_id = :branch_id AND e.delete_flg IS NULL";
        
                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':branch_id',$search_branch,PDO::PARAM_INT);
                $csv_emp_row->execute();
                $csv_emp_get = $csv_emp_row->fetchAll(PDO::FETCH_ASSOC);

                require_once('csv_download.php');

            }
        }
    }
}
?>