<?php 

require_once('function.php');

// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {

    // $_SESSION['access'] = "on";

    header('Location: login.php');
}

$_POST['regi_mail'] == "";



// lesson15追加課題

$qualies = array($_POST['regi_quali1'],$_POST['regi_quali2'],$_POST['regi_quali3'],$_POST['regi_quali4'],$_POST['regi_quali5']);

$quali = implode(',',array_filter($qualies));

var_dump($quali); 


$mail_result = 
    preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['regi_mail']);

$pass_result = preg_match('/^[a-zA-Z0-9]{6,}$/',($_POST['regi_pass']));

$hashed_pass = password_hash($_POST['regi_pass'], PASSWORD_DEFAULT);



// lesson18 メールアドレスのデータ取得
// 削除された人のメアドを使えるようにしている
try{
    $mail_sql = "SELECT email FROM employees WHERE delete_flg IS NULL";
    $mail_stmt = $pdo->prepare($mail_sql);
    $mail_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}

// 取得できたデータを変数に入れておく
$mail_match_row = $mail_stmt->fetchAll(PDO::FETCH_ASSOC);

$mail_match_result = "";


foreach ($mail_match_row as $mail_match) {
    if ($mail_match['email'] == $_POST['regi_mail']) {
        $mail_match_result = 1;
    }
}

    // トークンの比較で二重送信対策
    if (isset($_POST['regi_submit'])) {
        if ($_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
         $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== "") {

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
    
            echo "登録しました";

    
            }catch(PDOException $e){
            echo $e->getMessage();
            }
        } else {
            echo"ERROR：不正な登録処理です";
        }
    }
}

// lesson10 部門データ取得
try{
    $branch_sql = "SELECT * FROM branches ORDER BY order_list";
    $branch_stmt = $pdo->prepare($branch_sql);
    $branch_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}

// 取得できたデータを変数に入れておく
$branch_row = $branch_stmt->fetchAll(PDO::FETCH_ASSOC);


// lesson16 資格関連
$quali_get_sql = "SELECT * FROM `quali`";
$quali_masta = $pdo->query($quali_get_sql);


$sexCotegory = [
    ['value' => '3', 'text' => '選択'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女']
];

$bloodCotegory = [
    ['value' => '1', 'text' => 'A型'],
    ['value' => '2', 'text' => 'B型'],
    ['value' => '3', 'text' => 'AB型'],
    ['value' => '4', 'text' => 'O型'],
    ['value' => '5', 'text' => '不明']
];

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

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
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

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
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

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
            <label class="regi_label">部門</label>
            <select name="regi_branch" class="regi_branch" >

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['regi_submit']) && 
                $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
                $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

                    <option value="" selected>部門を選択</option>

                    <?php 
                        foreach ($branch_row as $branch_name_row){
                            echo '<option value="'. $branch_name_row['id'] .'">' . $branch_name_row['branch_name'] . '</option>';
                        }
                    ?>

                <?php else: ?>

                    <?php if (isset($_POST['regi_submit']) && isset($_POST['regi_branch'])): ?>

                        <option value="">部門を選択</option>

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

                        <option value=""selected>部門を選択</option>

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
                $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

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
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

                <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth">
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
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

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
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

                <input type="password" name="regi_pass" class="regi_pass">
            <?php else: ?>

                <input type="password" name="regi_pass" class="regi_pass" 
                value="<?php echo htmlspecialchars($_POST['regi_pass'], ENT_QUOTES); ?>">

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


        <div class="regi_class">
            <label class="regi_label">通勤時間(分)</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

                <input type="text" name="regi_com" class="regi_com">
            <?php else: ?>

                <input type="text" name="regi_com" class="regi_com" 
                value="<?php echo htmlspecialchars($_POST['regi_com'], ENT_QUOTES); ?>">

            <?php endif; ?>

        </div>

        <div class="regi_class_new">
            <label class="regi_label">血液型</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>

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

        </div>


        <div class="regi_class_new">
            <label class="regi_label">既婚</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if (isset($_POST['regi_submit']) && 
            $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" &&
            $mail_result == 1 && $mail_match_result == "" && $pass_result == 1 && $_POST['regi_pass'] !== ""): ?>
            
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

        <div class="regi_class_new">
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