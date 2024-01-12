<?php require_once('header.html'); ?>
<?php require_once('menu.php');?>

<header id="header">
    <h1>支店一覧</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
</header>

<main>
    <div class="search">
        <form action="branch_search.php" method="get">

            <label for="">支店名</label>
            <input type="text" name="search_branch_name" class="input_branch_name">

            <input type="submit" name="search_submit" value="検索" class="search_submit">

        </form>
    </div>

    <table class="branch_table">
        <tr class="table_title">
            <th>支店名</th>
            <th>電話番号</th>
            <th>住所</th>
            <th></th>
        </tr>

        <?php require_once('branch_escape.php'); ?>

    </table>

    <?php include(__DIR__ . '/../pagenation/page_display.php'); ?>

</main>
</body>
</html>
