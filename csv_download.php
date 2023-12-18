
<?php     
// ファイルを書き込み用に開きます。
$f = fopen('php://output', 'w');

// 正常にファイルを開くことができていれば、書き込みます。
if ($f) {
    // ヘッダー行を書き込む
    $header = array('社員ID', '氏名', 'かな', '支店名', '性別', '生年月日', 'メールアドレス', '通勤時間(分)', '血液型', '既婚','資格'); 

    $header = mb_convert_encoding($header, "SJIS", "UTF-8");

    fputcsv($f, $header);

    // データを順番に書き込みます。
    foreach ($csv_emp_get as $csv_emp) {
        if ($csv_emp['sex'] == 1) {
            $csv_emp['sex'] = "男";
        } elseif ($csv_emp['sex'] == 2) {
            $csv_emp['sex'] = "女";
        } else {
            $csv_emp['sex'] = "";
        }

        if ($csv_emp['blood_type'] == 1) {
            $csv_emp['blood_type'] = "A型";
        } elseif ($csv_emp['blood_type'] == 2) {
            $csv_emp['blood_type'] = "B型";
        } elseif ($csv_emp['blood_type'] == 3) {
            $csv_emp['blood_type'] = "AB型";
        } elseif ($csv_emp['blood_type'] == 4) {
            $csv_emp['blood_type'] = "O型";
        } else {
            $csv_emp['blood_type'] = "";
        }

        if ($csv_emp['married'] == 1) {
            $csv_emp['married'] = "既婚";
        } else {
            $csv_emp['married'] = "未婚";
        }

        $quali_name = [];  // ループ内で $quali_name を初期化
        

        // 資格の処理
        require_once('quali_get.php');

        foreach ($quali_masta as $quali_row){
            $quoted_id = preg_quote($quali_row['id'], '/');
            if (preg_match('/\b' . $quoted_id . '\b/', $csv_emp['quali'])){
                $quali_name[] = $quali_row['quali_name'];
            }
        }    
        $csv_emp['quali'] = implode(',',$quali_name);

        $csv_emp = mb_convert_encoding($csv_emp, "SJIS", "UTF-8");

        fputcsv($f, $csv_emp);
    }
}

// ファイルを閉じます。
fclose($f);
exit();


?>