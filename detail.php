<?php 

require_once('function.php');

require_once('not_login.php');
require(__DIR__ . '/entities/employee.php');

$id = $_GET['id'];

$sql = "SELECT e.*, b.branch_name FROM employees AS e INNER JOIN branches as b ON e.branch_id = b.id WHERE e.id = :id AND e.delete_flg is null";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $id,
]);
$employee = new Employee($stmt->fetch());

    require_once('branch_get.php');

    require_once('quali_get.php');

    /**
     * 性別によって枠線変える
     *
     * @param string|null $sex
     * @return string
     */
    function generateBorder(?string $sex): string
    {
        if ($sex === null) {
            return "detail_img";
        }

        $int_sex = (int)$sex;

        if ($int_sex === 1) {
            return "detail_img_man";
        }

        if ($int_sex === 2) {
            return "detail_img_woman";
        }

        return "detail_img";
    }

    /**
     * 日付をフォーマット
     *
     * @param string|null $birthdate
     * @param string $format_type
     * @return string
     */
    function formatDate(?string $birthdate, string $format_type): string
    {
        return date($format_type, strtotime($birthdate));
    }
?>

<?php require_once('header.html'); ?>

    <?php require_once('menu.php');?>

    <header>
        <h1>社員詳細 : <?php echo $employee->name; ?>さん</h1>
    </header>
    
    <main>

        <div class="detail_class">

        <?php if ($employee->image) : ?>
            <img src="img/<?php echo $employee->image; ?>" alt="" class="<?php echo generateBorder($employee->sex); ?>">
        <?php else : ?>
            <img src="img/now_printing.png" alt="" class="<?php echo generateBorder($employee->sex); ?>">
        <?php endif; ?>

            <section>
                <div class="detail_contents">
                    <h2 class="detail_title">氏名</h2>
                    <p class="detail_text"><?php echo $employee->name; ?>(<?php echo $employee->kana; ?>)</p>
                </div>

                <?php if ($employee->birthdate) : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">生年月日</h2>
                    <p class="detail_text"><?php echo formatDate($employee->birthdate, 'Y年m月d日') ?> (<?php echo $employee->age; ?>歳)</p>
                </div>
                <?php endif; ?>

                <div class="detail_contents">
                    <h2 class="detail_title">支店</h2>
                    <p class="detail_text">
                        <?php echo $employee->branch_name; ?>
                    </p>
                </div>

                <?php if ($employee->blood_type) : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">血液型</h2>
                    <p class="detail_text"><?php echo $employee->blood_type_label; ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($employee->quali) : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">資格</h2>
                    <div class="detail_quali">
                        <?php foreach ($quali_masta as $quali_row) : ?>
                            <?php if (in_array($quali_row['id'], $employee->qualies)) : ?>
                                <p class="detail_text"><?php echo $quali_row['quali_name']; ?></p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($employee->prof_text) : ?>
                <div class="detail_contents">
                    <h2 class="detail_title">紹介文</h2>
                    <p class="detail_text"><?php echo $employee->prof_text; ?></p>
                </div>
                <?php endif; ?>

            </section>

        </div>

    </main>

</body>
</html>
