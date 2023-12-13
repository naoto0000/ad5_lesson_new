
<?php 
require_once('function.php');

require_once('not_login.php');

$tel_result = preg_match('/^0[0-9]{1,4}-[0-9]{1,4}-[0-9]{3,4}\z/', $_POST['regi_branch_tel']);

    // トークンの比較で二重送信対策
    if (isset($_POST['regi_submit'])) {
        if ($_POST['regi_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
        $_POST['city3'] !== "" && $_POST['regi_branch_tel'] !== "" && $tel_result == 1 && $_POST['regi_order'] !== "" ) {

            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

                $sql = 'INSERT INTO branches
                (branch_name, tel_number, prefectures, address, address2, address3, order_list) 
                VALUES (:branch_name, :tel_number, :prefectures, :address, :address2, :address3, :order_list)';
                    
                try{
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':branch_name',$_POST['regi_branch_name'],PDO::PARAM_STR);
                $stmt->bindValue(':tel_number',$_POST['regi_branch_tel'],PDO::PARAM_STR);
                $stmt->bindValue(':prefectures',$_POST['pref'],PDO::PARAM_INT);
                $stmt->bindValue(':address',$_POST['city'],PDO::PARAM_STR);
                $stmt->bindValue(':address2',$_POST['city2'],PDO::PARAM_STR);
                $stmt->bindValue(':address3',$_POST['city3'],PDO::PARAM_STR);
                $stmt->bindValue(':order_list',$_POST['regi_order'],PDO::PARAM_STR);
        
                $stmt->execute();
        
                echo "登録しました";

                }catch(PDOException $e){
                echo $e->getMessage();
                }
            } else {
                echo"ERROR：不正な登録処理です";
            }
        }
    }

    require_once('pref_cotegory.php');

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

?>

<?php require_once('header.html'); ?>

<?php require_once('menu.php');?>

<header>
    <h1>支店登録</h1>
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
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['regi_branch_tel'] !== "" && $tel_result == 1  && $_POST['regi_order'] !== ""): ?>

                <input type="text" name="regi_branch_name" class="regi_branch_name">
            <?php else: ?>
                <input type="text" name="regi_branch_name" class="regi_branch_name" 
                value="<?php echo htmlspecialchars($_POST['regi_branch_name'], ENT_QUOTES); ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_branch_name'])) {
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

            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['regi_branch_tel'] !== "" && $tel_result == 1  && $_POST['regi_order'] !== ""): ?>

                <?php require_once('pref_option.php');?>

            <?php else: ?>
                <?php 
                    foreach ($prefCotegory as $row) {
                        if ($_POST['pref'] == $row['value']) {
                            echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                        } else {
                            echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                        }
                    }
                ?>
            <?php endif; ?>

            </select>

            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['regi_branch_tel'] !== "" && $tel_result == 1  && $_POST['regi_order'] !== ""): ?>

                <input type="text" name="city" placeholder="市区町村" class="regi_branch_text regi_city">
                <input type="text" name="city2" placeholder="番地" class="regi_branch_text regi_city">
                <input type="text" name="city3" placeholder="建物名" class="regi_branch_text regi_city">

            <?php else: ?>

                <input type="text" name="city" placeholder="市区町村" class="regi_branch_text regi_city" 
                value="<?php echo htmlspecialchars($_POST['city'], ENT_QUOTES); ?>">

                <input type="text" name="city2" placeholder="番地" class="regi_branch_text regi_city" 
                value="<?php echo htmlspecialchars($_POST['city2'], ENT_QUOTES); ?>">

                <input type="text" name="city3" placeholder="建物名" class="regi_branch_text regi_city" 
                value="<?php echo htmlspecialchars($_POST['city3'], ENT_QUOTES); ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['pref'])) {
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
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['regi_branch_tel'] !== "" && $tel_result == 1  && $_POST['regi_order'] !== ""): ?>

                <input type="text" name="regi_branch_tel" class="regi_branch_text">

            <?php else: ?>

                <input type="text" name="regi_branch_tel" class="regi_branch_text" 
                value="<?php echo htmlspecialchars($_POST['regi_branch_tel'], ENT_QUOTES); ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_branch_tel'])) {
                        echo "入力必須項目です";
                    } elseif ($tel_result == 0) {
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
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_branch_name'] !== "" && $_POST['pref'] !== "" && $_POST['city'] !== "" && $_POST['city2'] !== "" && 
            $_POST['city3'] !== "" && $_POST['regi_branch_tel'] !== "" && $tel_result == 1  && $_POST['regi_order'] !== ""): ?>

                <input type="text" name="regi_order"  class="regi_branch_text">

            <?php else: ?>

                <input type="text" name="regi_order" class="regi_branch_text" 
                value="<?php echo htmlspecialchars($_POST['regi_order'], ENT_QUOTES); ?>">
                
            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_order'])) {
                        echo "入力必須項目です";
                    }
                }
                ?>
            </span>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="regi_submit" value="登録" class="regi_submit">
    </form>
</div>
</main>
</body>
</html>