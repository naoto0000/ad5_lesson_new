
<?php 

require_once('function.php');

$_POST['regi_mail'] == "";

// lesson15追加課題
$qualies = array($_POST['regi_quali1'],$_POST['regi_quali2'],$_POST['regi_quali3'],$_POST['regi_quali4'],$_POST['regi_quali7']);

$quali = implode(',',array_filter($qualies));

$mail_result = 
    preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['regi_mail']);

$pass_result = preg_match('/^[a-zA-Z0-9]{8,}$/',($_POST['regi_pass']));

$hashed_pass = password_hash($_POST['regi_pass'], PASSWORD_DEFAULT);

require_once('mail_get.php');

foreach ($mail_match_row as $mail_match) {
    if ($mail_match['email'] == $_POST['regi_mail']) {
        $mail_match_result = 1;
    }
}

$regi_com_int = (int)$_POST['regi_com'];

    // トークンの比較で二重送信対策
    if (isset($_POST['regi_submit'])) {
        if ($_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
         $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])) {

            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

                $sql = 'INSERT INTO employees
                (name, kana, sex, birthdate, email, password, comm_time, blood_type, married, branch_id, quali) 
                VALUES (:name, :kana, :sex, :birthdate, :email, :password, :comm_time, :blood_type, :married, :branch_id, :quali)';
                
                try{
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':name',$_POST['regi_name'],PDO::PARAM_STR);
                $stmt->bindValue(':kana',$_POST['regi_kana'],PDO::PARAM_STR);
                $stmt->bindValue(':sex',$_POST['regi_sex'],PDO::PARAM_INT);
                $stmt->bindValue(':birthdate',$_POST['regi_birth'],PDO::PARAM_STR);
                $stmt->bindValue(':email',$_POST['regi_mail'],PDO::PARAM_STR);
                $stmt->bindValue(':password',$hashed_pass,PDO::PARAM_STR);
                $stmt->bindValue(':comm_time',$_POST['regi_com'],PDO::PARAM_STR);
                $stmt->bindValue(':blood_type',$_POST['regi_blood'],PDO::PARAM_INT);
                $stmt->bindValue(':married',$_POST['regi_married'],PDO::PARAM_INT);
                $stmt->bindValue(':branch_id',$_POST['regi_branch'],PDO::PARAM_INT);
                $stmt->bindValue(':quali',$quali,PDO::PARAM_STR);
        
                $stmt->execute();
        
                header('Location: ok_regi.php');

                }catch(PDOException $e){
                echo $e->getMessage();
                }
            }
        } else {
            echo"ERROR：不正な登録処理です";
        }
    }

require_once('branch_get.php');

require_once('quali_get.php');

$sexCotegory = [
    ['value' => '3', 'text' => '選択'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女']
];

require_once('blood_category.php');

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

?>

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
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                <input type="text" name="regi_name" class="regi_name">
            <?php else: ?>
                <input type="text" name="regi_name" class="regi_name" 
                value="<?php echo htmlspecialchars($_POST['regi_name'], ENT_QUOTES); ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_name'])) {
                        echo "入力必須項目です";
                    }  
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
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                <input type="text" name="regi_kana" class="regi_name">
            <?php else: ?>
                <input type="text" name="regi_kana" class="regi_name" 
                value="<?php echo htmlspecialchars($_POST['regi_kana'], ENT_QUOTES); ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_kana'])) {
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <label class="regi_label">支店</label>
            <select name="regi_branch" class="regi_branch" >

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['regi_submit']) && 
                $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
                $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                    <option value="" selected>支店を選択</option>

                    <?php 
                        foreach ($branch_row as $branch_name_row){
                            echo '<option value="'. $branch_name_row['id'] .'">' . $branch_name_row['branch_name'] . '</option>';
                        }
                    ?>

                <?php else: ?>

                    <?php if (isset($_POST['regi_submit']) && isset($_POST['regi_branch'])): ?>

                        <option value="">支店を選択</option>

                        <?php 
                            foreach ($branch_row as $branch_name_row){
                                if ($_POST['regi_branch'] == $branch_name_row['id']) {
                                    echo '<option value="'. $branch_name_row['id'] .'"selected>' . $branch_name_row['branch_name'] . '</option>';
                                } else {
                                    echo '<option value="'. $branch_name_row['id'] .'">' . $branch_name_row['branch_name'] . '</option>';
                                }
                            }
                        ?>
                    <?php else: ?>

                        <option value=""selected>支店を選択</option>

                        <?php 
                            foreach ($branch_row as $branch_name_row){
                                echo '<option value="'. $branch_name_row['id'] .'">' . $branch_name_row['branch_name'] . '</option>';
                            }
                        ?>
                    <?php endif; ?>

                <?php endif; ?>
            </select>
        </div>

        <div class="regi_class">
            <label class="regi_label">性別</label>
            <select name="regi_sex" class="regi_sex">

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['regi_submit']) && 
                $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
                $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                    <option value="">選択</option>
                    <option value="1">男</option>
                    <option value="2">女</option>

                <?php else: ?>
                    <?php 
                    foreach ($sexCotegory as $row){
                        if ($_POST['regi_sex'] == $row['value']) {
                            echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                        } else {
                            echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                        }
                    }
                    ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="regi_class">
            <label class="regi_label">生年月日</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" value="">
            <?php else: ?>
                <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" 
                value="<?php echo htmlspecialchars($_POST['regi_birth'], ENT_QUOTES); ?>">

            <?php endif; ?>

        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">メールアドレス</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                <input type="text" name="regi_mail" class="regi_mail">
            <?php else: ?>

                <input type="text" name="regi_mail" class="regi_mail" 
                value="<?php echo htmlspecialchars($_POST['regi_mail'], ENT_QUOTES); ?>">

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_mail'])) {
                        echo "入力必須項目です";
                    } elseif ($mail_result === 0) {
                        echo "メールアドレスの形式でご記入ください";
                    } elseif ($mail_match_result === 1) {
                        echo "こちらのメールアドレスは既に使用されています";
                    }
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
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                <div class="login_pass_class">
                    <input type="password" name="regi_pass" class="regi_pass">
                    <i id="eye" class="fa-solid fa-eye"></i>
                </div>

            <?php else: ?>

                <div class="login_pass_class">
                    <input type="password" name="regi_pass" class="regi_pass" 
                    value="<?php echo htmlspecialchars($_POST['regi_pass'], ENT_QUOTES); ?>">
                    <i id="eye" class="fa-solid fa-eye"></i>
                </div>


            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_pass'])) {
                        echo "入力必須項目です";
                    } elseif ($pass_result == 0) {
                        echo "半角英数字8文字以上でご記入ください";
                    }
                }
                ?>
            </span>
        </div>

        <script>
          let eye = document.getElementById("eye");
          eye.addEventListener('click', function () {
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

        <div class="regi_class">
            <label class="regi_label">通勤時間(分)</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                <input type="number" min="1" name="regi_com" class="regi_com">

            <?php else: ?>

                <input type="number" min="1" name="regi_com" class="regi_com" 
                value="<?php echo htmlspecialchars($_POST['regi_com'], ENT_QUOTES); ?>">

            <?php endif; ?>

        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">血液型</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>

                <div class="regi_blood">
                    
                    <div>
                        <input type="radio"  name="regi_blood" value="1" checked />
                        <label for="A型">A型</label>
                    </div>

                    <div>
                        <input type="radio"  name="regi_blood" value="2" />
                        <label for="B型">B型</label>
                    </div>

                    <div>
                        <input type="radio" name="regi_blood" value="3" />
                        <label for="O型">O型</label>
                    </div> 

                    <div>
                        <input type="radio" name="regi_blood" value="4" />
                        <label for="AB型">AB型</label>
                    </div>  

                    <div>
                        <input type="radio" name="regi_blood" value="5" />
                        <label for="不明">不明</label>
                    </div> 
                </div> 
            <?php else: ?>
                <div class="regi_blood">
                    <?php 
                        foreach ($bloodCotegory as $blood){
                            if (isset($_POST['regi_blood']) && $_POST['regi_blood'] == $blood['value']) {
                                echo 
                                '<div>
                                    <input type="radio" name="regi_blood" value="'. $blood['value'] . '" selected/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                                        
                            } else {
                                echo 
                                '<div>
                                    <input type="radio" name="regi_blood" value="'. $blood['value'] . '"/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                            }
                        }
                        ?>
                </div>

            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['regi_submit'])) {
                    if (empty($_POST['regi_blood'])) {
                        echo "入力必須項目です";
                    }
                }
                ?>
            </span>

        </div>

        <div class="regi_class">
            <label class="regi_label">既婚</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "" && isset($_POST['regi_blood'])): ?>
            
                <div>
                    <input type="checkbox"  name="regi_married" value="1" />
                    <label for="既婚">既婚</label>
                </div>            
            <?php else: ?>
                <div>
                    <input type="checkbox" name="regi_married" value="1"/>
                    <label for="既婚">既婚</label>
                </div>            
            <?php endif; ?>

        </div>

        <div class="regi_class">
            <label class="regi_label">保有資格</label>

            <div>
                <?php foreach ($quali_masta as $quali) : ?>
                    <input type="checkbox"  name="regi_quali<?php echo $quali['id']; ?>" value="<?php echo $quali['id']; ?>" class="quali_checkbox" />
                    <label for=""><?php echo $quali['quali_name']; ?></label>
                <?php endforeach ; ?>
            </div>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="regi_submit" value="登録" class="regi_submit">
    </form>
</div>
</main>
</body>
</html>