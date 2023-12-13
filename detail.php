
<?php 

require_once('function.php');

require_once('not_login.php');

$id = $_GET['id'];

$prof_sql = "SELECT * FROM `employees` WHERE delete_flg IS NULL";
$prof = $pdo->query($prof_sql);

foreach ($prof as $prof_row) {
    if ($id == $prof_row['id']) {
        $prof_birthdate_get = $prof_row['birthdate'];
        $prof_name = $prof_row['name'];       
        $prof_kana = $prof_row['kana'];       
        $prof_sex = $prof_row['sex'];       
        $prof_img =  $prof_row['image'];
        $prof_text =  $prof_row['prof_text'];
        $prof_branch_id =  $prof_row['branch_id'];        
        $prof_blood =  $prof_row['blood_type'];        
        $prof_quali =  $prof_row['quali'];        
    }
}

sscanf($prof_birthdate_get, "%d-%d-%d", $year, $month, $day);

    // 現在日付
    $now = date('Ymd');

    // 誕生日
    $birthday = $prof_birthdate_get ;
    $birthday = str_replace("-", "", $birthday);

    // 年齢
    $age = floor(($now - $birthday) / 10000);

    $blood_type = "";

    switch ($prof_blood) {  
        case 1:
            $blood_type = "A型";
            break;
        case 2:
            $blood_type = "B型";
            break;
        case 3:
            $blood_type = "AB型";
            break;
        case 4:
            $blood_type = "O型";
            break;
        case 5:
            $blood_type = "不明";
            break;

    }

    require_once('branch_get.php');

    require_once('quali_get.php');

?>

<?php require_once('header.html'); ?>

    <?php require_once('menu.php');?>

    <header>
        <h1>社員詳細 : <?php echo $prof_name; ?>さん</h1>
    </header>
    
    <main>

        <div class="detail_class">

        <!-- 性別によって枠線の色を変えている -->
        <?php if ($prof_sex == 1) : ?>    
            <?php if ($prof_img !== "") : ?>
                <img src="img/<?php echo $prof_img; ?>" alt="" class="detail_img_man">
            <?php else : ?>
                <img src="img/now_printing.png" alt="" class="detail_img_man">
            <?php endif; ?>

        <?php elseif ($prof_sex == 2) : ?>
            <?php if ($prof_img !== "") : ?>
                <img src="img/<?php echo $prof_img; ?>" alt="" class="detail_img_woman">
            <?php else : ?>
                <img src="img/now_printing.png" alt="" class="detail_img_woman">
            <?php endif; ?>

        <?php else : ?>
            <?php if ($prof_img !== "") : ?>
                <img src="img/<?php echo $prof_img; ?>" alt="" class="detail_img">
            <?php else : ?>
                <img src="img/now_printing.png" alt="" class="detail_img">
            <?php endif; ?>

        <?php endif ; ?>

            <section>
                <div class="detail_contents">
                    <h2 class="detail_title">氏名</h2>
                    <p class="detail_text"><?php echo $prof_name; ?>(<?php echo $prof_kana; ?>)</p>
                </div>

                <?php if (!empty($prof_birthdate_get)) : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">生年月日</h2>
                    <p class="detail_text"><?php echo $year; ?>年<?php echo $month; ?>月<?php echo $day; ?>日 (<?php echo $age; ?>歳)</p>
                </div>
                <?php endif; ?>

                <div class="detail_contents">
                    <h2 class="detail_title">支店</h2>
                    <p class="detail_text">
                        <?php foreach ($branch_row as $branch_name_detail) {
                            if ($branch_name_detail['id'] === $prof_branch_id) {
                                echo $branch_name_detail['branch_name'];
                            }
                        } ?>
                    </p>
                </div>

                <?php if (isset($prof_blood)) : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">血液型</h2>
                    <p class="detail_text"><?php echo $blood_type; ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($prof_quali)) : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">資格</h2>
                    <div class="detail_quali">
                        <?php foreach ($quali_masta as $quali_row) : ?>
                            <?php if (1 == preg_match('/' . preg_quote($quali_row['id'], '/') . '/', $prof_quali)) : ?>
                                <p class="detail_text"><?php echo $quali_row['quali_name']; ?></p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($prof_text !== "") : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">紹介文</h2>
                    <p class="detail_text"><?php echo $prof_text; ?></p>
                </div>
                <?php endif; ?>

            </section>

        </div>

    </main>

</body>
</html>
