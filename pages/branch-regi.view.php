<?php require_once('header.html'); ?>
<?php require_once('menu.php');?>

<header id="header">
    <h1>支店登録</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
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
            <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                <input type="text" name="regi_branch_name" class="regi_branch_name">
            <?php else: ?>
                <input type="text" name="regi_branch_name" class="regi_branch_name" value="<?php echo isset($_POST['regi_branch_name']) ? e($_POST['regi_branch_name']) : ''; ?>">
            <?php endif; ?>

            <span class="indi">
                <?php 
                    if (isset($_SESSION['validation_errors']['branch_name']) && $_SESSION['validation_errors']['branch_name']) {
                        echo $error_message1;
                        initializeValidationErrors('branch_name');
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

            <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                <?php include(__DIR__ . '/../utilities/pref_option.php');?>
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

            <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>

                <input type="text" name="address" placeholder="市区町村" class="regi_branch_text regi_city">
                <input type="text" name="address2" placeholder="番地" class="regi_branch_text regi_city">
                <input type="text" name="address3" placeholder="建物名" class="regi_branch_text regi_city">

            <?php else: ?>

                <input type="text" name="address" placeholder="市区町村" class="regi_branch_text regi_city" 
                value="<?php echo isset($_POST['address']) ? e($_POST['address']) : ''; ?>">

                <input type="text" name="address2" placeholder="番地" class="regi_branch_text regi_city" 
                value="<?php echo isset($_POST['address2']) ? e($_POST['address2']) : ''; ?>">

                <input type="text" name="address3" placeholder="建物名" class="regi_branch_text regi_city" 
                value="<?php echo isset($_POST['address3']) ? e($_POST['address3']) : ''; ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                    if (isset($_SESSION['validation_errors']['pref']) && $_SESSION['validation_errors']['pref']) {
                        echo $error_message1;
                        initializeValidationErrors('pref');
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
            <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                <input type="text" name="regi_branch_tel" class="regi_branch_text">
            <?php else: ?>
                <input type="text" name="regi_branch_tel" class="regi_branch_text" 
                value="<?php echo isset($_POST['regi_branch_tel']) ? e($_POST['regi_branch_tel']) : ''; ?>">
            <?php endif; ?>

            <span class="indi">
                <?php 
                    if (isset($_SESSION['validation_errors']['branch_tel']) && $_SESSION['validation_errors']['branch_tel']) {
                        echo $error_message1;
                        initializeValidationErrors('branch_tel');
                        initializeValidationErrors('tel_check');
                    } elseif (isset($_SESSION['validation_errors']['tel_check']) && $_SESSION['validation_errors']['tel_check']) {
                        echo $error_message5;
                        initializeValidationErrors('tel_check');
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
            <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                <input type="text" name="regi_order"  class="regi_branch_text">
            <?php else: ?>
                <input type="text" name="regi_order" class="regi_branch_text" 
                value="<?php echo isset($_POST['regi_order']) ? e($_POST['regi_order']) : ''; ?>">
            <?php endif; ?>

            <span class="indi">
            <?php 
                if (isset($_SESSION['validation_errors']['branch_order']) && $_SESSION['validation_errors']['branch_order']) {
                    echo $error_message1;
                    initializeValidationErrors('branch_order');
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