<?php
if ($pdt_inventory >= 1):
    ?>
    <form id="<?= $pdt_id; ?>" class="wc_cart_add" name="cart_add" method="post" enctype="multipart/form-data">
        <input name="pdt_id" type="hidden" value="<?= $pdt_id; ?>"/>
        <?php
        $Read->FullRead("SELECT stock_id, stock_code, stock_inventory FROM " . DB_PDT_STOCK . " WHERE pdt_id = :id AND stock_inventory >= 1", "id={$pdt_id}");
        if ($Read->getResult()):
            $ratioI = 0;
            foreach ($Read->getResult() as $StockVar):
                if ($StockVar['stock_code'] != 'default'):
                    $ratioI ++;
                    echo "<label id='{$StockVar['stock_inventory']}' class='wc_cart_size_select " . ($ratioI == 1 ? "wc_cart_size_select_true" : "") . "'><input " . ($ratioI == 1 ? "checked='checked'" : "") . " type='radio' name='stock_id' value='{$StockVar['stock_id']}'>{$StockVar['stock_code']}</label>";
                else:
                    echo "<input name='stock_id' type='hidden' value='{$StockVar['stock_id']}'/>";
                endif;
            endforeach;
        endif;
        ?>
        <button id="<?= $pdt_id; ?>" class="wc_cart_less cart_more less">-
        </button><input name="item_amount" type="text" value="1" max="<?= $pdt_inventory; ?>"/><button
            id="<?= $pdt_id; ?>" class="wc_cart_plus cart_more plus">+</button>
        <button class="btn <?= (!empty($CartBtn) ? $CartBtn : 'btn_green'); ?>"><?= ECOMMERCE_BUTTON_TAG; ?></button>
    </form>
    <?php
else:
    echo "<span class='wc_cart_outsale'>DESCULPE, PRODUTO FORA DE ESTOQUE!</span>";
endif;

$Read->ExeRead(DB_PDT, "WHERE pdt_status = 1 AND pdt_id != :id AND (pdt_id = :pr OR pdt_parent = :pr OR pdt_parent = :id) AND pdt_inventory >= 1", "pr={$pdt_parent}&id={$pdt_id}");
if ($Read->getResult()):
    echo "<section class='wc_cart_related'><h1>Produtos relacionados:</h1>";
    foreach ($Read->getResult() as $Parent):
        echo "<article class='box box5 wc_related_product'>
            <a href='" . BASE . "/produto/{$Parent['pdt_name']}#mais' title='Ver {$Parent['pdt_title']}'>
                <img src='" . BASE . "/tim.php?src=uploads/{$Parent['pdt_cover']}&w=" . THUMB_W / 3 . "&h=" . THUMB_H / 3 . "' alt='{$Parent['pdt_title']}' title='{$Parent['pdt_title']}'/>
            </a>
            <h1 class='site_title'><a href='" . BASE . "/produto/{$Parent['pdt_name']}#mais' title='Ver {$Parent['pdt_title']}'>{$Parent['pdt_title']}</a></h1>
        </article>";
    endforeach;
    echo "</section>";
endif;