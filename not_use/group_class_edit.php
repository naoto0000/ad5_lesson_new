
<?php 

require_once('function.php');

require_once('not_login.php');

$id = $_GET['id'];

var_dump($_POST['group_input_name']);

$prof_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL";
$prof = $pdo->query($prof_sql);

foreach ($prof as $prof_row) {
    if ($id == $prof_row['id']) {
        $prof_birthdate_get = $prof_row['birthdate'];
        $prof_name = $prof_row['name'];       
    }
}
    // 現在日付
    $now = date('Ymd');

    // 誕生日
    $birthday_age = str_replace("-", "", $prof_birthdate_get);

    $prof_birthdate = str_replace("-", ".", $prof_birthdate_get);
    
    // 年齢
    $prof_age = floor(($now - $birthday_age) / 10000);
    
    $group_term_sql = "SELECT * FROM `group_term`";
    $group_term_row = $pdo->query($group_term_sql);

    foreach ($group_term_row as $group_term) {
        if ($group_term['emp_id'] == $id) {
            $group_id = $group_term['id'];
            $group_name = $group_term['group_name'];
            $group_from = $group_term['group_from'];
            $group_to = $group_term['group_to'];            
        }
    }

    $base_sql = "SELECT * FROM `group_table`";
    $group_class_row = $pdo->query($base_sql);

    if ($_POST['group_class_submit']) {
        if (isset($_POST['group_input_name_new']) && isset($_POST['group_from_new'])) {
            $sql = 'INSERT INTO group_term
            (emp_id, group_name, group_from, group_to) 
            VALUES (:emp_id, :group_name, :group_from, :group_to)';
                
            try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':emp_id',$id,PDO::PARAM_INT);
            $stmt->bindValue(':group_name',$_POST['group_input_name_new'],PDO::PARAM_STR);
            $stmt->bindValue(':group_from',$_POST['group_from_new'],PDO::PARAM_STR);
            $stmt->bindValue(':group_to',$_POST['group_to_new'],PDO::PARAM_STR);
    
            $stmt->execute();
    
            echo "保存しました";
    
            }catch(PDOException $e){
            echo $e->getMessage();
            }
        } elseif ($group_name !== $_POST['group_input_name'] && $group_from !== $_POST['group_from'] && $group_to !== $_POST['group_to']) {
            $edit_sql = "UPDATE group_term 
            SET emp_id = :emp_id, group_name = :group_name, group_from = :group_from, group_to = :group_to
            WHERE id = :id";

            try{
                $edit_stmt = $pdo->prepare($edit_sql);
                $edit_stmt->bindValue(':id',$group_id,PDO::PARAM_INT);
                $edit_stmt->bindValue(':emp_id',$id,PDO::PARAM_INT);
                $edit_stmt->bindValue(':group_name',$_POST['group_input_name'],PDO::PARAM_STR);
                $edit_stmt->bindValue(':group_from',$_POST['group_from'],PDO::PARAM_STR);
                $edit_stmt->bindValue(':group_to',$_POST['group_to'],PDO::PARAM_STR);

                $edit_stmt->execute();

                echo "保存しました";

            }catch(PDOException $e){
                echo $e->getMessage();
            }

        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AD5 lesson</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
</head>
<body>

<?php require_once('menu.php');?>

<header>
    <h1>部門所属編集</h1>
</header>

<main>
    <form action="" method="post">
        <table class="prof_table">
            <tr>
                <td class="prof_title prof_list">氏名</td>
                <td class="prof_list"><?php echo $prof_name; ?></td>
                <td class="prof_title prof_list">生年月日</td>
                <td class="prof_list"><?php echo $prof_birthdate; ?> (<?php echo $prof_age; ?> 歳)</td>
            </tr>
        </table>

        <div class="group_class_edit">
            <table class="group_class_table">
                <tr class="table_title">
                    <th class="table_group_name">部門</th>
                    <th class="table_group_term" colspan="3">所属期間</th>
                </tr>

                <tr>
                    <td>
                        <select name="group_input_name" class="group_input_name">

                            <option value="">部門を選択</option>

                            <?php foreach ($group_class_row as $group_row) : ?>
                                <?php if ($group_row['group_name'] == $group_name) : ?>
                                    <option value="<?php echo $group_row['group_name']; ?>" selected><?php echo $group_row['group_name']; ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $group_row['group_name']; ?>"><?php echo $group_row['group_name']; ?></option>
                                <?php endif ; ?>
                            <?php endforeach; ?>

                        </select>
                    </td>

                    <td class="group_td"><input type="date" name="group_from" value="<?php echo $group_from ?>" class="group_date"></td>
                    <td class="group_td">〜</td>
                    <td class="group_td"><input type="date" name="group_to" value="<?php echo $group_to ?>" class="group_date"></td>

                </tr>

                <tr>
                    <td>
                        <select name="group_input_name_new" id="" class="group_input_name">

                            <option value="" selected>部門を選択</option>

                            <?php foreach ($group_class_row as $group_row) : ?>
                                <option value="<?php echo $group_row['group_name']; ?>"><?php echo $group_row['group_name']; ?></option>
                            <?php endforeach; ?>

                        </select>
                    </td>

                    <td class="group_td"><input type="date" name="group_from_new" class="group_date"></td>
                    <td class="group_td">〜</td>
                    <td class="group_td"><input type="date" name="group_to_new" class="group_date"></td>
                </tr>
            </table>
        </div>

        <input type="submit" name="group_class_submit" value="保存" class="group_class_submit">

    </form>

</main>
</body>
</html>