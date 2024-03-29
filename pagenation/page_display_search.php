<!-- ここからページネーション設定 -->
<p class="from_to"><?php echo $search_count ?>件中 <?php echo $from_record; ?> - <?php echo $to_record; ?> 件目を表示</p>

<!-- 戻るボタン -->
<div class="pagenation">

    <?php if ($page >= 2) : ?>
        <!-- ここで検索結果のデータを２ページ以降に渡している -->
        <a href="?page=<?php echo ($page - 1); ?>&search_name=<?php echo $name; ?> 
    &search_sex=<?php echo $search_sex; ?>&search_branch=<?php echo $search_branch; ?>" class="page_feed">&laquo;</a>

    <?php else : ?>
        <span class="first_last_page">&laquo;</span>

    <?php endif; ?>

    <!-- ページ選択 -->
    <?php for ($i = 1; $i <= $max_page; $i++) : ?>
        <?php if ($i >= $page - $range && $i <= $page + $range) : ?>
            <?php if ($i == $page) : ?>
                <span class="now_page_number"><?php echo $i; ?></span>
            <?php else : ?>
                <a href="?page=<?php echo $i; ?>&search_name=<?php echo $name; ?>
        &search_sex=<?php echo $search_sex; ?>&search_branch=<?php echo $search_branch; ?>" class="page_number"><?php echo $i; ?></a>

            <?php endif; ?>
        <?php endif; ?>
    <?php endfor; ?>

    <!-- 進むボタン -->
    <?php if ($page < $max_page) : ?>
        <a href="?page=<?php echo ($page + 1); ?>&search_name=<?php echo $name; ?>
    &search_sex=<?php echo $search_sex; ?>&search_branch=<?php echo $search_branch; ?>" class="page_feed">&raquo;</a>

    <?php else : ?>
        <span class="first_last_page">&raquo;</span>
    <?php endif; ?>

</div>