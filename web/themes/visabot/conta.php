<?php
if (!$Read):
    $Read = new Read;
endif;

if (!ACC_MANAGER):
    require REQUIRE_PATH . '/404.php';
else:
    ?>
    <div class="container post_single" id="acc">
        <div class="content">
            <?php require '_cdn/widgets/account/account.php'; ?>
            <div class="clear"></div>
        </div>
    </div>
<?php
endif;