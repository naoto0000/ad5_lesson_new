<?php require_once('header.html'); ?>
<?php require_once('menu.php');?>

<header id="header">
    <h1>資格マスタ</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
</header>

<main>

    <form action="" method="post">

        <div class="quali">
            <table class="quali_table">
                <tr class="table_title">
                    <th class="table_id">ID</th>
                    <th class="quali_table_name">資格名</th>
                </tr>

            <?php foreach ($quali_masta as $index => $quali) : ?>
                <tr class="table_contents  quali_title">
                    <input type="hidden" name="quali[<?php echo $index ?>][id]" value="<?php echo $quali['id']; ?>">
                    <td class="table_id quali_id"><?php echo $quali['id']; ?></td>
                    <td class="quali_table_name"><input type="text" name="quali[<?php echo $index ?>][quali_name]" value="<?php echo $quali['quali_name']; ?>" class="quali_input"></td>
                </tr>
            <?php endforeach ; ?>

                <tr class="table_contents  quali_title">
                    <td class="table_id quali_id"></td>
                    <td class="quali_table_name"><input type="text" name="quali_new" value="" class="quali_input"></td>
                </tr>

            </table>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="quali_submit" value="保存" class="regi_submit quali_submit">

    </form>    

</main>
</body>
</html>