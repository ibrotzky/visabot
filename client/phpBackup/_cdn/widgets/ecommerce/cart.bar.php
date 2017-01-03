<a href="<?= BASE; ?>/pedido/home#cart" title="Fechar Compra!" class="wc_cart_list">
    (<span class="cart_count"><?= (!empty($_SESSION['wc_order']) ? count($_SESSION['wc_order']) : '0'); ?></span>) <?= ECOMMERCE_TAG; ?>
</a>