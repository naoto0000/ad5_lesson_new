<?php require_once('header.html'); ?>
<?php require_once('menu.php');?>

<header id="header">
    <h1>社員一覧</h1>
    <?php include(__DIR__ . '/../utilities/navi.html'); ?>
</header>
<main>
    <div class="search">
        <form action="" method="get" id="search">
            <label for="" class="search_label">氏名</label>
                <input type="text" name="search_name" class="input_name" value="<?php  echo $name ?>">
            <label for="" class="search_label">性別</label>
                <select name="search_sex" class="select_sex" value="">
                    <?php 
                        foreach ($sexCotegory as $row){
                            if ($search_sex == $row['value']) {
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            } else {
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                    ?>
                </select>
            <label for="" class="search_label">支店</label>
                <select name="search_branch" class="select_branch" >

                        <option value="">全て</option>

                        <?php 
                            foreach ($branch_row as $branch_name_search){
                                if ($search_branch == $branch_name_search['id']) {
                                    echo '<option value="'. $branch_name_search['id'] .'"selected>' . $branch_name_search['branch_name'] . '</option>';
                                } else {
                                    echo '<option value="'. $branch_name_search['id'] .'">' . $branch_name_search['branch_name'] . '</option>';
                                }
                            }
                        ?>
                </select>
            <input type="submit" name="search_submit" value="検索" class="search_submit">

        </form>
    </div>

    <?php if ($search_count == 0): ?>
        <p class="search_none">該当する社員がいません</p>
    <?php else: ?>
        <table>
            <tr class="table_title">
                <th>氏名</th>
                <th>かな</th>
                <th>支店</th>
                <th class="table_responsive">性別</th>
                <th class="table_responsive">年齢</th>
                <th class="table_responsive">生年月日</th>
                <th> </th>
            </tr>

            <?php require_once('escape.php'); ?>
            
        </table>
    <?php endif; ?>

<!-- 検索結果が５件未満の場合ページネーションを表示させない -->
<?php if ($search_count > 5): ?>

    <?php include(__DIR__ . '/../pagenation/page_display_search.php'); ?>

<?php endif; ?>

</main>
</body>
</html>