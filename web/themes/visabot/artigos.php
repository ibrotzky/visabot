<?php
if (!$Read):
    $Read = new Read;
endif;

$Read->ExeRead(DB_CATEGORIES, "WHERE category_name = :nm", "nm={$URL[1]}");
if (!$Read->getResult()):
    require REQUIRE_PATH . '/404.php';
    return;
else:
    extract($Read->getResult()[0]);
endif;
?>
<div class="container main_content">
    <div class="content">
        <div class="main_blog">
            <?php
            $Page = (!empty($URL[2]) ? $URL[2] : 1);
            $Pager = new Pager(BASE . "/artigos/{$category_name}/", "<<", ">>", 5);
            $Pager->ExePager($Page, 5);
            $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND (post_category = :ct OR FIND_IN_SET(:ct, post_category_parent)) ORDER BY post_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&ct={$category_id}");
            if (!$Read->getResult()):
                $Pager->ReturnPage();
                echo Erro("Ainda Não existe posts cadastrados. Favor volte mais tarde :)", E_USER_NOTICE);
            else:
                foreach ($Read->getResult() as $Post):
                    extract($Post);
                    ?>
                    <article class="main_blog_post">
                        <a title="Ler mais sobre <?= $post_title; ?>" href="<?= BASE; ?>/artigo/<?= $post_name; ?>">
                            <img title="<?= $post_title; ?>" alt="<?= $post_title; ?>" src="<?= BASE; ?>/uploads/<?= $post_cover; ?>"/>
                        </a>
                        <header>
                            <h1><a title="Ler mais sobre <?= $post_title; ?>" href="<?= BASE; ?>/artigo/<?= $post_name; ?>"><?= $post_title; ?></a></h1>
                            <p class="tagline"><?= $post_subtitle; ?></p>
                        </header>
                    </article>
                    <?php
                endforeach;
            endif;

            $Pager->ExePaginator(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND (post_category = :ct OR FIND_IN_SET(:ct, post_category_parent))", "ct={$category_id}");
            echo $Pager->getPaginator();
            ?>
        </div>

        <?php require REQUIRE_PATH . '/inc/sidebar_cat.php'; ?>
        <div class="clear"></div>
    </div>
</div>