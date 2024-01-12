<?php require_once('header.html'); ?>
<?php require_once('menu.php');?>

<header id="header">
    <h1>支店一覧</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
</header>
<main>
    <div class="search">
        <form action="" method="get">
            <label for="">支店名</label>
                <input type="text" name="search_branch_name" class="input_branch_name" value="<?php  echo $branch_name ?>">

            <input type="submit" name="search_submit" value="検索" class="search_submit">
        </form>
    </div>

    <?php if ($search_count == 0): ?>
        <p class="search_none">該当する社員がいません</p>
    <?php else: ?>
        <table class="branch_table">
        <tr class="table_title">
            <th>支店名</th>
            <th>電話番号</th>
            <th>住所</th>
            <th></th>
        </tr>

        <?php require_once('branch_escape.php'); ?>
        
        </table>
    <?php endif; ?>

<!-- 検索結果が５件未満の場合ページネーションを表示させない -->
<?php if ($search_count > 6): ?>

    <?php include(__DIR__ . '/../pagenation/page_display_search.php'); ?>

<?php endif; ?>

</main>
</body>
</html>