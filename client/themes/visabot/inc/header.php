<?php
$Read = new Read;
?>
<div class="container main_header">
    <div class="content">
        <header>
            <img title="<?= SITE_NAME; ?>" alt="[<?= SITE_NAME; ?>]" src="<?= INCLUDE_PATH; ?>/images/logoBOT.png"/>
            <h1><?= SITE_NAME; ?></h1>
            <!--<p class="tagline"><?= SITE_SUBNAME; ?></p>-->
        </header>
        <nav>
            <ul>
                <li><a title="Home" href="<?= BASE; ?>">Home</a></li>
                <?php
                $Read->ExeRead(DB_CATEGORIES, "WHERE category_parent IS NULL AND category_id IN(SELECT post_category FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW()) ORDER BY category_title ASC");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Cat):
                        echo "<li><a title='{$Cat['category_title']}' href='" . BASE . "/artigos/{$Cat['category_name']}'>{$Cat['category_title']}</a>";
                        $Read->ExeRead(DB_CATEGORIES, "WHERE category_parent = :ct ORDER BY category_name ASC", "ct={$Cat['category_id']}");
                        if ($Read->getResult()):
                            echo "<ul class='sub'>";
                            foreach ($Read->getResult() as $SubCat):
                                echo "<li><a title='{$SubCat['category_title']}' href='" . BASE . "/artigos/{$SubCat['category_name']}'>{$SubCat['category_title']}</a></li>";
                            endforeach;
                            echo "</ul>";
                        endif;
                        echo "</li>";
                    endforeach;
                endif;

                $Read->FullRead("SELECT page_title, page_name FROM " . DB_PAGES . " WHERE page_status = 1 ORDER BY page_order ASC, page_name ASC");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Page):
                        echo "<li><a title='{$Page['page_title']}' href='" . BASE . "/{$Page['page_name']}'>{$Page['page_title']}</a></li>";
                    endforeach;
                endif;

                if (ACC_MANAGER):
                    echo "<li class='login'>";
                    require '_cdn/widgets/account/account.bar.php';
                    echo "</li>";
                endif;
                ?>
            </ul>
        </nav>
        <div class="clear"></div>
    </div>
</div>