<?php
$AdminLevel = LEVEL_WC_PRODUCTS_ORDERS;
if (!APP_ORDERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

$OrderReset = filter_input(INPUT_GET, 'reset', FILTER_VALIDATE_BOOLEAN);
if ($OrderReset):
    unset($_SESSION['oderCreate']);
    header('Location: dashboard.php?wc=orders/create');
endif;
?>
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-cart">Criar um novo pedido</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=orders/home">Pedidos</a>
            <span class="crumb">/</span>
            Criar pedido
        </p>
    </div>
    <div class="dashboard_header_search">
        <a title="Zerar Pedido" href="dashboard.php?wc=orders/create&reset=true" class="wc_view btn btn_yellow icon-warning jwc_order_app_reset">Zerar Pedido?</a>
    </div>
</header>

<div class="wc_orderapp_finish">
    <div class="wc_orderapp_finish_content">
        <p class="title">FINALIZAR PEDIDO:</p>
        <span class="jwc_orderapp_finish_close workcontrol_pdt_size_close">X</span>
        <div class="box box_finich">
            <p class="subtitle"><span>FRETE:</span></p>
            <ul class="wc_shipment_calculate"></ul>

            <p class="subtitle"><span>DESCONTO:</span></p>
            <input class="jwc_order_create_shipment_cupom" type="number" min="0" max="100" placeholder="Desconto de ??%"/>
            <div class="wc_order_create_finishcart">
                <p>Subtotal: R$&nbsp;<span class="jwc_order_create_shipment_cartprice">0,00</span></p>
                <p>Desconto: R$&nbsp;<span class="jwc_order_create_shipment_cartcupom">0,00</span></p>
                <p><b>Total: R$&nbsp;<span class="jwc_order_create_shipment_carttotal">0,00</span></b></p>
            </div>
            <div style="text-align: right;">
                <span class="btn btn_green jwc_order_create_shipment_ordercreate"><b>CRIAR PEDIDO</b></span>
            </div>
        </div>
        <div class="box box_share" style="display: none;">
            <p class="wc_order_create_success"><span>√</span><br> Pedido criado com sucesso!</p>
            <div class="wc_order_create_success_links">
                <a target="_blank" class="jwc_order_created_link" href="#" title="Pedido">LINK DO PEDIDO!</a>
                <a target="_blank" class="jwc_order_created_pay" href="#" title="Pagamento">LINK PARA PAGAMENTO!</a>
                <span class="jwc_order_created_paytext">LINK</span>
                <p class="wc_order_create_success_info">( ! ) Tudo pronto. Agora você pode enviar o link de pagamento (acima) para o seu cliente finalizar a compra!</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard_content">
    <div class="box box30">

        <div class="panel_header default">
            <h2>CLIENTE:</h2>
        </div>

        <div class="box_content">
            <input class="jwc_ordercreate_name" type="text" name="user_name" placeholder="Nome ou e-mail do cliente:"/>
            <div class="jwc_ordercreate_name_r jwc_createorder_user_result wc_createorder_result">
                <?php
                if (!empty($_SESSION['oderCreate']['user_id'])):
                    $Read->ExeRead(DB_USERS, "WHERE user_id = :id", "id={$_SESSION['oderCreate']['user_id']}");
                    $User = $Read->getResult()[0];
                    echo "<ul class='wc_createorder_user' style='margin-top: 15px;'>
                            <li><label><input checked='checked' class='jwc_ordercreate_addr' type='radio' name='user_id' value='{$User['user_id']}'><p><b>NOME:</b> {$User['user_name']} {$User['user_lastname']}<br><b>E-MAIL:</b> {$User['user_email']}<br><b>CPF:</b> {$User['user_document']}</p></label></li>
                            </ul>";
                endif;
                ?>
            </div>
            <div class="clear"></div>
        </div>

        <article class="m_top" id="addr">
            <div class="panel_header default">
                <h2>ENDEREÇO:</h2>
            </div>
            
            <div class="panel">
                <div class="jwc_ordercreate_name_addr jwc_createorder_addr_result wc_createorder_result">
                    <?php
                    if (!empty($_SESSION['oderCreate']['addr_id'])):
                        $Read->ExeRead(DB_USERS_ADDR, "WHERE addr_id = :id", "id={$_SESSION['oderCreate']['addr_id']}");
                        $Addr = $Read->getResult()[0];
                        echo "<ul class='wc_createorder_user'>
                            <li><label><input checked='checked' class='jwc_ordercreate_addr' type='radio' name='addr_id' value='{$Addr['addr_id']}'><p><b>ENDEREÇO:</b> {$Addr['addr_name']}<br>{$Addr['addr_street']}, {$Addr['addr_number']}<br>{$Addr['addr_district']}, {$Addr['addr_city']}/{$Addr['addr_state']}<br>CEP: {$Addr['addr_zipcode']}</p></label></li>
                            </ul>";
                    else:
                        echo '<p class="jwc_client">Selecione o Cliente...</p>';
                    endif;
                    ?>
                </div>
                <div class="clear"></div>
            </div>
        </article>
    </div>

    <div class="box box70" id="pdts">
        
        <div class="panel_header default">
            <h2>PRODUTOS:</h2>
        </div>
        
        <div class="panel">
            <?php
            if (empty($_SESSION['oderCreate']['addr_id'])):
                echo '<p class="jwc_addr">Selecione o endereço...</p>';
            endif;

            $SearchPDT = (empty($_SESSION['oderCreate']['addr_id']) ? 'display: none;' : 'display: block;');
            ?>

            <input style="margin: 10px 0 0 0; <?= $SearchPDT; ?>" class="jwc_ordercreate_products" type="text" name="product_search" placeholder="Nome ou código do produto:"/>
            <div class="jwc_ordercreate_name_pdt wc_ordercreate_name_pdt">
                <?php
                $Read->FullRead("SELECT stock_id, pdt_id, stock_code, stock_inventory FROM " . DB_PDT_STOCK . " WHERE stock_inventory >= 1 ORDER BY stock_inventory DESC LIMIT 5");
                if (!$Read->getResult()):
                    echo "<div>&nbsp;</div>";
                    Erro("Não existem produtos em estoque!", E_USER_WARNING);
                else:
                    foreach ($Read->getResult() as $Stock):
                        $Read->FullRead("SELECT pdt_title, pdt_cover, pdt_price, pdt_offer_price, pdt_offer_start, pdt_offer_end FROM " . DB_PDT . " WHERE pdt_id = :id", "id={$Stock['pdt_id']}");
                        if ($Read->getResult()):
                            extract($Read->getResult()[0]);

                            $PdtPrice = null;
                            if ($pdt_offer_price && $pdt_offer_start <= date('Y-m-d H:i:s') && $pdt_offer_end >= date('Y-m-d H:i:s')):
                                $PdtPrice = $pdt_offer_price;
                            else:
                                $PdtPrice = $pdt_price;
                            endif;

                            $ForSelect = null;
                            for ($FC = 1; $FC <= $Stock['stock_inventory']; $FC++):
                                $ForSelect .= "<option value='{$FC}'>" . str_pad($FC, 3, 0, 0) . "</option>";
                            endfor;

                            echo "<article class='wc_order_create_item jwc_order_create_add' id='{$Stock['stock_id']}'>
                                    <img src='../tim.php?src=uploads/{$pdt_cover}&w=180' alt='{$pdt_title}' title='{$pdt_title}'/><header>
                                        <h1>{$pdt_title} <span>Tamanho: {$Stock['stock_code']}</span></h1>
                                        <p>R$ " . number_format($PdtPrice, 2, ',', '.') . " - <span>{$Stock['stock_inventory']} em estoque</span></p>
                                    </header><div class='add'>
                                        <select name='pdt_inventory'>{$ForSelect}</select><span class='btn btn_green' id='{$Stock['stock_id']}'><b>ADD</b></span>
                                    </div></article>";
                        endif;
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
        </div>

        <article class="box box100">
            
            <div class="panel_header default">
                <h2>PEDIDO:</h2>
            </div>
            
            <div class="panel">
                <div class="jwc_order_create_cart">
                    <?php
                    if (!empty($_SESSION['oderCreate']['item'])):
                        $CartTotalItems = 0;
                        $CartTotalPrice = 0;
                        foreach ($_SESSION['oderCreate']['item'] as $Pdt => $Qtd):
                            $Read->ExeRead(DB_PDT_STOCK, "WHERE stock_id = :id", "id={$Pdt}");
                            extract($Read->getResult()[0]);

                            $Read->ExeRead(DB_PDT, "WHERE pdt_id = :st", "st={$pdt_id}");
                            extract($Read->getResult()[0]);

                            if ($pdt_offer_price && $pdt_offer_start <= date('Y-m-d H:i:s') && $pdt_offer_end >= date('Y-m-d H:i:s')):
                                $PdtPrice = $pdt_offer_price;
                            else:
                                $PdtPrice = $pdt_price;
                            endif;

                            echo "<article class='item_{$Pdt} wc_ordercreate_itemcart'>
                                    <h1>{$pdt_title} <span>Tamanho: {$stock_code}</span>
                                 </h1><p class='col'>
                                    {$Qtd} x R$ " . number_format($PdtPrice, 2, ',', '.') . "
                                 </p><p class='col'>
                                    R$ " . number_format($PdtPrice * $Qtd, 2, ',', '.') . "</p><p class='col'>
                                    <span id='{$Pdt}' class='btn btn_red jwc_order_create_item_remove'>X</span>
                                  </p></article>";

                            //CART TOTAL
                            $CartTotalItems += $Qtd;
                            $CartTotalPrice += $PdtPrice * $Qtd;
                        endforeach;

                        echo "<div class='wc_ordercreate_totalcart m_top'>";
                        echo "<p>{$CartTotalItems} produtos</p>";
                        echo "<p>Total: R$ " . number_format($CartTotalPrice, 2, ',', '.') . "</p>";
                        echo "<p><span class='btn btn_green jwc_orderapp_finish_order'>FINALIZAR PEDIDO</span></p>";
                        echo "</div>";
                    else:
                        echo '<p class="jwc_pdt">Selecione os produtos...</p>';
                    endif;
                    ?>
                </div>
                <div class="clear"></div>
            </div>
        </article>
    </div>
</div>