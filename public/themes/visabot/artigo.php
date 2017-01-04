<?php
if (!$Read):
    $Read = new Read;
endif;

$Read->ExeRead(DB_POSTS, "WHERE post_name = :nm AND post_date <= NOW()", "nm={$URL[1]}");
if (!$Read->getResult()):
    require REQUIRE_PATH . '/404.php';
    return;
else:
    extract($Read->getResult()[0]);
    $Update = new Update;
    $UpdateView = ['post_views' => $post_views + 1, 'post_lastview' => date('Y-m-d H:i:s')];
    $Update->ExeUpdate(DB_POSTS, $UpdateView, "WHERE post_id = :id", "id={$post_id}");
    
    $Read->ExeRead(DB_CATEGORIES, "WHERE category_id = :cat", "cat={$post_category}");
    if($Read->getResult()):
        $Category = $Read->getResult()[0];
    endif;
endif;
?>
<div class="container post_single">
    <div class="content">
        <div class="left_content">
            <div class="post_content">
                <h1><?= $post_title; ?></h1>
                <?php
                if ($post_video):
                    echo "<div class='embed-container'>";
                    echo "<iframe id='mediaview' width='640' height='360' src='https://www.youtube.com/embed/{$post_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe>";
                    echo "</div>";
                else:
                    echo "<img class='cover' title='{$post_title}' alt='{$post_title}' src='" . BASE . "/uploads/{$post_cover}'/>";
                endif;
                ?>

                <?php
                $WC_TITLE_LINK = $post_title;
                $WC_SHARE_HASH = "BoraEmpreender";
                $WC_SHARE_LINK = BASE . "/artigo/{$post_name}";
                require './_cdn/widgets/share/share.wc.php';
                ?>
                <h2 class="tagline"><?= $post_subtitle; ?></h2>
                <div class="htmlchars">
                    <?= $post_content; ?>
                </div>
                <?php
                require './_cdn/widgets/share/share.wc.php';

                $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND post_category_parent = :ct AND post_id != :id ORDER BY post_date DESC LIMIT 4", "ct={$post_category_parent}&id={$post_id}");
                if ($Read->getResult()):
                    echo '<section class="single_post_more">';
                    echo '<header>';
                    echo '<h1>Veja Também:</h1>';
                    echo '<p>Artigos Relacionados</p>';
                    echo '</header>';

                    foreach ($Read->getResult() as $More):
                        ?>
                        <article class="single_post_more_post">
                            <a title="Ler mais sobre <?= $More['post_title']; ?>" href="<?= BASE; ?>/artigo/<?= $More['post_name']; ?>">
                                <img title="<?= $More['post_title']; ?>" alt="<?= $More['post_title']; ?>" src="<?= BASE; ?>/tim.php?src=uploads/<?= $More['post_cover']; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>"/>
                            </a>
                        </article>
                        <?php
                    endforeach;
                    echo '</section>';
                endif;
                ?>
                <div class="clear"></div>
            </div>
        </div>

<?php require REQUIRE_PATH . '/inc/sidebar_post.php'; ?>
        <div class="clear"></div>
    </div>
</div>

<?php if (APP_COMMENTS && COMMENT_ON_POSTS): ?>
    <div class="container" style="background: #fff; padding: 20px 0;">
        <div class="content">
            <?php
            $CommentKey = $post_id;
            $CommentType = 'post';
            require '_cdn/widgets/comments/comments.php';
            ?>
            <div class="clear"></div>
        </div>
    </div>
<?php endif; ?>