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

$OrderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($OrderId):
    $Read->ExeRead(DB_ORDERS, "WHERE order_id = :id", "id={$OrderId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
        $Traking = ($order_shipcode < 40000 ? ECOMMERCE_SHIPMENT_COMPANY_LINK : 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=');
    else:
        $_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um pedido que não existe ou que foi removido recentemente!";
        header('Location: dashboard.php?wc=orders/home');
    endif;
else:
    $Date = date('Y-m-d H:i:s');
    $Price = rand(1000, 5000);
    $PayMent = rand(1, 5);
    $Status = rand(1, 6);
    $OrderCreate = ['user_id' => $_SESSION['userLogin']['user_id'], 'order_status' => $Status, 'order_price' => $Price, 'order_payment' => $PayMent, 'order_date' => $Date];
    $Create->ExeCreate(DB_ORDERS, $OrderCreate);
    header('Location: dashboard.php?wc=orders/order&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-new-tab">Gerenciar Pedido</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=orders/home">Pedidos</a>
            <span class="crumb">/</span>
            Pedido
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;" id="<?= $OrderId; ?>">
        <span <?= ($order_status > 2 ? '' : 'style="display: none"'); ?> class='j_order_cancel icon-warning btn btn_yellow' id='<?= $OrderId; ?>'>Cancelar Pedido!</span>
        <span <?= ($order_status == 2 ? '' : 'style="display: none"'); ?> rel='dashboard_header_search' class='j_delete_action icon-warning btn btn_red' id='<?= $OrderId; ?>'>Deletar Pedido!</span>
        <span rel='dashboard_header_search' callback='Orders' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='<?= $OrderId; ?>'>EXCLUIR AGORA!</span>
    </div>
</header>

<div class="dashboard_content">
    <form name="post_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Orders"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="order_id" value="<?= $OrderId; ?>"/>

        <div class="box box30">
            <div class="panel_header default">
                <h2>Dados do Cliente:</h2>
            </div>

            <?php
            $Read->ExeRead(DB_USERS, "WHERE user_id = :puser", "puser={$user_id}");
            if ($Read->getResult()):
                $OrderClient = $Read->getResult()[0];
                $OrderClientPhone = preg_replace("/[^0-9]/", "", $OrderClient['user_telephone']);

                $Read->FullRead("SELECT order_id FROM " . DB_ORDERS . " WHERE user_id = :id", "id={$user_id}");
                $OrderCount = $Read->getRowCount();
            endif;
            ?>

            <div class="panel">
                <p class="single_order_userdata icon-user"><?= "{$OrderClient['user_name']} {$OrderClient['user_lastname']}"; ?><a title="Ver Cliente" href="dashboard.php?wc=users/create&id=<?= $OrderClient['user_id']; ?>" class="icon-notext icon-arrow-right"></a></p>
                <p class="single_order_userdata icon-calendar">Cliente Desde <?= date('d/m/Y', strtotime($OrderClient['user_registration'])); ?></p>
                <p class="single_order_userdata icon-cart">Possui <?= str_pad($OrderCount, 2, 0, STR_PAD_LEFT); ?> pedido(s) no site</p>
                <p class="single_order_userdata icon-mail4"><?= substr($OrderClient['user_email'], 0, 29); ?> <a title="Enviar E-mail" href="mailto:<?= $OrderClient['user_email']; ?>" class="icon-notext icon-mail"></a></p>
                <p class="single_order_userdata icon-phone"><?= $OrderClient['user_telephone']; ?> <a title="Ligar para Cliente" href="callto:<?= $OrderClientPhone; ?>" class="icon-notext icon-phone-hang-up"></a></p>


                <h2 class="icon-location2 m_botton">Mail Tag:</h2>
                
                <blockquote>
                    <?php
                    $Read->FullRead("SELECT pdt_id, stock_id, item_name, item_amount FROM " . DB_ORDERS_ITEMS . " WHERE order_id = :order", "order={$OrderId}");
                    if ($Read->getResult()):
                        echo "<b style='font-size: 1.2em; font-weight: 400'>√ PEDIDO: " . str_pad($order_id, 7, 0, STR_PAD_LEFT) . "</b><br>";
                        foreach ($Read->getResult() as $Items):
                            $Read->FullRead("SELECT stock_code FROM " . DB_PDT_STOCK . " WHERE stock_id = :stid", "stid={$Items['stock_id']}");
                            $ProductSize = ($Read->getResult() && $Read->getResult()[0]['stock_code'] != 'default' ? " ({$Read->getResult()[0]['stock_code']})" : null);

                            $Items['item_name'] = Check::Chars($Items['item_name'], 20);
                            $Read->FullRead("SELECT pdt_code FROM " . DB_PDT . " WHERE pdt_id = :pdt", "pdt={$Items['pdt_id']}");
                            if ($Read->getResult()):
                                echo "[&nbsp;&nbsp;] {$Items['item_amount']} - ({$Read->getResult()[0]['pdt_code']}) {$Items['item_name']}{$ProductSize}<br>";
                            else:
                                echo "[&nbsp;&nbsp;] {$Items['item_amount']} - {$Items['item_name']}{$ProductSize}<br>";
                            endif;
                        endforeach;
                        echo "[&nbsp;&nbsp;] 1 - ({$order_shipcode}) " . getShipmentTag($order_shipcode);
                    endif;
                    ?>
                </blockquote>
                <br>

                <blockquote>
                    <b style='font-size: 1.2em; font-weight: 400;'>REMETENTE:</b><br>
                    <?php
                    echo SITE_ADDR_NAME . "<br>
                      Fone: " . SITE_ADDR_PHONE_A . "<br>
                      " . SITE_ADDR_ADDR . " <br>
                      B. " . SITE_ADDR_DISTRICT . ", " . SITE_ADDR_CITY . "/" . SITE_ADDR_UF . "<br>
                      Cep: " . SITE_ADDR_ZIP;
                    ?>
                </blockquote>
                <br>

                <?php
                $Read->ExeRead(DB_USERS_ADDR, "WHERE addr_id = :order_addr", "order_addr={$order_addr}");
                if (!$Read->getResult()):
                    echo Erro("<span class='icon-warning'>OPPSSS:</span> Não foi possível ler o endereço de envio para esse pedido!", E_USER_ERROR);
                else:
                    extract($Read->getResult()[0]);
                    $addr_complement = ($addr_complement ? " ({$addr_complement})" : null);
                    echo "<blockquote>"
                    . "<b style='font-size: 1.2em; font-weight: 400;'>DESTINATÁRIO:</b><br>"
                    . "{$OrderClient['user_name']} {$OrderClient['user_lastname']}<br>"
                    . "Fone: {$OrderClient['user_cell']}<br>"
                    . "{$addr_street}, {$addr_number}{$addr_complement}<br>"
                    . "B. {$addr_district}, {$addr_city}/{$addr_state}<br>"
                    . "Cep: {$addr_zipcode}"
                    . "</blockquote>";
                endif;
                ?>
                <div class="clear"></div>
            </div>
        </div>

        <article class="box box70">
            <div class="panel">
                <div class="label_50">
                    <label class="label">
                        <span class="legend">Pedido:</span>
                        <p style="font-size: 1.3em;" class="input icon-cart"><?= str_pad($order_id, 7, 0, STR_PAD_LEFT); ?></p>
                    </label>

                    <label class="label">
                        <span class="legend">Status:</span>
                        <p style="font-size: 1.3em;" class="input icon-history j_statustext"><?= getOrderStatus($order_status); ?></p>
                    </label>
                </div>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Data:</span>
                        <p style="font-size: 1.3em;" class="input icon-calendar"><?= date('d/m/Y H\hi', strtotime($order_date)); ?></p>
                    </label>

                    <label class="label">
                        <span class="legend">Valor:</span>
                        <p style="font-size: 1.3em;" class="input icon-coin-dollar">R$ <?= number_format($order_price, '2', ',', '.'); ?></p>
                    </label>
                </div>

                <label class="label_50">
                    <label class="label">
                        <span class="legend">Pagamento:</span>
                        <p style="font-size: 1.3em;" class="input icon-coin-dollar"><?php
                            echo "Total pago: R$ ";
                            if ($order_installment):
                                echo number_format($order_installments * $order_installment, '2', ',', '.') . " em " . str_pad($order_installments, 2, 0, 0) . "x de R$ " . number_format($order_installment, '2', ',', '.');
                            else:
                                echo number_format($order_price, '2', ',', '.');
                            endif;
                            ?></p>
                    </label>

                    <label class="label">
                        <span class="legend">NFE:</span>
                        <p style="font-size: 1.3em;" class="input icon-clipboard wc_nfe"><span class="wc_nfe_pdf"><?= $order_nfepdf ? "<a target='_blank' href='../uploads/{$order_nfepdf}' title='Ver PDF'>Ver PDF</a>" : "Enviar PDF"; ?></span>, <span class="wc_nfe_xml"><?= $order_nfexml ? "<a target='_blank' href='../uploads/{$order_nfexml}' title='Ver XML'>Ver XML</a>" : "Enviar XML"; ?></span></p>
                    </label>
                </label>

                <div class="clear"></div>
                <p class="section icon-clipboard" style="margin-bottom: 15px;">Produtos:</p>
                <div class="single_order_items">
                    <?php
                    $Read->ExeRead(DB_ORDERS_ITEMS, "WHERE order_id = :order", "order={$order_id}");
                    if (!$Read->getResult()):
                        echo Erro("<span class='icon-info'>Não foram selecionados produtos no pedido atual!</span>", E_USER_NOTICE);
                    else:
                        $i = 0;
                        $ItemsPrice = 0;
                        $ItemExtra = 0;
                        $ItemsAmount = 0;
                        $OrderDiscount = 0;
                        foreach ($Read->getResult() as $Item):
                            $Read->FullRead("SELECT stock_code FROM " . DB_PDT_STOCK . " WHERE stock_id = :stid", "stid={$Item['stock_id']}");
                            $ProductSize = ($Read->getResult() && $Read->getResult()[0]['stock_code'] != 'default' ? " - <b>{$Read->getResult()[0]['stock_code']}</b>" : null);

                            $i++;
                            echo "<div class='single_order_items_item'>";
                            if ($Item['pdt_id']):
                                echo "<p><b>" . str_pad($Item['item_id'], 5, 0, STR_PAD_LEFT) . "</b> - <a target='_blanc' href='dashboard.php?wc=products/create&id={$Item['pdt_id']}' title='Ver Produto!'>" . Check::Words($Item['item_name'], 4) . "{$ProductSize}</a></p>";
                                $ItemsPrice += $Item['item_amount'] * $Item['item_price'];
                            else:
                                echo "<p><b>" . str_pad($Item['item_id'], 5, 0, STR_PAD_LEFT) . "</b> - " . Check::Words($Item['item_name'], 4) . "</p>";
                                $ItemExtra += $Item['item_amount'] * $Item['item_price'];
                            endif;
                            echo "<p>R$ " . number_format($Item['item_price'], '2', ',', '.') . " * <b>{$Item['item_amount']}</b></p>";
                            echo "<p>R$ " . number_format($Item['item_amount'] * $Item['item_price'], '2', ',', '.') . "</p>";
                            echo "</div>";
                            $ItemsAmount += $Item['item_amount'];
                        endforeach;

                        if ($order_coupon):
                            echo "<div class='single_order_items_item'>";
                            echo "<p>Cupom de Desconto: </p>";
                            echo "<p>{$order_coupon}%</p>";
                            echo "<p>- R$ " . number_format($ItemsPrice * ($order_coupon / 100), '2', ',', '.') . "</p>";
                            echo "</div>";
                        endif;

                        if ($order_shipcode):
                            $order_shipprice = ($order_shipprice ? $order_shipprice : 0);
                            echo "<div class='single_order_items_item'>";
                            echo "<p><b>{$order_shipcode}</b> - Frete via " . getShipmentTag($order_shipcode) . ": </p>";
                            echo "<p>R$ " . number_format($order_shipprice, '2', ',', '.') . " <b>* 1</b></p>";
                            echo "<p>R$ " . number_format($order_shipprice, '2', ',', '.') . "</p>";
                            echo "</div>";
                        endif;

                        echo "<div class='single_order_items_item total'>";
                        echo "<p>{$i} Produto(s) no pedido</p>";
                        echo "<p>{$ItemsAmount} Itens</p>";
                        echo "<p>R$ " . number_format($order_price, '2', ',', '.') . "</p>";
                        echo "</div>";

                        if ($order_free):
                            echo "<div class='single_order_items_item'><b>";
                            echo "<p>Taxas de Pagamento:</p>";
                            echo "<p class='font_red'>- R$ " . number_format($order_free, '2', ',', '.') . "</p>";
                            echo "<p class='font_green'>R$ " . number_format($order_price - $order_free, '2', ',', '.') . "</p>";
                            echo "</b></div>";
                        endif;
                    endif;
                    ?>
                </div>

                <div class="clear"></div>
                <p class="section icon-truck" style="margin-bottom: 15px;">Gerenciar Pedido:</p>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Método de Pagamento:</span>
                        <select name="order_payment" required>
                            <option selected="selected" value="<?= $order_payment; ?>">&checkmark; <?= getOrderPayment($order_payment); ?></option>
                            <?php
                            $PayMethod = getOrderPayment();
                            foreach ($PayMethod as $PayMethodId => $PayMethod):
                                echo "<option value='{$PayMethodId}'>&raquo; {$PayMethod}</option>";
                            endforeach;
                            ?>
                        </select>
                    </label>

                    <label class="label">
                        <span class="legend">Status do Pedido:</span>
                        <select name="order_status" required>
                            <option selected="selected" value="<?= $order_status; ?>">&checkmark; <?= getOrderStatus($order_status); ?></option>
                            <option value="6">&raquo; Processando</option>
                            <option value="1">&raquo; Concluído</option>
                        </select>
                    </label>
                </div>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Data de envio:</span>
                        <input type="date" name="order_shipment" value="<?= $order_shipment ? date('Y-m-d', strtotime($order_shipment)) : date('Y-m-d'); ?>"/>
                    </label>

                    <label class="label">
                        <span class="legend icon-truck single_order_traking j_content"><?= $order_tracking && $order_tracking != 1 ? "<a title='Rastrear Pedido' target='_blanck' href='{$Traking}{$order_tracking}'>RASTREIO:</a>" : 'RASTREIO: (informe <b>1</b> para enviar sem rastreio!'; ?></span>
                        <input type="text" name="order_tracking" value="<?= $order_tracking; ?>" placeholder="SS987654321XX"/>
                    </label>
                </div>

                <label class="label">
                    <span class="legend">NFE (Nota + XML)</span>
                    <input type="file" multiple="multiple" name="order_nfe[]" accept="application/xml, application/pdf, text/xml" value="<?= $order_tracking; ?>" placeholder="Selecione a nota e o XML"/>
                </label>

                <label class="label_check"><input style="margin-top: -1px;" type="checkbox" value="1" name="post_mail" checked> Enviar e-mails de aviso!</label>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar Pedido!</button>

                <div class="clear"></div>
            </div>
        </article>
    </form>

    <article class="box box50">
        <div class="panel">
            <p>
                <b class="icon-history" style="color: #00B594">PROCESSANDO: </b>
                Significa que o pagamento do pedido já foi identificado e o mesmo está em processo de envio. O cliente é avisado por e-mail!
            </p>
        </div>
    </article>

    <article class="box box50">
        <div class="panel">
            <p>
                <b class="icon-history" style="color: #00B594">CONCLUÍDO: </b>
                Significa que você já enviou o pedido para entrega. Informe uma data de postagem e um reastreio para avisar o cliente por e-mail!
            </p>
        </div>
    </article>
</div>