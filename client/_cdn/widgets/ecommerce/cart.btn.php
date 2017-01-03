<?php
$Read->FullRead("SELECT stock_id, stock_code FROM " . DB_PDT_STOCK . " WHERE pdt_id = :id", "id={$pdt_id}");
if ($Read->getResult()[0]['stock_code'] == 'default'):
    ?>
    <form class="wc_cart_add" name="cart_add" method="post" enctype="multipart/form-data">
        <input name="pdt_id" type="hidden" value="<?= $pdt_id; ?>"/>
        <input name="stock_id" type="hidden" value="<?= $Read->getResult()[0]['stock_id']; ?>"/>
        <input name="item_amount" type="hidden" value="1"/>
        <button class="btn <?= (!empty($CartBtn) ? $CartBtn : 'btn_green'); ?>"><?= ECOMMERCE_BUTTON_TAG; ?></button>
    </form>
    <?php
else:
    $wcPdtLink = (!empty($pdt_hotlink) ? $pdt_hotlink : BASE . "/produto/{$pdt_name}");
    echo "<a class='wc_cart_add_btn btn " . (!empty($CartBtn) ? $CartBtn : 'btn_green') . "' href='{$wcPdtLink}' title='Ver detalhes de {$pdt_title}'>VER DETALHES</a>";
endif;
?>