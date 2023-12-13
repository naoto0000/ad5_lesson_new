
<?php 

require_once('function.php');

require_once('not_login.php');

$base_sql = "SELECT group_code FROM `group_table`";
$group_row = $pdo->query($base_sql);

// 取得できたデータを変数に入れておく
$group_match_row = $group_row->fetchAll(PDO::FETCH_ASSOC);

$group_match_result = "";

foreach ($group_match_row as $group_match) {
    if ($group_match['group_code'] == $_POST['regi_group_code']) {
        $group_match_result = 1;
    }
}

if ($_POST['regi_group_submit']) {
    if ($_POST['regi_group_code'] !== "" && $group_match_result == "" && $_POST['regi_group_name'] !== "" && $_POST['regi_group_parent'] !== $_POST['regi_group_name']) {
        $group_sql = "INSERT INTO `group_table` (`group_code`, `group_name`, `parent_group`, `group_order`) 
        VALUES (:group_code, :group_name, :parent_group, :group_order)";
            
        try{
        $group_stmt = $pdo->prepare($group_sql);
        $group_stmt->bindValue(':group_code',$_POST['regi_group_code'],PDO::PARAM_STR);
        $group_stmt->bindValue(':group_name',$_POST['regi_group_name'],PDO::PARAM_STR);
        $group_stmt->bindValue(':parent_group',$_POST['regi_group_parent'],PDO::PARAM_STR);
        $group_stmt->bindValue(':group_order',$_POST['regi_group_order'],PDO::PARAM_INT);
        
        $group_stmt->execute();
        
        echo "登録しました";
        
        }catch(PDOException $e){
        echo $e->getMessage();
        }        
    } else {
        echo"ERROR：不正な登録処理です";
    }
}

require_once('get_parent_group.php');

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
    <h1>部門登録</h1>
</header>

<main>

<div class="registration">
    <form action="" method="post">

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">部門コード</label>
                <p class="indi_mes">必須</p>
            </div>

            <?php if (isset($_POST['regi_group_submit']) && $_POST['regi_group_code'] !== "" && $group_match_result == "" && 
                    $_POST['regi_group_name'] !== "" && $_POST['regi_group_parent'] !== $_POST['regi_group_name']) : ?>

                <input type="text" name="regi_group_code" class="regi_name">

            <?php else : ?>

                <input type="text" name="regi_group_code" class="regi_name" value="<?php echo htmlspecialchars($_POST['regi_group_code'], ENT_QUOTES); ?>">

            <?php endif ; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_group_submit'])) {
                    if (empty($_POST['regi_group_code'])) {
                        echo "入力必須項目です";
                    }  elseif ($group_match_result == 1) {
                        echo "こちらの部門コードは既に使用されています";
                    }
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">部門名</label>
                <p class="indi_mes">必須</p>
            </div>

            <?php if (isset($_POST['regi_group_submit']) && $_POST['regi_group_code'] !== "" && $group_match_result == "" && 
                    $_POST['regi_group_name'] !== "" && $_POST['regi_group_parent'] !== $_POST['regi_group_name']) : ?>

                <input type="text" name="regi_group_name" class="regi_name">

            <?php else : ?>

                <input type="text" name="regi_group_name" class="regi_name" value="<?php echo htmlspecialchars($_POST['regi_group_name'], ENT_QUOTES); ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_group_submit'])) {
                    if (empty($_POST['regi_group_name'])) {
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <label class="regi_label">親部門</label>
            <select name="regi_group_parent" class="regi_branch" >

            <?php if (isset($_POST['regi_group_submit']) && $_POST['regi_group_code'] !== "" && $group_match_result == "" && 
                    $_POST['regi_group_name'] !== "" && $_POST['regi_group_parent'] !== $_POST['regi_group_name']) : ?>

                <option value="" selected>親部門なし</option>

                <?php foreach ($group_parent_row as $group_row_regi) : ?>
                    <option value="<?php echo $group_row_regi['group_name'] ?>"><?php echo $group_row_regi['group_name'] ?></option>
                <?php endforeach; ?>

            <?php else : ?>

                <option value="">親部門なし</option>

                <?php foreach ($group_parent_row as $group_row_regi) : ?>
                    <?php if ($group_row_regi['group_name'] ===  $_POST['regi_group_parent']) : ?>
                        <option value="<?php echo $group_row_regi['group_name'] ?>" selected><?php echo $group_row_regi['group_name'] ?></option>
                    <?php else : ?>
                        <option value="<?php echo $group_row_regi['group_name'] ?>"><?php echo $group_row_regi['group_name'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php endif; ?>

            </select>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_group_submit'])) {
                    if ($_POST['regi_group_parent'] == $_POST['regi_group_name']) {
                        echo "自身を親部門に選ぶことはできません";
                    }  
                }
                ?>
            </span>

        </div>

        <div class="regi_class">
            <label class="regi_label">並び順</label>

            <?php if (isset($_POST['regi_group_submit']) && $_POST['regi_group_code'] !== "" && $group_match_result == "" && 
                    $_POST['regi_group_name'] !== "" && $_POST['regi_group_parent'] !== $_POST['regi_group_name']) : ?>

                <input type="text" name="regi_group_order" class="regi_group_order">

            <?php else : ?>

                <input type="text" name="regi_group_order" class="regi_group_order" value="<?php echo htmlspecialchars($_POST['regi_group_order'], ENT_QUOTES); ?>">

            <?php endif; ?>
            
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="regi_group_submit" value="登録" class="regi_submit">
    </form>
</div>
</main>
</body>
</html>