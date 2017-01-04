<section class='workcontrol_order_details'>
    <h1 style="margin: 0 0 20px 0;"><span>&#10003 Pedido #<?= str_pad($order_id, 7, 0, 0); ?></span></h1>
    <article style="margin: 0 0 20px 0;">
        <?php
        $Read = new Read;
        $Read->FullRead("SELECT user_name, user_lastname FROM " . DB_USERS . " WHERE user_id = :oruser", "oruser={$user_id}");
        $User = $Read->getResult()[0];

        $Read->ExeRead(DB_USERS_ADDR, "WHERE addr_id = :oraddr", "oraddr={$order_addr}");
        $Addr = $Read->getResult()[0];
        ?>
        <h1 class="row">Por <?= "{$User['user_name']} {$User['user_lastname']} dia " . date("d/m/Y \a\s H\hi", strtotime($order_date)); ?></h1>
        <p class="row">Envio para: <?= "{$Addr['addr_street']}, {$Addr['addr_number']}, {$Addr['addr_city']}/{$Addr['addr_state']} - {$Addr['addr_zipcode']}"; ?></p>
    </article>
    <article>
        <h1 class="title">Itens do Pedido:</h1>
        <?php
        $SideTotalCart = 0;
        $SideTotalExtra = 0;
        $SideTotalPrice = 0;
        $Read->ExeRead(DB_ORDERS_ITEMS, "WHERE order_id = :orid", "orid={$order_id}");
        if ($Read->getResult()):
            foreach ($Read->getResult() as $SideProduct):
                $Read->FullRead("SELECT stock_code FROM " . DB_PDT_STOCK . " WHERE stock_id = :stid", "stid={$SideProduct['stock_id']}");
                $ProductSize = ($Read->getResult() && $Read->getResult()[0]['stock_code'] != 'default' ? " <b>{$Read->getResult()[0]['stock_code']}</b> " : null);

                echo "<p>";
                $Read->FullRead("SELECT pdt_cover FROM " . DB_PDT . " WHERE pdt_id = :pid", "pid={$SideProduct['pdt_id']}");
                echo "<img title='{$SideProduct['item_name']}' alt='{$SideProduct['item_name']}' src='" . BASE . "/tim.php?src=uploads/{$Read->getResult()[0]['pdt_cover']}&w=" . THUMB_W / 10 . "&h=" . THUMB_H / 10 . "'/>";
                echo "<span>" . Check::Chars($SideProduct['item_name'], 42) . "{$ProductSize}<br>{$SideProduct['item_amount']} * R$ " . number_format($SideProduct['item_price'], '2', ',', '.') . "</span>";
                $SideTotalCart += $SideProduct['item_price'] * $SideProduct['item_amount'];
                echo "</p>";
            endforeach;
        endif;

        $TotalCart = $SideTotalCart;
        ?>
        <div class="workcontrol_order_details_total">
            <div class="wc_cart_total">Sub-total: <b>R$ <span><?= number_format($TotalCart, '2', ',', '.'); ?></span></b></div>
            <?php if ($order_coupon): ?>
                <div class="wc_cart_discount">Desconto: <b><strike>R$ <span><?= number_format($SideTotalCart * ($order_coupon / 100), '2', ',', '.'); ?></span></strike></b></div>
            <?php endif; ?>
            <div class="wc_cart_shiping">Frete: <b>R$ <span><?= number_format($order_shipprice, '2', ',', '.'); ?></span></b></div>
            <div class="wc_cart_price">Total : <b>R$ <span><?= number_format($order_price, '2', ',', '.'); ?></span></b></div>
        </div>
    </article>
</section>