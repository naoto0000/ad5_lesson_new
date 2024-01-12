<?php
$max_page = ceil($count['cnt'] / 5);

// ページの数字ボタンを最大５個のみ表示
if ($page == 1 || $page == $max_page) {
    $range = 4;
} elseif ($page == 2 || $page == $max_page - 1) {
    $range = 3;
} else {
    $range = 2;
}

// 件数表示
$from_record = ($page - 1) * 5 + 1;

if ($page == $max_page && $count['cnt'] % 5 !== 0) {
    $to_record = ($page - 1) * 5 + $count['cnt'] % 5;
} else {
    $to_record = $page * 5;
}

if ($page > 1) {
    $start = ($page * 5) - 5;
} else {
    $start = 0;
}

$search_count = $count['cnt'];
?>