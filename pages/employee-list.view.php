<?php require_once('header.html'); ?>
<?php require_once('menu.php'); ?>

<header id="header">
    <h1>社員一覧</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
</header>

<main>

    <div class="search">
        <form action="search.php" method="get" id="search">

            <label for="" class="search_label">氏名</label>
            <input type="text" name="search_name" class="input_name">
            <label for="" class="search_label">性別</label>
            <select name="search_sex" class="select_sex">
                <option value="">全て</option>
                <option value=1>男</option>
                <option value=2>女</option>
                <option value=3>不明</option>
            </select>
            <label for="" class="search_label">支店</label>
            <select name="search_branch" class="select_branch">

                <option value="" selected>全て</option>

                <?php
                foreach ($branch_row as $branch_name_search) {
                    echo '<option value="' . $branch_name_search['id'] . '">' . $branch_name_search['branch_name'] . '</option>';
                }
                ?>
            </select>

            <input type="submit" name="search_submit" value="検索" class="search_submit">

        </form>

    </div>

    <table>
        <tr class="table_title">
            <th>氏名</th>
            <th>かな</th>
            <th>支店</th>
            <th class="table_responsive">性別</th>
            <th class="table_responsive">年齢</th>
            <th class="table_responsive">生年月日</th>
            <th></th>
        </tr>

        <?php require_once('escape.php'); ?>

    </table>

    <?php include(__DIR__ . '/../pagenation/page_display.php'); ?>

</main>
</body>

</html>