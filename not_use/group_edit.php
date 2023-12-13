
<?php 

require_once('function.php');

require_once('not_login.php');

$id = $_GET['id'];

if (!$id) {
    // 一覧画面へ戻る
    header('Location: index.php');
}

var_dump($_POST['edit_group_parent']);

$base_sql = "SELECT group_code FROM `group_table`";
$group_row = $pdo->query($base_sql);

// 取得できたデータを変数に入れておく
$group_match_row = $group_row->fetchAll(PDO::FETCH_ASSOC);

$group_match_result = "";

foreach ($group_match_row as $group_match) {
    if ($group_match['group_code'] == $_POST['edit_group_code']) {
        $group_match_result = 1;
    }
}

// 編集対象のデータをidをもとにとってくる
try{
    $edit_sql = "SELECT * FROM group_table WHERE id = :id";
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(":id", $id,PDO::PARAM_INT);
    $edit_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}

// 取得できたデータを変数に入れておく
$edit_row = $edit_stmt->fetch(PDO::FETCH_ASSOC);

if ($edit_row === false) {
    $errar_mes = "URLが間違っています";
} else {
    $group_code_edit = $edit_row['group_code'];
    $group_name_edit = $edit_row['group_name'];
    $parent_group_edit = $edit_row['parent_group'];
    $group_order_edit = $edit_row['group_order'];
}

if ($_POST['edit_group_submit']) {
    if ($group_code_edit == $_POST['edit_group_code']) {
        if ($_POST['edit_group_code'] !== "" && $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) {

            $group_edit_sql = "UPDATE group_table 
            SET group_name = :group_name, parent_group = :parent_group, group_order = :group_order
            WHERE id = :id";
    
            try{
            $group_edit_stmt = $pdo->prepare($group_edit_sql);
            $group_edit_stmt->bindValue(':id',$id,PDO::PARAM_INT);
            $group_edit_stmt->bindValue(':group_name',$_POST['edit_group_name'],PDO::PARAM_STR);
            $group_edit_stmt->bindValue(':parent_group',$_POST['edit_group_parent'],PDO::PARAM_STR);
            $group_edit_stmt->bindValue(':group_order',$_POST['edit_group_order'],PDO::PARAM_STR);
    
            $group_edit_stmt->execute();
    
            echo "更新しました";
    
            }catch(PDOException $e){
            echo $e->getMessage();
            }
    
        } else {
            echo"ERROR：不正な登録処理です";
        }

    } elseif ($group_code_edit !== $_POST['edit_group_code']) {
        if ($_POST['edit_group_code'] !== "" && $group_match_result == "" && $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) {

            $group_edit_sql = "UPDATE group_table 
            SET group_code = :group_code, group_name = :group_name, parent_group = :parent_group, group_order = :group_order
            WHERE id = :id";
    
            try{
            $group_edit_stmt = $pdo->prepare($group_edit_sql);
            $group_edit_stmt->bindValue(':id',$id,PDO::PARAM_INT);
            $group_edit_stmt->bindValue(':group_code',$_POST['edit_group_code'],PDO::PARAM_STR);
            $group_edit_stmt->bindValue(':group_name',$_POST['edit_group_name'],PDO::PARAM_STR);
            $group_edit_stmt->bindValue(':parent_group',$_POST['edit_group_parent'],PDO::PARAM_STR);
            $group_edit_stmt->bindValue(':group_order',$_POST['edit_group_order'],PDO::PARAM_STR);
    
            $group_edit_stmt->execute();
    
            echo "更新しました";
    
            }catch(PDOException $e){
            echo $e->getMessage();
            }
    
        } else {
            echo"ERROR：不正な登録処理です";
        }    
    }
}

if ($_POST['delete_group_submit']) {
    $group_delete_sql = "DELETE FROM group_table WHERE id = :id";

    $group_delete_stmt = $pdo->prepare($group_delete_sql);
    $group_delete_stmt->bindValue(':id',$id,PDO::PARAM_INT);
    
    $group_delete_stmt->execute();
    
    header("Location: group.php?delete_msg=1");
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

            <!-- 保存時に部門コードが重複していてもOK -->
            <?php if ($group_code_edit == $_POST['edit_group_code']) : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <input type="text" name="edit_group_code" class="regi_name">

                <?php else : ?>

                    <?php if (isset($_POST['edit_group_code'])) : ?>
                        <input type="text" name="edit_group_code" class="regi_name" value="<?php echo htmlspecialchars($_POST['regi_group_code'], ENT_QUOTES); ?>">
                    <?php else : ?>
                        <input type="text" name="edit_group_code" class="regi_name" value="<?php echo $group_code_edit ; ?>">
                    <?php endif; ?>
                <?php endif ; ?>

                <span class="indi">
                    <?php 
                    if (isset($_POST['edit_group_submit'])) {
                        if (empty($_POST['edit_group_code'])) {
                            echo "入力必須項目です";
                        }
                    }
                    ?>
                </span>
            <?php else : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && $group_match_result == "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <input type="text" name="edit_group_code" class="regi_name">

                <?php else : ?>

                    <?php if (isset($_POST['edit_group_code'])) : ?>
                        <input type="text" name="edit_group_code" class="regi_name" value="<?php echo htmlspecialchars($_POST['regi_group_code'], ENT_QUOTES); ?>">
                    <?php else : ?>
                        <input type="text" name="edit_group_code" class="regi_name" value="<?php echo $group_code_edit ; ?>">
                    <?php endif; ?>
                <?php endif ; ?>

                <span class="indi">
                    <?php 
                    if (isset($_POST['edit_group_submit'])) {
                        if (empty($_POST['edit_group_code'])) {
                            echo "入力必須項目です";
                        }  elseif ($group_match_result == 1) {
                            echo "こちらの部門コードは既に使用されています";
                        }
                    }
                    ?>
                </span>
            <?php endif; ?>

        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">部門名</label>
                <p class="indi_mes">必須</p>
            </div>

            <?php if ($group_code_edit == $_POST['edit_group_code']) : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <input type="text" name="edit_group_name" class="regi_name">

                <?php else : ?>

                    <?php if (isset($_POST['edit_group_name'])) : ?>
                        <input type="text" name="edit_group_name" class="regi_name" value="<?php echo htmlspecialchars($_POST['edit_group_name'], ENT_QUOTES); ?>">
                    <?php else : ?>
                        <input type="text" name="edit_group_name" class="regi_name" value="<?php echo $group_name_edit ; ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <?php else : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && $group_match_result == "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <input type="text" name="edit_group_name" class="regi_name">

                <?php else : ?>

                    <?php if (isset($_POST['edit_group_name'])) : ?>
                        <input type="text" name="edit_group_name" class="regi_name" value="<?php echo htmlspecialchars($_POST['edit_group_name'], ENT_QUOTES); ?>">
                    <?php else : ?>
                        <input type="text" name="edit_group_name" class="regi_name" value="<?php echo $group_name_edit ; ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_group_submit'])) {
                    if (empty($_POST['edit_group_name'])) {
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <label class="regi_label">親部門</label>
            <select name="edit_group_parent" class="regi_branch">

            <!-- 部門コードの重複チェック -->
            <?php if ($group_code_edit == $_POST['edit_group_code']) : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <option value="">親部門なし</option>

                    <?php foreach ($group_parent_row as $group_row_edit) : ?>
                        <option value="<?php echo $group_row_edit['group_name']; ?>"><?php echo $group_row_edit['group_name']; ?></option>
                    <?php endforeach; ?>

                <?php else : ?>

                    <!-- 入力データ保持 -->
                    <?php if (isset($_POST['edit_group_parent'])) : ?>
                        <option value="">親部門なし</option>

                        <?php foreach ($group_parent_row as $group_row_edit) : ?>
                            <?php if ($group_row_edit['group_name'] ===  $_POST['edit_group_parent']) : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>" selected><?php echo $group_row_edit['group_name']; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>"><?php echo $group_row_edit['group_name']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    
                    <!-- 既存データがあった場合 -->
                    <?php else : ?>
                        <option value="">親部門なし</option>

                        <?php foreach ($group_parent_row as $group_row_edit) : ?>
                            <?php if ($group_row_edit['group_name'] ===  $parent_group_edit) : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>" selected><?php echo $group_row_edit['group_name']; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>"><?php echo $group_row_edit['group_name']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                <?php endif; ?>
            <?php else : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && $group_match_result == "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <option value="">親部門なし</option>

                    <?php foreach ($group_parent_row as $group_row_edit) : ?>
                        <option value="<?php echo $group_row_edit['group_name']; ?>"><?php echo $group_row_edit['group_name']; ?></option>
                    <?php endforeach; ?>

                <?php else : ?>

                    <?php if (isset($_POST['edit_group_parent'])) : ?>
                        <option value="">親部門なし</option>

                        <?php foreach ($group_parent_row as $group_row_edit) : ?>
                            <?php if ($group_row_edit['group_name'] ===  $_POST['edit_group_parent']) : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>" selected><?php echo $group_row_edit['group_name']; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>"><?php echo $group_row_edit['group_name']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else  : ?>
                        <!-- 既存データがあった場合 -->
                        <option value="">親部門なし</option>

                        <?php foreach ($group_parent_row as $group_row_edit) : ?>
                            <?php if ($group_row_edit['group_name'] ===  $parent_group_edit) : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>" selected><?php echo $group_row_edit['group_name']; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $group_row_edit['group_name']; ?>"><?php echo $group_row_edit['group_name']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                <?php endif; ?>
            <?php endif; ?>

            </select>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_group_submit'])) {
                    if ($_POST['edit_group_parent'] == $_POST['edit_group_name']) {
                        echo "自身を親部門に選ぶことはできません";
                    }  
                }
                ?>
            </span>

        </div>

        <div class="regi_class">
            <label class="regi_label">並び順</label>

            <?php if ($group_code_edit == $_POST['edit_group_code']) : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <input type="text" name="edit_group_order" class="regi_group_order">

                <?php else : ?>

                    <?php if (isset($_POST['edit_group_order'])) : ?>
                        <input type="text" name="edit_group_order" class="regi_group_order" value="<?php echo htmlspecialchars($_POST['edit_group_order'], ENT_QUOTES); ?>">
                    <?php else : ?>
                        <input type="text" name="edit_group_order" class="regi_group_order" value="<?php echo $group_order_edit ; ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <?php else : ?>
                <?php if (isset($_POST['edit_group_submit']) && $_POST['edit_group_code'] !== "" && $group_match_result == "" && 
                        $_POST['edit_group_name'] !== "" && $_POST['edit_group_parent'] !== $_POST['edit_group_name']) : ?>

                    <input type="text" name="edit_group_order" class="regi_group_order">

                <?php else : ?>

                    <?php if (isset($_POST['edit_group_order'])) : ?>
                        <input type="text" name="edit_group_order" class="regi_group_order" value="<?php echo htmlspecialchars($_POST['edit_group_order'], ENT_QUOTES); ?>">
                    <?php else : ?>
                        <input type="text" name="edit_group_order" class="regi_group_order" value="<?php echo $group_order_edit ; ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <div class="submit_etc">
            <input type="submit" name="edit_group_submit" value="編集" class="regi_submit">
            <input type="submit" name="delete_group_submit" value="削除" class="regi_submit">
        </div>

    </form>
</div>
</main>
</body>
</html>