<?php 

require_once('function.php');
require_once('validation.php');

// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php');
}

$id = $_GET['id'];

$errar_mes = "";

if (!$id) {
    // 一覧画面へ戻る
    header('Location: index.php');
}

// lesson15追加課題

$qualies = array($_POST['edit_quali1'],$_POST['edit_quali2'],$_POST['edit_quali3'],$_POST['edit_quali4'],$_POST['edit_quali5']);

$quali = implode(',',array_filter($qualies));

var_dump($quali); 


if ($_POST['edit_submit']) {
    validate($_POST);
}

$edit_name = htmlspecialchars($_POST['edit_name'], ENT_QUOTES);
$edit_kana = htmlspecialchars($_POST['edit_kana'], ENT_QUOTES);
$edit_birth = htmlspecialchars($_POST['edit_birth'], ENT_QUOTES);
$edit_mail = htmlspecialchars($_POST['edit_mail'], ENT_QUOTES);
$edit_pass = htmlspecialchars($_POST['edit_pass'], ENT_QUOTES);
$edit_com = htmlspecialchars((int)$_POST['edit_com'], ENT_QUOTES);

// lesson18 パスワードのハッシュ化
$hashed_pass = password_hash($edit_pass, PASSWORD_DEFAULT);

$mail_result = preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $edit_mail);

$pass_result = preg_match('/^[a-zA-Z0-9]{6,}$/',$edit_pass);

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
    if ($mail_match['email'] == $edit_pass) {
        $mail_match_result = 1;
    }
}



// POSTされたトークンとセッション変数のトークンの比較
    if (isset($_POST['edit_submit'])) {
        if ($_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && $_POST['edit_mail'] !== "" && $mail_result == 1 && $pass_result == 1) {
            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {
                // if(is_int($edit_com_int) == true && $edit_com_int > 0){
                    $sql = "UPDATE employees 
                    SET name = :name, kana = :kana, sex = :sex, birthdate = :birthdate, 
                        email = :email, password = :password, comm_time = :comm_time, blood_type = :blood_type, married = :married, branch_id = :branch_id, quali = :quali
                    WHERE id = :id";
    
                    try{
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id',$id,PDO::PARAM_INT);
                    $stmt->bindValue(':name',$edit_name,PDO::PARAM_STR);
                    $stmt->bindValue(':kana',$edit_kana,PDO::PARAM_STR);
                    $stmt->bindValue(':sex',$_POST['edit_sex'],PDO::PARAM_INT);
                    $stmt->bindValue(':birthdate',$edit_birth,PDO::PARAM_STR);
                    $stmt->bindValue(':email',$edit_mail,PDO::PARAM_STR);
                    $stmt->bindValue(':password',$hashed_pass,PDO::PARAM_STR);
                    $stmt->bindValue(':comm_time',$edit_com,PDO::PARAM_INT);
                    $stmt->bindValue(':blood_type',$_POST['edit_blood'],PDO::PARAM_INT);
                    $stmt->bindValue(':married',$_POST['edit_married'],PDO::PARAM_INT);
                    $stmt->bindValue(':branch_id',$_POST['edit_branch'],PDO::PARAM_INT);
                    $stmt->bindValue(':quali',$quali,PDO::PARAM_STR);
            
                    $stmt->execute();
            
                
                    echo "更新しました";
            
                    }catch(PDOException $e){
                    echo $e->getMessage();
                    // }
    
                }

            }
            

        } elseif ($_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && $_POST['edit_mail'] !== "" && $mail_result == 1) {
            if ($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

                $sql = "UPDATE employees 
                SET name = :name, kana = :kana, sex = :sex, birthdate = :birthdate, 
                    email = :email, comm_time = :comm_time, blood_type = :blood_type, married = :married, branch_id = :branch_id, quali = :quali
                WHERE id = :id";
    
                try{
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id',$id,PDO::PARAM_INT);
                $stmt->bindValue(':name',$edit_name,PDO::PARAM_STR);
                $stmt->bindValue(':kana',$edit_kana,PDO::PARAM_STR);
                $stmt->bindValue(':sex',$_POST['edit_sex'],PDO::PARAM_INT);
                $stmt->bindValue(':birthdate',$edit_birth,PDO::PARAM_STR);
                $stmt->bindValue(':email',$edit_mail,PDO::PARAM_STR);
                $stmt->bindValue(':comm_time',$edit_com,PDO::PARAM_INT);
                $stmt->bindValue(':blood_type',$_POST['edit_blood'],PDO::PARAM_INT);
                $stmt->bindValue(':married',$_POST['edit_married'],PDO::PARAM_INT);
                $stmt->bindValue(':branch_id',$_POST['edit_branch'],PDO::PARAM_INT);
                $stmt->bindValue(':quali',$quali,PDO::PARAM_STR);
            
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

$sexCotegory = [
    ['value' => '3', 'text' => '選択'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女']
];

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

// 編集対象のデータをidをもとにとってくる
try{
    $edit_sql = "SELECT * FROM employees WHERE id = :id";
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
    $name = $edit_row['name'];
    $kana = $edit_row['kana'];
    $sex = $edit_row['sex'];
    $birthdate = $edit_row['birthdate'];
    $email = $edit_row['email'];
    $comm_time = $edit_row['comm_time'];
    $blood_type = $edit_row['blood_type'];
    $married = $edit_row['married'];
    $branch_id = $edit_row['branch_id'];
    $quali_id = $edit_row['quali'];
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

$bloodCotegory = [
    ['value' => 1, 'text' => 'A型'],
    ['value' => 2, 'text' => 'B型'],
    ['value' => 3, 'text' => 'AB型'],
    ['value' => 4, 'text' => 'O型'],
    ['value' => 5, 'text' => '不明']
];

// 削除部分
if ($_POST['delete_submit']) {
    $delete_sql = "UPDATE employees SET delete_flg = :delete_flg WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->bindParam(":id", $id);
    $delete_stmt->bindValue(':delete_flg',1,PDO::PARAM_STR);
    $delete_stmt->execute();

    header("Location: index.php?delete_msg=1");
}

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
    <h1>社員編集</h1>
</header>

<main>

<?php if ($errar_mes) : ?>
<div>
    <?php echo $errar_mes; ?>
</div>

<?php else : ?>
<div>


<div class="registration">
    <form action="" method="post" >

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">氏名</label>
                <p class="indi_mes">必須</p>
            </div>
                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1
                // && is_int($edit_com_int) == true && $edit_com_int > 0
                ): ?>


                    <input type="text" name="edit_name" class="regi_name">
                <?php else: ?>

                    <?php if (isset($_POST['edit_name'])) :?>
                        <!-- 編集があった場合、その内容を保持 -->
                        <input type="text" name="edit_name" class="regi_name" 
                        value="<?php echo htmlspecialchars($_POST['edit_name'], ENT_QUOTES); ?>">

                    <?php else: ?>

                        <!-- index.phpからのデータを保持 -->
                        <input type="text" name="edit_name" class="regi_name" value="<?php echo $name; ?>">

                    <?php endif; ?>
                <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])){
                    if (empty($_POST['edit_name'])){
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
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                    <input type="text" name="edit_kana" class="regi_name">
                <?php else: ?>

                    <?php if (isset($_POST['edit_kana'])) :?>
                        <input type="text" name="edit_kana" class="regi_name" 
                        value="<?php echo htmlspecialchars($_POST['edit_kana'], ENT_QUOTES); ?>">

                    <?php else: ?>
                        <input type="text" name="edit_kana" class="regi_name" value="<?php echo $kana; ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])){
                    if (empty($_POST['edit_kana'])){
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>

        </div>

        <div class="regi_class">
            <label class="regi_label">部門</label>
            <select name="edit_branch" class="regi_branch" >

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                    <option value="" selected>部門を選択</option>

                    <?php 
                        foreach ($branch_row as $branch_name_row){
                            echo '<option value="'. $branch_name_row['id'] .'">' . $branch_name_row['branch_name'] . '</option>';
                        }
                    ?>

                <?php else: ?>

                    <?php if (isset($_POST['edit_submit']) && isset($_POST['edit_branch'])): ?>

                        <option value="">部門を選択</option>

                        <?php 
                            foreach ($branch_row as $branch_name_row){
                                if ($_POST['edit_branch'] == $branch_name_row['id']) {
                                    echo '<option value="'. $branch_name_row['id'] .'"selected>' . $branch_name_row['branch_name'] . '</option>';
                                } else {
                                    echo '<option value="'. $branch_name_row['id'] .'">' . $branch_name_row['branch_name'] . '</option>';
                                }
                            }
                        ?>
                    <?php else: ?>

                        <!-- 保存データの表示 -->
                        <option value="">部門を選択</option>

                        <?php 
                            foreach ($branch_row as $branch_name_row){
                                if ($branch_id == $branch_name_row['id']) {
                                    echo '<option value="'. $branch_name_row['id'] .'"selected>' . $branch_name_row['branch_name'] . '</option>';
                                } else {
                                    echo '<option value="'. $branch_name_row['id'] .'">' . $branch_name_row['branch_name'] . '</option>';
                                }
                            }
                        ?>
                    <?php endif; ?>

                <?php endif; ?>
            </select>
        </div>


        <div class="regi_class">
            <label class="regi_label">性別</label>
            <select name="edit_sex" class="regi_sex">

                <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                    <option value="">選択</option>
                    <option value="1">男</option>
                    <option value="2">女</option>

                <?php else: ?>
                    <!-- 編集があった場合、その内容を保持 -->
                    <?php if (isset($_POST['edit_sex'])): ?>
                        <?php 
                        foreach ($sexCotegory as $row){
                            if ($_POST['edit_sex'] == $row['value']) {
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                        ?>
                    <!-- index.phpからのデータを保持 -->
                    <?php else: ?>
                        <?php 
                        foreach ($sexCotegory as $row){
                            if ($sex == $row['value']) {
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                        ?>
                    <?php endif; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="regi_class">
        <label class="regi_label">生年月日</label>

            <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth">

            <?php else: ?>
                <?php if (isset($_POST['edit_birth'])): ?>
                    <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth" 
                    value="<?php echo htmlspecialchars($_POST['edit_birth'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth" 
                    value="<?php echo $birthdate; ?>">

                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">メールアドレス</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                <input type="text" name="edit_mail" class="regi_mail">
            <?php else: ?>
                <?php if (isset($_POST['edit_mail'])): ?>
                    <input type="text" name="edit_mail" class="regi_mail" 
                    value="<?php echo htmlspecialchars($_POST['edit_mail'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="text" name="edit_mail" class="regi_mail" value="<?php echo $email; ?>">
                <?php endif; ?>
            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['edit_mail'])) {
                        echo "入力必須項目です";
                    } elseif ($mail_result == 0) {
                        echo "メールアドレスの形式でご記入ください";
                    } 
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
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                <input type="password" name="edit_pass" class="regi_pass">
            <?php else: ?>

                <input type="password" name="edit_pass" class="regi_pass" 
                value="<?php echo htmlspecialchars($_POST['edit_pass'], ENT_QUOTES); ?>">
            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if ($_POST['edit_pass'] !== "" && $pass_result == 0) {
                        echo "半角英数字8文字以上でご記入ください";
                    }
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <label class="regi_label">通勤時間(分)</label>

            <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                <input type="number" min="1" name="edit_com" class="regi_com">
            <?php else: ?>
                <?php if (isset($_POST['edit_com'])): ?>
                    <input type="number" min="1" name="edit_com" class="regi_com" 
                    value="<?php echo htmlspecialchars($_POST['edit_com'], ENT_QUOTES); ?>">

                <?php else: ?>
                    <input type="number" min="1" name="edit_com" class="regi_com" value="<?php echo $comm_time; ?>">
                <?php endif; ?>
            <?php endif; ?>

            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit']) && $_POST['edit_com'] !== "") {
                    if (is_int($edit_com_int) == false || $edit_com_int <= 0 || is_int($edit_com_int) == false && $edit_com_int <= 0) {
                        echo "１以上の整数でご記入ください";
                    }
                }
                ?>
            </span>

        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">血液型</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>

                <div class="regi_blood">
                    
                    <div>
                        <input type="radio"  name="edit_blood" value="1" checked />
                        <label for="A型">A型</label>
                    </div>

                    <div>
                        <input type="radio"  name="edit_blood" value="2" />
                        <label for="B型">B型</label>
                    </div>

                    <div>
                        <input type="radio" name="edit_blood" value="3" />
                        <label for="O型">O型</label>
                    </div> 

                    <div>
                        <input type="radio" name="edit_blood" value="4" />
                        <label for="AB型">AB型</label>
                    </div>  

                    <div>
                        <input type="radio" name="edit_blood" value="5" />
                        <label for="不明">不明</label>
                    </div> 
                </div> 
            <?php else: ?>
                <div class="regi_blood">
                    <?php 
                    if (isset($_POST['edit_blood'])) {
                        foreach ($bloodCotegory as $blood){
                            if ($_POST['edit_blood'] == $blood['value']) {
                                echo 
                                '<div>
                                    <input type="radio" name="edit_blood" value="'. $blood['value'] . '" checked/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                                        
                            } else {
                                echo 
                                '<div>
                                    <input type="radio" name="edit_blood" value="'. $blood['value'] . '"/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                            }
                        }
                    } else {
                        foreach ($bloodCotegory as $blood){
                            if ($blood_type == $blood['value']) {
                                echo 
                                '<div>
                                    <input type="radio" name="edit_blood" value="'. $blood['value'] . '" checked/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                                        
                            } else {
                                echo 
                                '<div>
                                    <input type="radio" name="edit_blood" value="'. $blood['value'] . '"/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                            }
                        }
                    }
                        ?>
                </div>

            <?php endif; ?>
            <span class="indi">
                <?php 
                if (isset($_POST['edit_submit'])) {
                    if (empty($_POST['edit_blood'])) {
                        echo "入力必須項目です";
                    }
                }
                ?>
            </span>


        </div>


        <div class="regi_class_new">
            <label class="regi_label">既婚</label>

            <!-- 入力データ保持の条件分岐 -->
                <?php if (isset($_POST['edit_submit']) && 
                $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>
                
                <div>
                    <input type="checkbox"  name="edit_married" value="1" class="married_input" />
                    <label for="既婚">既婚</label>
                </div>            
            <?php else: ?>
                <?php if (isset($_POST['edit_married']) || $married == 1): ?>
                <div>
                    <input type="checkbox" name="edit_married" value="1" class="married_input" checked />
                    <label for="既婚">既婚</label>
                </div>    
                <?php else: ?> 
                <div>
                    <input type="checkbox" name="edit_married" value="1" class="married_input"  />
                    <label for="既婚">既婚</label>
                </div>
                <?php endif; ?>
      
            <?php endif; ?>

        </div>

        <div class="regi_class_new">
            <label class="regi_label">保有資格</label>

            <div>
                <?php foreach ($quali_masta as $quali_masta_row) : ?>
                    <?php if (isset($_POST['edit_submit']) && 
                        $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== "" && 
                        $_POST['edit_mail'] !== "" && $mail_result == 1 && $_POST['edit_blood'] !== "" && $pass_result == 1): ?>
                            <input type="checkbox"  name="edit_quali<?php echo $quali_masta_row['id']; ?>" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox"/>
                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                    <?php else : ?>
                        <?php if (1 === preg_match($quali_masta_row['id'],$quali_id) || isset($_POST['edit_quali'])) : ?>
                            <input type="checkbox"  name="edit_quali<?php echo $quali_masta_row['id']; ?>" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox" checked />
                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                        <?php else : ?>
                            <input type="checkbox"  name="edit_quali<?php echo $quali_masta_row['id']; ?>" value="<?php echo $quali_masta_row['id']; ?>" class="quali_checkbox"/>
                            <label for=""><?php echo $quali_masta_row['quali_name']; ?></label>
                        <?php endif; ?>
                    <?php endif ; ?>
                <?php endforeach ; ?>
                
            </div>            


        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">


        <div class="submit_etc">
            <input type="submit" name="edit_submit" value="保存" class="regi_submit">
            <input type="submit" name="delete_submit" value="削除" class="regi_submit">
        </div>

    </form>
</div>
</div>
<?php endif ; ?>

</main>

</body>
</html>
