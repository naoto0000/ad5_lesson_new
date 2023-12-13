
<?php 
$validate_mes = [];

function validate($post_data) {
    var_dump($post_data['edit_com']);
    $edit_com_int = (int)$_POST['edit_com'];
    if ($edit_com_int < 1) {
        $validate_mes['edit_com'] = '1以上の整数で入力ください';
    }
}
?>