<?php 
require_once('function.php');

// lesson19 ログインしてない時の処理
if (empty($_SESSION['id'])) {
    header('Location: login.php');
}


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

$prefCotegory = [
    ['value' => '0', 'text' => '都道府県を選択'],
    ['value' => '1', 'text' => '北海道'],
    ['value' => '2', 'text' => '青森県'],
    ['value' => '3', 'text' => '岩手県'],
    ['value' => '4', 'text' => '宮城県'],
    ['value' => '5', 'text' => '秋田県'],
    ['value' => '6', 'text' => '山形県'],
    ['value' => '7', 'text' => '福島県'],
    ['value' => '8', 'text' => '茨城県'],
    ['value' => '9', 'text' => '栃木県'],
    ['value' => '10', 'text' => '群馬県'],
    ['value' => '11', 'text' => '埼玉県'],
    ['value' => '12', 'text' => '千葉県'],
    ['value' => '13', 'text' => '東京都'],
    ['value' => '14', 'text' => '神奈川県'],
    ['value' => '15', 'text' => '新潟県'],
    ['value' => '16', 'text' => '富山県'],
    ['value' => '17', 'text' => '石川県'],
    ['value' => '18', 'text' => '福井県'],
    ['value' => '19', 'text' => '山梨県'],
    ['value' => '20', 'text' => '長野県'],
    ['value' => '21', 'text' => '岐阜県'],
    ['value' => '22', 'text' => '静岡県'],
    ['value' => '23', 'text' => '愛知県'],
    ['value' => '24', 'text' => '三重県'],
    ['value' => '25', 'text' => '滋賀県'],
    ['value' => '26', 'text' => '京都府'],
    ['value' => '27', 'text' => '大阪府'],
    ['value' => '28', 'text' => '兵庫県'],
    ['value' => '29', 'text' => '奈良県'],
    ['value' => '30', 'text' => '和歌山県'],
    ['value' => '31', 'text' => '鳥取県'],
    ['value' => '32', 'text' => '島根県'],
    ['value' => '33', 'text' => '岡山県'],
    ['value' => '34', 'text' => '広島県'],
    ['value' => '35', 'text' => '山口県'],
    ['value' => '36', 'text' => '徳島県'],
    ['value' => '37', 'text' => '香川県'],
    ['value' => '38', 'text' => '愛媛県'],
    ['value' => '39', 'text' => '高知県'],
    ['value' => '40', 'text' => '福岡県'],
    ['value' => '41', 'text' => '佐賀県'],
    ['value' => '42', 'text' => '長崎県'],
    ['value' => '43', 'text' => '熊本県'],
    ['value' => '44', 'text' => '大分県'],
    ['value' => '45', 'text' => '宮崎県'],
    ['value' => '46', 'text' => '鹿児島県'],
    ['value' => '47', 'text' => '沖縄県']
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

                <option value="0">都道府県を選択</option>
                <option value="1">北海道</option>
                <option value="2">青森県</option>
                <option value="3">岩手県</option>
                <option value="4">宮城県</option>
                <option value="5">秋田県</option>
                <option value="6">山形県</option>
                <option value="7">福島県</option>
                <option value="8">茨城県</option>
                <option value="9">栃木県</option>
                <option value="10">群馬県</option>
                <option value="11">埼玉県</option>
                <option value="12">千葉県</option>
                <option value="13">東京都</option>
                <option value="14">神奈川県</option>
                <option value="15">新潟県</option>
                <option value="16">富山県</option>
                <option value="17">石川県</option>
                <option value="18">福井県</option>
                <option value="19">山梨県</option>
                <option value="20">長野県</option>
                <option value="21">岐阜県</option>
                <option value="22">静岡県</option>
                <option value="23">愛知県</option>
                <option value="24">三重県</option>
                <option value="25">滋賀県</option>
                <option value="26">京都府</option>
                <option value="27">大阪府</option>
                <option value="28">兵庫県</option>
                <option value="29">奈良県</option>
                <option value="30">和歌山県</option>
                <option value="31">鳥取県</option>
                <option value="32">島根県</option>
                <option value="33">岡山県</option>
                <option value="34">広島県</option>
                <option value="35">山口県</option>
                <option value="36">徳島県</option>
                <option value="37">香川県</option>
                <option value="38">愛媛県</option>
                <option value="39">高知県</option>
                <option value="40">福岡県</option>
                <option value="41">佐賀県</option>
                <option value="42">長崎県</option>
                <option value="43">熊本県</option>
                <option value="44">大分県</option>
                <option value="45">宮崎県</option>
                <option value="46">鹿児島県</option>
                <option value="47">沖縄県</option>

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