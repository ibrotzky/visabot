<?php
if (!$Read):
    $Read = new Read;
endif;

$Email = new Email;

$Read->ExeRead(DB_PAGES, "WHERE page_name = :nm", "nm={$URL[0]}");
if (!$Read->getResult()):
    require REQUIRE_PATH . '/404.php';
    return;
else:
    extract($Read->getResult()[0]);
endif;
?>
<div class="container page_single">
    <div class="content">
        <div class="page_content">
            <header>
                <h1><?= $page_title; ?></h1>
                <p><?= $page_subtitle; ?></p>
            </header>
            <div class="htmlchars">
                <?= $page_content; ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<?php if (APP_COMMENTS && COMMENT_ON_PAGES): ?>
    <div class="container" style="background: #fff; padding: 20px 0;">
        <div class="content">
            <?php
            $CommentKey = $page_id;
            $CommentType = 'page';
            require '_cdn/widgets/comments/comments.php';
            ?>
            <div class="clear"></div>
        </div>
    </div>
<?php endif; ?>