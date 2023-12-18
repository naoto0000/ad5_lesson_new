<?php require_once('header.html'); ?>
<?php require_once('menu.php'); ?>

<header>
    <h1>社員登録</h1>
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
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="text" name="regi_name" class="regi_name">
                <?php else : ?>
                    <input type="text" name="regi_name" class="regi_name" value="<?php e($_POST['regi_name']); ?>">
                <?php endif; ?>

                <span class="indi">
                    <?php
                    if ($_SESSION['validation_errors']['regi_name']) {
                        echo $error_message1;
                        $_SESSION['validation_errors']['regi_name'] = "";
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
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="text" name="regi_kana" class="regi_name">
                <?php else : ?>
                    <input type="text" name="regi_kana" class="regi_name" value="<?php e($_POST['regi_kana']); ?>">
                <?php endif; ?>

                <span class="indi">
                    <?php
                    if ($_SESSION['validation_errors']['regi_kana']) {
                        echo $error_message1;
                        $_SESSION['validation_errors']['regi_kana'] = "";
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

                    <?php if (count($_SESSION['validation_errors']) === 0) : ?>

                        <option value="" selected>支店を選択</option>

                        <?php
                        foreach ($branch_row as $branch_name_row) {
                            echo '<option value="' . $branch_name_row['id'] . '">' . $branch_name_row['branch_name'] . '</option>';
                        }
                        ?>

                    <?php else : ?>

                        <?php if (isset($_POST['regi_submit']) && isset($_POST['regi_branch'])) : ?>

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
                    if ($_SESSION['validation_errors']['regi_branch']) {
                        echo $error_message1;
                        $_SESSION['validation_errors']['regi_branch'] = "";
                    }
                    ?>
                </span>

            </div>

            <div class="regi_class">
                <label class="regi_label">性別</label>
                <select name="regi_sex" class="regi_sex">

                    <?php if (count($_SESSION['validation_errors']) === 0) : ?>

                        <option value="3">選択</option>
                        <option value="1">男</option>
                        <option value="2">女</option>

                    <?php else : ?>
                        <?php
                        foreach ($sexCotegory as $row) {
                            if ($_POST['regi_sex'] == $row['value']) {
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
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" value="">
                <?php else : ?>
                    <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" value="<?php e($_POST['regi_birth']); ?>">
                <?php endif; ?>

            </div>

            <div class="regi_class">
                <div class="regi_indi">
                    <label class="regi_label">メールアドレス</label>
                    <p class="indi_mes">必須</p>
                </div>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="text" name="regi_mail" class="regi_mail">
                <?php else : ?>
                    <input type="text" name="regi_mail" class="regi_mail" value="<?php e($_POST['regi_mail']); ?>">
                <?php endif; ?>

                <span class="indi">
                    <?php
                    if ($_SESSION['validation_errors']['regi_mail']) {
                        echo $error_message1;
                        $_SESSION['validation_errors']['regi_mail'] = "";
                        // 下記をしておかないとelseifのエラーメッセージが表示されてしまう
                        $_SESSION['validation_errors']['mail_check'] = "";
                    } elseif ($_SESSION['validation_errors']['mail_check']) {
                        echo $error_message2;
                        $_SESSION['validation_errors']['mail_check'] = "";
                    }

                    if ($_SESSION['validation_errors']['mail_match']) {
                        echo $error_message3;
                        $_SESSION['validation_errors']['mail_match'] = "";
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
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <div class="login_pass_class">
                        <input type="password" name="regi_pass" class="regi_pass">
                        <i id="eye" class="fa-solid fa-eye"></i>
                    </div>
                <?php else : ?>
                    <div class="login_pass_class">
                        <input type="password" name="regi_pass" class="regi_pass" value="<?php e($_POST['regi_pass']); ?>">
                        <i id="eye" class="fa-solid fa-eye"></i>
                    </div>
                <?php endif; ?>

                <span class="indi">
                    <?php
                    if ($_SESSION['validation_errors']['regi_pass']) {
                        echo $error_message1;
                        $_SESSION['validation_errors']['regi_pass'] = "";
                        // 下記をしておかないとelseifのエラーメッセージが表示されてしまう
                        $_SESSION['validation_errors']['pass_check'] = "";
                    } elseif ($_SESSION['validation_errors']['pass_check']) {
                        echo $error_message4;
                        $_SESSION['validation_errors']['pass_check'] = "";
                    }
                    ?>
                </span>
            </div>

            <div class="regi_class">
                <label class="regi_label">通勤時間(分)</label>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <input type="number" min="1" name="regi_com" class="regi_com">
                <?php else : ?>
                    <input type="number" min="1" name="regi_com" class="regi_com" value="<?php e($_POST['regi_com']); ?>">
                <?php endif; ?>

            </div>

            <div class="regi_class">
                <div class="regi_indi">
                    <label class="regi_label">血液型</label>
                    <p class="indi_mes">必須</p>
                </div>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <div class="regi_blood">

                        <div>
                            <input type="radio" name="regi_blood" value=1 />
                            <label for="A型">A型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value=2 />
                            <label for="B型">B型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value=3 />
                            <label for="O型">O型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value=4 />
                            <label for="AB型">AB型</label>
                        </div>

                        <div>
                            <input type="radio" name="regi_blood" value=5 />
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
                    if ($_SESSION['validation_errors']['regi_blood']) {
                        echo $error_message1;
                        $_SESSION['validation_errors']['regi_blood'] = "";
                    }
                    ?>
                </span>

            </div>

            <div class="regi_class">
                <label class="regi_label">既婚</label>

                <!-- 入力データ保持の条件分岐 -->
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <div>
                        <input type="checkbox" name="regi_married" value="1" />
                        <label for="既婚">既婚</label>
                    </div>
                <?php else : ?>
                    <?php if ($_POST['regi_married']) : ?>
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

                <!-- 入力データ保持の条件分岐 -->
                <?php if (count($_SESSION['validation_errors']) === 0) : ?>
                    <div>
                        <?php foreach ($quali_masta as $quali_masta_row) : ?>
                            <input type="checkbox" name="regi_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" />
                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div>
                        <?php foreach ($quali_masta as $quali_masta_row) : ?>
                            <?php if (in_array($quali_masta_row['id'], (array)$_POST['regi_quali'])) : ?>
                                <input type="checkbox" name="regi_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" checked />
                                <label for="quali_<?php echo $quali_masta_row['id']; ?>"><?php echo $quali_masta_row['quali_name']; ?></label>
                            <?php else : ?>
                                <input type="checkbox" name="regi_quali[]" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" />
                                <label for="quali_<?php echo $quali_masta_row['id']; ?>"><?php echo $quali_masta_row['quali_name']; ?></label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <input type="submit" name="regi_submit" value="登録" class="regi_submit">
        </form>
    </div>
</main>
</body>
<script>
    let eye = document.getElementById("eye");
    eye.addEventListener('click', function() {
        if (this.previousElementSibling.getAttribute('type') == 'password') {
            this.previousElementSibling.setAttribute('type', 'text');
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        } else {
            this.previousElementSibling.setAttribute('type', 'password');
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        }
    })
</script>

</html>