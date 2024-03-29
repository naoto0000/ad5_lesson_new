<?php require_once('header.html'); ?>
<?php require_once('menu.php'); ?>
<header id="header">
    <h1>社員編集</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
</header>

<main>

    <?php if ($errar_mes) : ?>
        <div>
            <?php echo $errar_mes; ?>
        </div>
    <?php else : ?>
        <div>

            <div class="registration">
                <form action="" method="post">

                    <div class="regi_class">
                        <div class="regi_indi">
                            <label class="regi_label">氏名</label>
                            <p class="indi_mes">必須</p>
                        </div>


                        
                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_name'])) : ?>
                            <!-- 編集があった場合、その内容を保持 -->
                            <input type="text" name="edit_name" class="regi_name" value="<?php e($_POST['edit_name']); ?>">
                        <?php else : ?>
                            <!-- index.phpからのデータを保持 -->
                            <input type="text" name="edit_name" class="regi_name" value="<?php echo $name; ?>">
                        <?php endif; ?>

                        <span class="indi">
                            <?php
                            if (isset($_SESSION['validation_errors']['name']) && $_SESSION['validation_errors']['name']) {
                                echo $error_message1;
                                initializeValidationErrors('name');
                            }
                            ?>
                        </span>
                    </div>

                    <div class="regi_class">
                        <div class="regi_indi">
                            <label class="regi_label">かな</label>
                            <p class="indi_mes">必須</p>
                        </div>
                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_kana'])) : ?>
                            <input type="text" name="edit_kana" class="regi_name" value="<?php e($_POST['edit_kana']); ?>">
                        <?php else : ?>
                            <input type="text" name="edit_kana" class="regi_name" value="<?php echo $kana; ?>">
                        <?php endif; ?>

                        <span class="indi">
                            <?php
                            if (isset($_SESSION['validation_errors']['kana']) && $_SESSION['validation_errors']['kana']) {
                                echo $error_message1;
                                initializeValidationErrors('kana');
                            }
                            ?>
                        </span>

                    </div>

                    <div class="regi_class">
                        <div class="regi_indi">
                            <label class="regi_label">支店</label>
                            <p class="indi_mes">必須</p>
                        </div>
                        <select name="edit_branch" class="regi_branch">

                            <!-- 入力データ保持の条件分岐 -->
                            <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_branch'])) : ?>

                                <option value="">支店を選択</option>

                                <?php
                                foreach ($branch_row as $branch_name_row) {
                                    if ($_POST['edit_branch'] == $branch_name_row['id']) {
                                        echo '<option value="' . $branch_name_row['id'] . '"selected>' . $branch_name_row['branch_name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $branch_name_row['id'] . '">' . $branch_name_row['branch_name'] . '</option>';
                                    }
                                }
                                ?>
                            <?php else : ?>

                                <!-- 保存データの表示 -->
                                <option value="">支店を選択</option>

                                <?php
                                foreach ($branch_row as $branch_name_row) {
                                    if ($branch_id == $branch_name_row['id']) {
                                        echo '<option value="' . $branch_name_row['id'] . '"selected>' . $branch_name_row['branch_name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $branch_name_row['id'] . '">' . $branch_name_row['branch_name'] . '</option>';
                                    }
                                }
                                ?>
                            <?php endif; ?>
                        </select>

                        <span class="indi">
                            <?php
                            if (isset($_SESSION['validation_errors']['branch']) && $_SESSION['validation_errors']['branch']) {
                                echo $error_message1;
                                initializeValidationErrors('branch');
                            }
                            ?>
                        </span>

                    </div>

                    <div class="regi_class">
                        <label class="regi_label">性別</label>
                        <select name="edit_sex" class="regi_sex">

                            <!-- 入力データ保持の条件分岐 -->
                            <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_sex'])) : ?>
                                <?php
                                foreach ($sexCotegory as $row) {
                                    if ($_POST['edit_sex'] == $row['value']) {
                                        echo '<option value="' . $row['value'] . '"selected>' . $row['text'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row['value'] . '">' . $row['text'] . '</option>';
                                    }
                                }
                                ?>
                                <!-- index.phpからのデータを保持 -->
                            <?php else : ?>
                                <?php
                                foreach ($sexCotegory as $row) {
                                    if ($sex == $row['value']) {
                                        echo '<option value="' . $row['value'] . '"selected>' . $row['text'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row['value'] . '">' . $row['text'] . '</option>';
                                    }
                                }
                                ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="regi_class">
                        <label class="regi_label">生年月日</label>

                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_birth'])) : ?>
                            <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth" value="<?php e($_POST['edit_birth']); ?>">
                        <?php else : ?>
                            <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth" value="<?php echo $birthdate; ?>">
                        <?php endif; ?>
                    </div>

                    <div class="regi_class">
                        <div class="regi_indi">
                            <label class="regi_label">メールアドレス</label>
                            <p class="indi_mes">必須</p>
                        </div>

                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_mail'])) : ?>
                            <input type="text" name="edit_mail" class="regi_mail" value="<?php e($_POST['edit_mail']); ?>">
                        <?php else : ?>
                            <input type="text" name="edit_mail" class="regi_mail" value="<?php echo $email; ?>">
                        <?php endif; ?>

                        <span class="indi">
                            <?php
                            if (isset($_SESSION['validation_errors']['mail']) && $_SESSION['validation_errors']['mail']) {
                                echo $error_message1;
                                initializeValidationErrors('mail');
                                initializeValidationErrors('mail_check');
                                initializeValidationErrors('mail_match');
                            } elseif (isset($_SESSION['validation_errors']['mail_check']) && $_SESSION['validation_errors']['mail_check']) {
                                echo $error_message2;
                                initializeValidationErrors('mail_check');
                                initializeValidationErrors('mail_match');
                            } elseif (isset($_SESSION['validation_errors']['mail_match']) && $_SESSION['validation_errors']['mail_match']) {
                                echo $error_message3;
                                initializeValidationErrors('mail_match');
                            }
                            ?>
                        </span>
                    </div>

                    <div class="regi_class">
                        <div class="regi_indi">
                            <label class="regi_label">パスワード</label>
                            <p class="indi_mes">変更する場合のみ入力</p>
                        </div>

                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_pass'])) : ?>
                            <div class="login_pass_class">
                                <input type="password" name="edit_pass" class="regi_pass" value="<?php e($_POST['edit_pass']); ?>">
                                <i id="eye" class="fa-solid fa-eye"></i>
                            </div>
                        <?php else : ?>
                            <div class="login_pass_class">
                                <input type="password" name="edit_pass" class="regi_pass" value="">
                                <i id="eye" class="fa-solid fa-eye"></i>
                            </div>
                        <?php endif; ?>

                        <span class="indi">
                            <?php
                            if (isset($_SESSION['validation_errors']['pass_check']) && $_SESSION['validation_errors']['pass_check']) {
                                echo $error_message4;
                                initializeValidationErrors('pass_check');
                            }
                            ?>
                        </span>
                    </div>

                    <div class="regi_class">
                        <label class="regi_label">通勤時間(分)</label>

                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_com'])) : ?>
                            <input type="number" min="1" name="edit_com" class="regi_com" value="<?php e($_POST['edit_com']); ?>">
                        <?php else : ?>
                            <?php if ($comm_time == 0) : ?>
                                <input type="number" min="1" name="edit_com" class="regi_com">
                            <?php else : ?>
                                <input type="number" min="1" name="edit_com" class="regi_com" value="<?php echo $comm_time; ?>">
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>

                    <div class="regi_class">
                        <div class="regi_indi">
                            <label class="regi_label">血液型</label>
                            <p class="indi_mes">必須</p>
                        </div>

                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_blood'])) : ?>
                            <div class="regi_blood">
                                <?php
                                foreach ($bloodCotegory as $blood) {
                                    if ($_POST['edit_blood'] == $blood['value']) {
                                        echo
                                        '<div>
                                            <input type="radio" name="edit_blood" value="' . $blood['value'] . '" checked/>
                                            <label for="' . $blood['value'] . '">' . $blood['text'] . '</label>
                                        </div>';
                                    } else {
                                        echo
                                        '<div>
                                            <input type="radio" name="edit_blood" value="' . $blood['value'] . '"/>
                                            <label for="' . $blood['value'] . '">' . $blood['text'] . '</label>
                                        </div>';
                                    }
                                }
                                ?>
                            </div>
                        <?php else : ?>
                            <div class="regi_blood">
                                <?php
                                foreach ($bloodCotegory as $blood) {
                                    if ($blood_type == $blood['value']) {
                                        echo
                                        '<div>
                                        <input type="radio" name="edit_blood" value="' . $blood['value'] . '" checked/>
                                        <label for="' . $blood['value'] . '">' . $blood['text'] . '</label>
                                    </div>';
                                    } else {
                                        echo
                                        '<div>
                                        <input type="radio" name="edit_blood" value="' . $blood['value'] . '"/>
                                        <label for="' . $blood['value'] . '">' . $blood['text'] . '</label>
                                    </div>';
                                    }
                                }
                                ?>
                            </div>

                        <?php endif; ?>
                        <span class="indi">
                            <?php
                            if (isset($_SESSION['validation_errors']['blood']) && $_SESSION['validation_errors']['blood']) {
                                echo $error_message1;
                                initializeValidationErrors('blood');
                            }
                            ?>
                        </span>
                    </div>

                    <div class="regi_class">
                        <label class="regi_label">既婚</label>
                        <!-- 入力データ保持の条件分岐 -->
                        <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0) : ?>
                            <?php if (isset($_POST['edit_married'])) : ?>
                                <div>
                                    <input type="checkbox" name="edit_married" value="1" class="married_input" checked />
                                    <label for="既婚">既婚</label>
                                </div>
                            <?php else : ?>
                                <div>
                                    <input type="checkbox" name="edit_married" value="1" class="married_input" />
                                    <label for="既婚">既婚</label>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if ($married == 1) : ?>
                                <div>
                                    <input type="checkbox" name="edit_married" value="1" class="married_input" checked />
                                    <label for="既婚">既婚</label>
                                </div>
                            <?php else : ?>
                                <div>
                                    <input type="checkbox" name="edit_married" value="1" class="married_input" />
                                    <label for="既婚">既婚</label>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="regi_class">
                        <label class="regi_label">保有資格</label>
                        <div class="regi_quali">

                            <?php foreach ($quali_masta as $quali_masta_row) : ?>
                                <?php if (isset($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) !== 0 && isset($_POST['edit_quali[]']) && in_array($quali_masta_row['id'], (array)$_POST['edit_quali'])) : ?>
                                    <!-- postされたデータを保持 -->
                                    <?php if (isset($_POST['edit_quali[]']) && in_array($quali_masta_row['id'], (array)$_POST['edit_quali'])) : ?>
                                        <div>
                                            <input type="checkbox" name="edit_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" checked />
                                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                                        </div>
                                    <?php else : ?>
                                        <div>
                                            <input type="checkbox" name="edit_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" />
                                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                                        </div>
                                    <?php endif; ?>

                                <?php else : ?>
                                    <!-- 既に登録データがあった場合 -->
                                    <?php if (1 == preg_match('/\b' . preg_quote($quali_masta_row['id'], '/') . '\b/', $quali_id)) : ?>
                                        <div>
                                            <input type="checkbox" name="edit_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" checked />
                                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                                        </div>
                                    <?php else : ?>
                                        <div>
                                            <input type="checkbox" name="edit_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" />
                                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <input type="hidden" name="token" value="<?php echo $token; ?>">

                    <div class="submit_etc">
                        <input type="submit" name="edit_submit" value="保存" class="regi_submit">
                        <input type="submit" name="delete_submit" value="削除" class="regi_submit" onclick="return confirm('社員データを削除しても良いですか？');">
                    </div>

                </form>
            </div>
        </div>
    <?php endif; ?>

</main>
</body>
</html>