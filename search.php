
<?php

require_once('function.php');

require_once('not_login.php');

// 入力値を取得 文字前後の空白除去&エスケープ処理
$name = trim(htmlspecialchars($_GET['search_name'],ENT_QUOTES));
// 文字列の中の「　」(全角空白)を「」(何もなし)に変換
$name = str_replace("　","",$name);

$search_sex = htmlspecialchars($_GET["search_sex"],ENT_QUOTES);

$search_branch = htmlspecialchars($_GET["search_branch"],ENT_QUOTES);

// csv出力処理
if (isset($_GET['csv_submit'])) {
    // ファイル名を指定してHTTPヘッダを設定
    header('Content-Type: text/csv; charset=Shift_JIS');
    header('Content-Disposition: attachment; filename="emp.csv"');
        
    if ($name == "" && $search_sex == "" && $search_branch == "") {

    $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married 
    FROM employees AS e 
    INNER JOIN branches AS b 
    ON e.branch_id = b.id WHERE e.delete_flg IS NULL";

    $csv_emp_get = $pdo->query($csv_sql)->fetchAll(PDO::FETCH_ASSOC);

    require_once('csv_download.php');

    } else {
            // 氏名のみ入力時
        if ($name) {
            $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married 
            FROM employees AS e 
            INNER JOIN branches AS b 
            ON e.branch_id = b.id WHERE e.delete_flg IS NULL AND (e.name LIKE :word OR e.kana LIKE :word2)";

            $csv_emp_row = $pdo->prepare($csv_sql);
            $csv_emp_row->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
            $csv_emp_row->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
            $csv_emp_row->execute();
            $csv_emp_get = $pdo->fetchAll(PDO::FETCH_ASSOC);
        
            require_once('csv_download.php');
            
        }
        // 性別検索時
        if ($search_sex) {
            // 氏名も入力されている場合
            if ($name) {
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married 
                FROM employees AS e 
                INNER JOIN branches AS b 
                ON e.branch_id = b.id WHERE e.delete_flg IS NULL AND (e.name LIKE :word OR e.kana LIKE :word2) AND sex = :sex";
    
                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
                $csv_emp_row->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $csv_emp_row->execute();
                $csv_emp_get = $pdo->fetchAll(PDO::FETCH_ASSOC);
            
                require_once('csv_download.php');

                // 性別のみ入力時
            } else {
                $csv_sql = "SELECT e.id, e.name, e.kana, b.branch_name, e.sex, e.birthdate, e.email, e.comm_time, e.blood_type, e.married 
                FROM employees AS e 
                INNER JOIN branches AS b 
                ON e.branch_id = b.id WHERE e.delete_flg IS NULL AND sex = :sex";

                $csv_emp_row = $pdo->prepare($csv_sql);
                $csv_emp_row->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $csv_emp_row->execute();
                $csv_emp_get = $pdo->fetchAll(PDO::FETCH_ASSOC);

                require_once('csv_download.php');

            }
        }
        
        if ($search_branch) {
            if ($name) {
                $base_sql = "SELECT * FROM `employees` WHERE (name LIKE :word OR kana LIKE :word2) AND branch_id = :branch_id AND delete_flg IS NULL";
                $employees = $pdo->prepare($base_sql);
                $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);    
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
        
                $search_count = $employees->rowCount();
        
                $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE (e.name LIKE :word OR e.kana LIKE :word2) AND e.branch_id = :branch_id AND e.delete_flg IS NULL LIMIT {$start},5";
        
                $employees = $pdo->prepare($limit_sql);
                $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
        
            } elseif ($search_sex) {
                $base_sql = "SELECT * FROM `employees` WHERE sex = :sex AND branch_id = :branch_id AND delete_flg IS NULL";
                $employees = $pdo->prepare($base_sql);
                $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
        
                $search_count = $employees->rowCount();
        
                $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE sex = :sex AND branch_id = :branch_id AND delete_flg IS NULL LIMIT {$start},5";
                
                $employees = $pdo->prepare($limit_sql);
                $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
        
            } elseif ($name && $search_sex) {
                $base_sql = "SELECT * FROM `employees` 
                            WHERE ((name LIKE :word OR kana LIKE :word2) AND sex = :sex) AND branch_id = :branch_id AND delete_flg IS NULL";
                $employees = $pdo->prepare($base_sql);
                $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
                $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
        
                $search_count = $employees->rowCount();
                
                $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE ((name LIKE :word OR kana LIKE :word2) AND sex = :sex) AND branch_id = :branch_id AND delete_flg IS NULL LIMIT {$start},5";
        
                $employees = $pdo->prepare($limit_sql);
                $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
                $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
                $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
        
            } else {
                $base_sql = "SELECT * FROM `employees` WHERE branch_id = :branch_id AND delete_flg IS NULL";
                $employees = $pdo->prepare($base_sql);
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
        
                $search_count = $employees->rowCount();
        
                $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
                INNER JOIN branches AS b ON e.branch_id = b.id 
                WHERE e.branch_id = :branch_id AND e.delete_flg IS NULL LIMIT {$start},5";
        
                $employees = $pdo->prepare($limit_sql);
                $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
                $employees->execute();
            }
        }
    }
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = "";

if ($page > 1) {
    $start = ($page * 5) - 5;
} else {
    $start = 0;
}

try{
$base_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL";
$where_sql = "";
$limit_sql = "";

if ($name == "" && $search_sex == "" && $search_branch == "") {

    // ここでは検索結果と件数を取得
    $employees = $pdo->prepare($base_sql);
    $employees->execute();
    $search_count = $employees->rowCount();

    // ここでは上記で取得したデータを５件のみの表示にする
    $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e INNER JOIN branches AS b ON e.branch_id = b.id WHERE e.delete_flg IS NULL LIMIT {$start},5";
    $employees = $pdo->query($limit_sql);

} else {
    // 氏名のみ入力時
if ($name) {
    $where_sql = " AND (name LIKE :word OR kana LIKE :word2)";
    $base_sql .= $where_sql;
    $employees = $pdo->prepare($base_sql);
    $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
    $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
    $employees->execute();

    $search_count = $employees->rowCount();

    $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
    INNER JOIN branches AS b ON e.branch_id = b.id 
    WHERE (name LIKE :word OR kana LIKE :word2) AND delete_flg IS NULL LIMIT {$start},5";

    $employees = $pdo->prepare($limit_sql);
    $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
    $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
    $employees->execute();

}
// 性別検索時
if ($search_sex) {
    // 氏名も入力されている場合
    if ($name) {
        $base_sql .= " AND sex = :sex";
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);    
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();

        $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
        INNER JOIN branches AS b ON e.branch_id = b.id 
        WHERE ((name LIKE :word OR kana LIKE :word2) AND sex = :sex) AND delete_flg IS NULL LIMIT {$start},5";
        
        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();
    
        // 性別のみ入力時
    } else {
        $where_sql .= " AND sex = :sex";
        $base_sql .= $where_sql;
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();

        $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
        INNER JOIN branches AS b ON e.branch_id = b.id 
        WHERE sex = :sex AND delete_flg IS NULL LIMIT {$start},5";

        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();
    }
}

if ($search_branch) {
    if ($name) {
        $base_sql = "SELECT * FROM `employees` WHERE (name LIKE :word OR kana LIKE :word2) AND branch_id = :branch_id AND delete_flg IS NULL";
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);    
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();

        $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
        INNER JOIN branches AS b ON e.branch_id = b.id 
        WHERE (e.name LIKE :word OR e.kana LIKE :word2) AND e.branch_id = :branch_id AND e.delete_flg IS NULL LIMIT {$start},5";

        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();

    } elseif ($search_sex) {
        $base_sql = "SELECT * FROM `employees` WHERE sex = :sex AND branch_id = :branch_id AND delete_flg IS NULL";
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();

        $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
        INNER JOIN branches AS b ON e.branch_id = b.id 
        WHERE sex = :sex AND branch_id = :branch_id AND delete_flg IS NULL LIMIT {$start},5";
        
        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();

    } elseif ($name && $search_sex) {
        $base_sql = "SELECT * FROM `employees` 
                    WHERE ((name LIKE :word OR kana LIKE :word2) AND sex = :sex) AND branch_id = :branch_id AND delete_flg IS NULL";
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();
        
        $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
        INNER JOIN branches AS b ON e.branch_id = b.id 
        WHERE ((name LIKE :word OR kana LIKE :word2) AND sex = :sex) AND branch_id = :branch_id AND delete_flg IS NULL LIMIT {$start},5";

        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();

    } else {
        $base_sql = "SELECT * FROM `employees` WHERE branch_id = :branch_id AND delete_flg IS NULL";
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();

        $limit_sql = "SELECT e.*, b.branch_name FROM employees AS e 
        INNER JOIN branches AS b ON e.branch_id = b.id 
        WHERE e.branch_id = :branch_id AND e.delete_flg IS NULL LIMIT {$start},5";

        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':branch_id',$search_branch,PDO::PARAM_STR);
        $employees->execute();
    }
}

}

require_once('page_gene_search.php');

}catch(PDOException $e) {
    echo $e->getMessage();
}
$sexCotegory = [
    ['value' => '', 'text' => '全て'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女'],
    ['value' => '3', 'text' => '不明'],
];

require_once('branch_get.php');

$pdo = null;

?>
<?php require_once('header.html'); ?>

<?php require_once('menu.php');?>

<header>
    <h1>社員一覧</h1>
</header>
<main>
    <div class="search">
        <form action="" method="get">
            <label for="">氏名</label>
                <input type="text" name="search_name" class="input_name" value="<?php  echo $name ?>">
            <label for="">性別</label>
                <select name="search_sex" class="select_sex" value="">
                    <?php 
                        foreach ($sexCotegory as $row){
                            if ($search_sex == $row['value']) {
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                    ?>
                </select>
            <label for="">支店</label>
                <select name="search_branch" class="select_branch" >

                        <option value="">全て</option>

                        <?php 
                            foreach ($branch_row as $branch_name_search){
                                if ($search_branch == $branch_name_search['id']) {
                                    echo '<option value="'. $branch_name_search['id'] .'"selected>' . $branch_name_search['branch_name'] . '</option>';
                                } else {
                                    echo '<option value="'. $branch_name_search['id'] .'">' . $branch_name_search['branch_name'] . '</option>';
                                }
                            }
                        ?>
                </select>
            <input type="submit" name="search_submit" value="検索" class="search_submit">

            <input type="submit" name="csv_submit" value="CSVダウンロード" class="csv_submit">
            
            <a href="csv_import.php"><input type="button" name="csv_import" value="CSVインポート" class="csv_submit"></a>

        </form>
    </div>

    <?php if ($search_count == 0): ?>
        <p class="search_none">該当する社員がいません</p>
    <?php else: ?>
        <table>
            <tr class="table_title">
                <th>氏名</th>
                <th>かな</th>
                <th>支店</th>
                <th>性別</th>
                <th>年齢</th>
                <th>生年月日</th>
                <th> </th>
            </tr>

            <?php require_once('escape.php'); ?>
            
        </table>
    <?php endif; ?>

<!-- 検索結果が５件未満の場合ページネーションを表示させない -->
<?php if ($search_count > 6): ?>

    <?php require_once('page_display_search.php'); ?>

<?php endif; ?>

</main>
</body>
</html>