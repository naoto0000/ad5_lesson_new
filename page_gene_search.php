<?php 
$max_page = ceil($search_count / 5);

if ($page == 1 || $page == $max_page) {
    $range = 4;
} elseif ($page == 2 || $page == $max_page - 1) {
    $range = 3;
} else {
    $range = 2;
}

$from_record = ($page - 1) * 5 + 1;

if ($page == $max_page && $search_count % 5 !== 0) {
    $to_record = ($page - 1) * 5 + $search_count % 5;
} else {
    $to_record = $page * 5;
}
?>