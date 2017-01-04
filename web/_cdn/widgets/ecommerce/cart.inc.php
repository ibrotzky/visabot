<link rel="stylesheet" href="<?= BASE; ?>/_cdn/widgets/ecommerce/cart.css"/>
<script src="<?= BASE; ?>/_cdn/widgets/ecommerce/cart.js"></script>

<div class="wc_cart_callback"></div>


<div class="wc_cart_manager">
    <div class="wc_cart_manager_content"> 
        <div class="wc_cart_manager_header"><?= ECOMMERCE_TAG; ?></div>
        <div class="wc_cart_manager_info">Você adicionou <b></b> a sua lista de compras. O que deseja fazer agora?</div>
        <div class="wc_cart_manager_actions">
            <span class="wc_cart_close btn btn_blue">Continuar Comprando!</span>
            <a class="wc_cart_finish btn btn_green" title="Concluir Compra!" href="<?= BASE; ?>/pedido/home#cart">Fechar Compra!</a>
            <div class="clear"></div>
        </div>
    </div>
</div>