<?php
require_once('function.php');
require_once('not_login.php');

// 入力値を取得 文字前後の空白除去&エスケープ処理
$name = trim(htmlspecialchars($_GET['search_name'],ENT_QUOTES));
// 文字列の中の「　」(全角空白)を「」(何もなし)に変換
$name = str_replace("　","",$name);
$search_sex = $_GET["search_sex"];
$search_branch = $_GET["search_branch"];

// 検索処理
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

require(__DIR__ . '/entities/employee_class.php');

// 条件を格納する配列
$conditions = [];

// 名前の検索条件
if ($name) {
    $conditions[] = "(e.name LIKE :word OR e.kana LIKE :word2)";
}
// 性別の検索条件
if ($search_sex) {
    $conditions[] = "e.sex = :sex";
}
// 支店の検索条件
if ($search_branch) {
    $conditions[] = "e.branch_id = :branch_id";
}
// 削除フラグがNULLの条件
$conditions[] = "e.delete_flg IS NULL";

// 条件を結合して基本クエリを構築
$base_sql = "SELECT e.*, b.branch_name 
FROM employees AS e 
INNER JOIN branches AS b 
ON e.branch_id = b.id 
WHERE " . implode(" AND ", $conditions);

// ここでは検索結果と件数を取得
$employees = $pdo->prepare($base_sql);

// 事前にバインドするパラメータを初期化
$params = [];

// 名前の検索条件
if ($name) {
    $params[':word'] = "%{$name}%";
    $params[':word2'] = "%{$name}%";
}
// 性別の検索条件
if ($search_sex) {
    $params[':sex'] = $search_sex;
}
// 支店の検索条件
if ($search_branch) {
    $params[':branch_id'] = $search_branch;
}

// SQL クエリを実行
if (empty($params)) {
    // すべての条件が空の場合
    $employees->execute();
} else {
    // 1つ以上の条件がある場合
    $employees->execute($params);
}

$search_count = $employees->rowCount();

// ここでは上記で取得したデータを５件のみの表示にする
$limit_sql = $base_sql . " LIMIT {$start},5";

$employees = $pdo->prepare($limit_sql);

// 事前にバインドするパラメータを初期化
$params = [];

// 名前の検索条件
if ($name) {
    $params[':word'] = "%{$name}%";
    $params[':word2'] = "%{$name}%";
}
// 性別の検索条件
if ($search_sex) {
    $params[':sex'] = $search_sex;
}
// 支店の検索条件
if ($search_branch) {
    $params[':branch_id'] = $search_branch;
}

// SQL クエリを実行
if (empty($params)) {
    // すべての条件が空の場合
    $employees->execute();
} else {
    // 1つ以上の条件がある場合
    $employees->execute($params);
}

$employees = $employees->fetchAll(PDO::FETCH_ASSOC);
$employees = array_map(function ($employee) {
    return new Employee($employee);
}, $employees);

include(__DIR__ . '/pagenation/page_gene_search.php');

$sexCotegory = [
    ['value' => '', 'text' => '全て'],
    ['value' => 1, 'text' => '男'],
    ['value' => 2, 'text' => '女'],
    ['value' => 3, 'text' => '不明'],
];

include(__DIR__ . '/utilities/branch_sql.php');
include(__DIR__ . '/pages/employee-search.view.php');