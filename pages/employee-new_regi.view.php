<?php require_once('header.html'); ?>

<header>
    <h1>新規登録</h1>
</header>

<main>
    <div class="registration">
        <form action="" method="post">

            <div class="regi_class">
                <div class="regi_indi">
                    <label class="regi_label">氏名</label>
                    <p class="indi_mes">必須</p>
                </div>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="text" name="regi_name" class="regi_name">
                <?php else : ?>
                    <input type="text" name="regi_name" class="regi_name" value="<?php echo isset($_POST['regi_name']) ? e($_POST['regi_name']) : ''; ?>">
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
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="text" name="regi_kana" class="regi_name">
                <?php else : ?>
                    <input type="text" name="regi_kana" class="regi_name" value="<?php echo isset($_POST['regi_kana']) ? e($_POST['regi_kana']) : ''; ?>">
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
                <select name="regi_branch" class="regi_branch">

                    <!-- 入力データ保持の条件分岐 -->
                    <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>

                        <option value="" selected>支店を選択</option>

                        <?php
                        foreach ($branch_row as $branch_name_row) {
                            echo '<option value="' . $branch_name_row['id'] . '">' . $branch_name_row['branch_name'] . '</option>';
                        }
                        ?>

                    <?php else : ?>

                        <?php if (isset($_POST['regi_branch'])) : ?>

                            <option value="">支店を選択</option>

                            <?php
                            foreach ($branch_row as $branch_name_row) {
                                if ($_POST['regi_branch'] == $branch_name_row['id']) {
                                    echo '<option value="' . $branch_name_row['id'] . '"selected>' . $branch_name_row['branch_name'] . '</option>';
                                } else {
                                    echo '<option value="' . $branch_name_row['id'] . '">' . $branch_name_row['branch_name'] . '</option>';
                                }
                            }
                            ?>
                        <?php else : ?>

                            <option value="" selected>支店を選択</option>

                            <?php
                            foreach ($branch_row as $branch_name_row) {
                                echo '<option value="' . $branch_name_row['id'] . '">' . $branch_name_row['branch_name'] . '</option>';
                            }
                            ?>
                        <?php endif; ?>

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
                <select name="regi_sex" class="regi_sex">

                    <!-- 入力データ保持の条件分岐 -->
                    <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>

                        <option value="">選択</option>
                        <option value="1">男</option>
                        <option value="2">女</option>

                    <?php else : ?>
                        <?php
                        foreach ($sexCotegory as $row) {
                            if (isset($_POST['regi_sex']) && $_POST['regi_sex'] == $row['value']) {
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
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" value="">
                <?php else : ?>
                    <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" value="<?php echo isset($_POST['regi_birth']) ? e($_POST['regi_birth']) : ''; ?>">
                <?php endif; ?>

            </div>

            <div class="regi_class">
                <div class="regi_indi">
                    <label class="regi_label">メールアドレス</label>
                    <p class="indi_mes">必須</p>
                </div>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="text" name="regi_mail" class="regi_mail">
                <?php else : ?>
                    <input type="text" name="regi_mail" class="regi_mail" value="<?php echo isset($_POST['regi_mail']) ? e($_POST['regi_mail']) : ''; ?>">
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
                    <p class="indi_mes">必須</p>
                </div>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <div class="login_pass_class">
                        <input type="password" name="regi_pass" class="regi_pass">
                        <i id="eye" class="fa-solid fa-eye"></i>
                    </div>
                <?php else : ?>
                    <div class="login_pass_class">
                        <input type="password" name="regi_pass" class="regi_pass" value="<?php echo isset($_POST['regi_pass']) ? e($_POST['regi_pass']) : ''; ?>">
                        <i id="eye" class="fa-solid fa-eye"></i>
                    </div>
                <?php endif; ?>

                <span class="indi">
                    <?php
                    if (isset($_SESSION['validation_errors']['pass']) && $_SESSION['validation_errors']['pass']) {
                        echo $error_message1;
                        initializeValidationErrors('pass');
                        initializeValidationErrors('pass_check');
                    } elseif (isset($_SESSION['validation_errors']['pass_check']) && $_SESSION['validation_errors']['pass_check']) {
                        echo $error_message4;
                        initializeValidationErrors('pass_check');
                    }
                    ?>
                </span>
            </div>

            <div class="regi_class">
                <label class="regi_label">通勤時間(分)</label>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="number" min="1" name="regi_com" class="regi_com">
                <?php else : ?>
                    <input type="number" min="1" name="regi_com" class="regi_com" value="<?php echo isset($_POST['regi_com']) ? e($_POST['regi_com']) : ''; ?>">
                <?php endif; ?>

            </div>

            <div class="regi_class">
                <div class="regi_indi">
                    <label class="regi_label">血液型</label>
                    <p class="indi_mes">必須</p>
                </div>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <div class="regi_blood">
                        <div>
                            <input type="radio" name="regi_blood" value="1" />
                            <label for="A型">A型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value="2" />
                            <label for="B型">B型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value="3" />
                            <label for="AB型">AB型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value="4" />
                            <label for="O型">O型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value="5" />
                            <label for="不明">不明</label>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="regi_blood">
                        <?php
                        foreach ($bloodCotegory as $blood) {
                            if (isset($_POST['regi_blood']) && $_POST['regi_blood'] == $blood['value']) {
                                echo
                                '<div>
                                    <input type="radio" name="regi_blood" value="' . $blood['value'] . '" checked/>
                                    <label for="' . $blood['value'] . '">' . $blood['text'] . '</label>
                                </div>';
                            } else {
                                echo
                                '<div>
                                    <input type="radio" name="regi_blood" value="' . $blood['value'] . '"/>
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
                <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                    <div>
                        <input type="checkbox" name="regi_married" value="1" />
                        <label for="既婚">既婚</label>
                    </div>
                <?php else : ?>
                    <?php if (isset($_POST['regi_married']) && $_POST['regi_married']) : ?>
                        <div>
                            <input type="checkbox" name="regi_married" value="1" checked />
                            <label for="既婚">既婚</label>
                        </div>
                    <?php else : ?>
                        <div>
                            <input type="checkbox" name="regi_married" value="1" />
                            <label for="既婚">既婚</label>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="regi_class">
                <label class="regi_label">保有資格</label>
                <div class="regi_quali">
                    <!-- 入力データ保持の条件分岐 -->
                    <?php if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors']) && count($_SESSION['validation_errors']) === 0) : ?>
                        <?php foreach ($quali_masta as $quali_masta_row) : ?>
                            <div>
                                <input type="checkbox" name="regi_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" />
                                <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php foreach ($quali_masta as $quali_masta_row) : ?>
                            <?php if (isset($_POST['regi_quali[]']) && in_array($quali_masta_row['id'], (array)$_POST['regi_quali'])) : ?>
                                <div>
                                    <input type="checkbox" name="regi_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" checked />
                                    <label for="quali_<?php echo $quali_masta_row['id']; ?>"><?php echo $quali_masta_row['quali_name']; ?></label>
                                </div>
                            <?php else : ?>
                                <div>
                                    <input type="checkbox" name="regi_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" />
                                    <label for="quali_<?php echo $quali_masta_row['id']; ?>"><?php echo $quali_masta_row['quali_name']; ?></label>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <input type="submit" name="regi_submit" value="登録" class="regi_submit">
        </form>
    </div>
</main>
</body>
</html>