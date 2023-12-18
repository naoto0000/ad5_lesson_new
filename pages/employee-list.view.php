<?php require_once('header.html'); ?>

<?php require_once('menu.php');?>

<header>
    <h1>社員一覧</h1>
</header>

<main>
    
    <div class="search">
        <form action="search.php" method="get">

            <label for="">氏名</label>
            <input type="text" name="search_name" class="input_name">
            <label for="">性別</label>
            <select name="search_sex" class="select_sex">
                <option value="">全て</option>
                <option value="1">男</option>
                <option value="2">女</option>
                <option value="3">不明</option>
            </select>
            <label for="">支店</label>
            <select name="search_branch" class="select_branch" >

                    <option value="" selected>全て</option>

                    <?php 
                        foreach ($branch_row as $branch_name_search){
                            echo '<option value="'. $branch_name_search['id'] .'">' . $branch_name_search['branch_name'] . '</option>';
                        }
                    ?>
            </select>

            <input type="submit" name="search_submit" value="検索" class="search_submit">

            <input type="submit" name="csv_submit" value="CSVダウンロード" class="csv_submit">
            
            <a href="csv_import.php"><input type="button" name="csv_import" value="CSVインポート" class="csv_submit"></a>

        </form>

    </div>

    <table>
        <tr class="table_title">
            <th>氏名</th>
            <th>かな</th>
            <th>支店</th>
            <th>性別</th>
            <th>年齢</th>
            <th>生年月日</th>
            <th></th>
        </tr>

        <?php require_once('escape.php'); ?>

    </table>

    <?php require_once('page_display.php'); ?>

</main>
</body>
</html>