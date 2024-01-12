<?php 
require_once('function.php');
require_once('not_login.php');
require(__DIR__ . '/entities/employee_class.php');

$id = $_GET['id'];

$sql = "SELECT e.*, b.branch_name FROM employees AS e INNER JOIN branches as b ON e.branch_id = b.id WHERE e.id = :id AND e.delete_flg is null";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $id,
]);
$employee = new Employee($stmt->fetch());

    include(__DIR__ . '/utilities/branch_sql.php');

    include(__DIR__ . '/utilities/quali_get.php');

    /**
     * 性別によって枠線変える
     *
     * @param string|null $sex
     * @return string
     */
    function generateBorder(?string $sex): string
    {
        if ($sex === null) {
            return "detail_img";
        }

        $int_sex = (int)$sex;

        if ($int_sex === 1) {
            return "detail_img_man";
        }

        if ($int_sex === 2) {
            return "detail_img_woman";
        }

        return "detail_img";
    }

    /**
     * 日付をフォーマット
     *
     * @param string|null $birthdate
     * @param string $format_type
     * @return string
     */
    function formatDate(?string $birthdate, string $format_type): string
    {
        return date($format_type, strtotime($birthdate));
    }

include(__DIR__ . '/pages/detail.view.php');