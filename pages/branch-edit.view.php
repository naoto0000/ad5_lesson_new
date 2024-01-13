<?php require_once('header.html'); ?>
<?php require_once('menu.php'); ?>

<header id="header">
    <h1>支店編集</h1>
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
                <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_branch_name'])) : ?>
                    <!-- 編集があった場合、その内容を保持 -->
                    <input type="text" name="edit_branch_name" class="regi_branch_name" value="<?php e($_POST['edit_branch_name']); ?>">
                <?php else : ?>
                    <!-- branch.phpからのデータを保持 -->
                    <input type="text" name="edit_branch_name" class="regi_branch_name" value="<?php echo $branch_name; ?>">
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

                    <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['pref'])) : ?>
                        <?php include(__DIR__ . '/../utilities/pref_option.php'); ?>
                        <?php
                        foreach ($prefCotegory as $row) {
                            if ($_POST['pref'] == $row['value']) {
                                echo '<option value="' . $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="' . $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                        ?>
                    <?php else : ?>
                        <?php
                        foreach ($prefCotegory as $row) {
                            if ($pref == $row['value']) {
                                echo '<option value="' . $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="' . $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                        ?>
                    <?php endif; ?>

                </select>

                <span class="indi">
                    <?php
                    if (isset($_SESSION['validation_errors']['pref']) && $_SESSION['validation_errors']['pref']) {
                        echo $error_message1;
                        initializeValidationErrors('pref');
                    }
                    ?>
                </span>

                <!-- 市区町村 -->
                <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['address'])) : ?>
                    <input type="text" name="address" placeholder="市区町村" class="regi_branch_text regi_city" value="<?php e($_POST['address']); ?>">
                <?php else : ?>
                    <input type="text" name="address" placeholder="市区町村" class="regi_branch_text regi_city" value="<?php echo $address; ?>">
                <?php endif; ?>

                <!-- 番地 -->
                <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['address2'])) : ?>
                    <input type="text" name="address2" placeholder="番地" class="regi_branch_text regi_city" value="<?php e($_POST['address2']); ?>">
                <?php else : ?>
                    <input type="text" name="address2" placeholder="番地" class="regi_branch_text regi_city" value="<?php echo $address2; ?>">
                <?php endif; ?>

                <!-- 建物名 -->
                <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['address3'])) : ?>
                    <input type="text" name="address3" placeholder="建物名" class="regi_branch_text regi_city" value="<?php e($_POST['address3']); ?>">
                <?php else : ?>
                    <input type="text" name="address3" placeholder="建物名" class="regi_branch_text regi_city" value="<?php echo $address3; ?>">
                <?php endif; ?>

            </div>

            <div class="regi_class">
                <div class="regi_indi">
                    <label class="regi_label">電話番号</label>
                    <p class="indi_mes">必須</p>
                </div>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_branch_tel'])) : ?>
                    <input type="text" name="edit_branch_tel" class="regi_branch_text" value="<?php e($_POST['edit_branch_tel']); ?>">
                <?php else : ?>
                    <input type="text" name="edit_branch_tel" class="regi_branch_text" value="<?php echo $tel_number; ?>">
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
                <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_order'])) : ?>
                    <input type="text" name="edit_order" class="regi_branch_text" value="<?php e($_POST['edit_order']); ?>">
                <?php else : ?>
                    <input type="text" name="edit_order" class="regi_branch_text" value="<?php echo $order_list; ?>">
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

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <input type="submit" name="edit_submit" value="登録" class="regi_submit">
        </form>
    </div>
</main>
</body>

</html>