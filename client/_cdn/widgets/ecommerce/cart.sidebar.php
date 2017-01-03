<?php
if (!empty($_SESSION['wc_order'])):
    $OderDetail = $_SESSION['wc_order'];
    $OderCupom = (!empty($_SESSION['wc_cupom']) ? $_SESSION['wc_cupom'] : null);
    ?><section class='workcontrol_order_details'>
        <h1><span>Resumo do pedido:</span></h1>
        <?php
        $SideTotalCart = 0;
        foreach ($OderDetail as $SideItemId => $SideItemAmount):
            $Read->ExeRead(DB_PDT, "WHERE pdt_id = (SELECT pdt_id FROM " . DB_PDT_STOCK . " WHERE stock_id = :id)", "id={$SideItemId}");
            if ($Read->getResult()[0]):
                $SideProduct = $Read->getResult()[0];
                $SideProductPrice = ($SideProduct['pdt_offer_price'] && $SideProduct['pdt_offer_start'] <= date('Y-m-d H:i:s') && $SideProduct['pdt_offer_end'] >= date('Y-m-d H:i:s') ? $SideProduct['pdt_offer_price'] : $SideProduct['pdt_price']);

                $Read->FullRead("SELECT stock_code FROM " . DB_PDT_STOCK . " WHERE stock_id = :stid", "stid={$SideItemId}");
                $ProductSize = ($Read->getResult() && $Read->getResult()[0]['stock_code'] != 'default' ? " <b>{$Read->getResult()[0]['stock_code']}</b> " : null);

                echo "<p>";
                echo "<img title='{$SideProduct['pdt_title']}' alt='{$SideProduct['pdt_title']}' src='" . BASE . "/tim.php?src=uploads/{$SideProduct['pdt_cover']}&w=" . THUMB_W / 10 . "&h=" . THUMB_H / 10 . "'/>";
                echo "<span>" . Check::Chars($SideProduct['pdt_title'], 42) . "{$ProductSize}<br>{$SideItemAmount} * R$ " . number_format($SideProductPrice, '2', ',', '.') . "</span>";
                echo "</p>";
                $SideTotalCart += $SideProductPrice * $SideItemAmount;
            endif;
        endforeach;

        $SideTotalPrice = (!empty($_SESSION['wc_cupom']) ? $SideTotalCart * ((100 - $_SESSION['wc_cupom']) / 100) : $SideTotalCart);
        ?>
        <div class="workcontrol_order_details_total">
            <div class="wc_cart_total">Sub-total: <b>R$ <span><?= number_format($SideTotalCart, '2', ',', '.'); ?></span></b></div>
            <?php if ($OderCupom): ?>
                <div class="wc_cart_discount">Desconto: <b><strike>R$ <span><?= number_format($SideTotalCart * ($OderCupom / 100), '2', ',', '.'); ?></span></strike></b></div>
            <?php endif; ?>
            <div class="wc_cart_shiping">Frete: <b>R$ <span><?= number_format((!empty($_SESSION['wc_shipment']['wc_shipprice']) ? $_SESSION['wc_shipment']['wc_shipprice'] : 0), '2', ',', '.'); ?></span></b></div>
            <div class="wc_cart_price">Total: <b>R$ <span><?= number_format((!empty($_SESSION['wc_shipment']['wc_shipprice']) ? $SideTotalPrice + $_SESSION['wc_shipment']['wc_shipprice'] : $SideTotalPrice), '2', ',', '.'); ?></span></b></div>
        </div>
    </section><?php



endif;