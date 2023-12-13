<?php 
require_once('function.php');

require_once('not_login.php');

// 二重送信防止対策
// 
$id = $_GET['id'];

$tel_result = preg_match('/^0[0-9]{1,4}-[0-9]{1,4}-[0-9]{3,4}\z/', $_POST['edit_branch_tel']);

// POSTされたトークンとセッション変数のトークンの比較
    if (isset($_POST['edit_submit'])) {
        if ($_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1 && $_POST['edit_order'] !== "" ) {

            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {
                $sql = "UPDATE branches 
                        SET branch_name = :branch_name, tel_number = :tel_number, prefectures = :prefectures,
                            address = :address, address2 = :address2, address3 = :address3, order_list = :order_list 
                        WHERE id = :id";

                try{
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id',$id,PDO::PARAM_INT);
                $stmt->bindValue(':branch_name',$_POST['edit_branch_name'],PDO::PARAM_STR);
                $stmt->bindValue(':tel_number',$_POST['edit_branch_tel'],PDO::PARAM_STR);
                $stmt->bindValue(':prefectures',$_POST['pref'],PDO::PARAM_INT);
                $stmt->bindValue(':address',$_POST['city'],PDO::PARAM_STR);
                $stmt->bindValue(':address2',$_POST['city2'],PDO::PARAM_STR);
                $stmt->bindValue(':address3',$_POST['city3'],PDO::PARAM_STR);
                $stmt->bindValue(':order_list',$_POST['edit_order'],PDO::PARAM_STR);
        
                $stmt->execute();
            
                echo "更新しました";
        
                }catch(PDOException $e){
                echo $e->getMessage();
                }
            }
            
        } else {
        echo"ERROR：不正な登録処理です";
        }
    }

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

// 編集対象のデータをidをもとにとってくる
try{
    $edit_sql = "SELECT * FROM branches WHERE id = :id";
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(":id", $id,PDO::PARAM_INT);
    $edit_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}

// 取得できたデータを変数に入れておく
$edit_row = $edit_stmt->fetch(PDO::FETCH_ASSOC);
$branch_name = $edit_row['branch_name'];
$tel_number = $edit_row['tel_number'];
$pref = $edit_row['prefectures'];
$address = $edit_row['address'];
$address2 = $edit_row['address2'];
$address3 = $edit_row['address3'];
$order_list = $edit_row['order_list'];

require_once('pref_cotegory.php');

?>

<?php require_once('header.html'); ?>

<?php require_once('menu.php');?>

<header>
    <h1>支店編集</h1>
</header>

<main>

<div class="registration">
    <form action="" method="post">

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">支店名</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['edit_submit']) &&
            $_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" &&
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1  && $_POST['edit_order'] !== ""): ?>

                <input type="text" name="edit_branch_name" class="regi_branch_name">
            <?php else: ?>

                <?php if (isset($_POST['edit_branch_name'])): ?>
                    <input type="text" name="edit_branch_name" class="regi_branch_name"
                    value="<?php echo htmlspecialchars($_POST['edit_branch_name'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="text" name="edit_branch_name" class="regi_branch_name" 
                    value="<?php echo $branch_name; ?>">

                <?php endif; ?>

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])){
                    if (empty($_POST['edit_branch_name'])){
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>
        </div>

        <div class="regi_branch_class">
            <div class="regi_indi">
                <label class="regi_label">住所</label>
                <p class="indi_mes">必須</p>
            </div>

            <select name="pref" id="" class="regi_pref">

            <?php if (isset($_POST['edit_submit']) &&
            $_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1  && $_POST['edit_order'] !== ""): ?>

            <?php require_once('pref_option.php');?>

            <?php else: ?>
                <?php if (isset($_POST['pref'])): ?>
                    <?php 
                        foreach ($prefCotegory as $row) {
                            if ($_POST['pref'] == $row['value']) {
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                    ?>
                <?php else: ?>
                    <?php 
                        foreach ($prefCotegory as $row) {
                            if ($pref == $row['value']) {
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                    ?>
                <?php endif; ?>
            <?php endif; ?>

            </select>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['pref'])) {
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>

            <!-- 市区町村 -->
            <?php if (isset($_POST['edit_submit']) &&
            $_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1  && $_POST['edit_order'] !== ""): ?>

                <input type="text" name="city" placeholder="市区町村" class="regi_branch_text regi_city">
            <?php else: ?>

                <?php if (isset($_POST['city'])): ?>
                    <input type="text" name="city" placeholder="市区町村" class="regi_branch_text regi_city" 
                    value="<?php echo htmlspecialchars($_POST['city'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="text" name="city" placeholder="市区町村" class="regi_branch_text regi_city" 
                    value="<?php echo $address; ?>">

                <?php endif; ?>

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['city'])) {
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>

            <!-- 番地 -->
            <?php if (isset($_POST['edit_submit']) && 
            $_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1  && $_POST['edit_order'] !== ""): ?>

                <input type="text" name="city2" placeholder="番地" class="regi_branch_text regi_city">
            <?php else: ?>

                <?php if (isset($_POST['city2'])): ?>
                    <input type="text" name="city2" placeholder="番地" class="regi_branch_text regi_city" 
                    value="<?php echo htmlspecialchars($_POST['city2'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="text" name="city2" placeholder="番地" class="regi_branch_text regi_city" 
                    value="<?php echo $address2; ?>">

                <?php endif; ?>

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['city2'])) {
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>

            <!-- 建物名 -->
            <?php if (isset($_POST['edit_submit']) && 
            $_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1  && $_POST['edit_order'] !== ""): ?>

                <input type="text" name="city3" placeholder="建物名" class="regi_branch_text regi_city">
            <?php else: ?>

                <?php if (isset($_POST['city3'])): ?>
                    <input type="text" name="city3" placeholder="建物名" class="regi_branch_text regi_city" 
                    value="<?php echo htmlspecialchars($_POST['city3'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="text" name="city3" placeholder="建物名" class="regi_branch_text regi_city" 
                    value="<?php echo $address3; ?>">

                <?php endif; ?>

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['city3'])) {
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">電話番号</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['edit_submit']) && 
            $_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1  && $_POST['edit_order'] !== ""): ?>

                <input type="text" name="edit_branch_tel" class="regi_branch_text">
            <?php else: ?>

                <?php if (isset($_POST['edit_branch_tel'])): ?>
                    <input type="text" name="edit_branch_tel" class="regi_branch_text" 
                    value="<?php echo htmlspecialchars($_POST['edit_branch_tel'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="text" name="edit_branch_tel" class="regi_branch_text" 
                    value="<?php echo $tel_number; ?>">

                <?php endif; ?>

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['edit_branch_tel'])) {
                        echo "入力必須項目です";
                    } elseif ($tel_result == 0){
                        echo "電話番号(ハイフンあり)の形式でご記入ください";
                    }
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">並び順</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['edit_submit']) && 
            $_POST['edit_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['edit_branch_tel'] !== "" && $tel_result == 1  && $_POST['edit_order'] !== ""): ?>

                <input type="text" name="edit_order"  class="regi_branch_text">
            <?php else: ?>

                <?php if (isset($_POST['edit_order'])): ?>
                    <input type="text" name="edit_order" class="regi_branch_text" 
                    value="<?php echo htmlspecialchars($_POST['edit_order'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="text" name="edit_order" class="regi_branch_text" 
                    value="<?php echo $order_list; ?>">
                    
                <?php endif; ?>

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['edit_order'])) {
                        echo "入力必須項目です";
                    }
                }
                ?>
            </span>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="edit_submit" value="登録" class="regi_submit">
    </form>
</div>
</main>
</body>
</html>