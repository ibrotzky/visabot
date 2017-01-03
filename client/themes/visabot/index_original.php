<?php
if (APP_SLIDE):
    $SlideSeconts = 3;
    require '_cdn/widgets/slide/slide.wc.php';
endif;
?>

<div class="container main_content">
    <div class="content">
        <div class="main_blog">
            <?php
            $Page = (!empty($URL[1]) ? $URL[1] : 1);
            $Pager = new Pager(BASE . "/index/", "<<", ">>", 5);
            $Pager->ExePager($Page, 5);
            $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
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

            $Pager->ExePaginator(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW()");
            echo $Pager->getPaginator();
            ?>
        </div>

        <?php require REQUIRE_PATH . '/inc/sidebar.php'; ?>
        <div class="clear"></div>
    </div>
</div>