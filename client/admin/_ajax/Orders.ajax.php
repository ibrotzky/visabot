<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_PRODUCTS_ORDERS;

if (!APP_ORDERS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Orders';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;

    // AUTO INSTANCE OBJECT CREATE
    if (empty($Create)):
        $Create = new Create;
    endif;

    // AUTO INSTANCE OBJECT UPDATE
    if (empty($Update)):
        $Update = new Update;
    endif;
    
    // AUTO INSTANCE OBJECT DELETE
    if (empty($Delete)):
        $Delete = new Delete;
    endif;
    
    $Email = new Email;
    $Upload = new Upload('../../uploads/');

    //SELECIONA AÇÃO
    switch ($Case):
        case 'manager':
            $OrderId = $PostData['order_id'];
            $OrderMail = (!empty($PostData['post_mail']) ? true : false);
            unset($PostData['order_id'], $PostData['post_mail'], $PostData['order_nfe']);

            $Read->FullRead("SELECT order_nfepdf, order_nfexml FROM " . DB_ORDERS . " WHERE order_id = :ord", "ord={$OrderId}");
            if (!empty($_FILES['order_nfe'])):
                $NFE = $_FILES['order_nfe'];
                $nfeFile = array();
                $nfeCount = count($NFE['type']);
                $nfeKeys = array_keys($NFE);
                $nfeLoop = 0;
                $UpdateNfe = array();

                for ($nfe = 0; $nfe < $nfeCount; $nfe++):
                    foreach ($nfeKeys as $Keys):
                        $nfeFiles[$nfe][$Keys] = $NFE[$Keys][$nfe];
                    endforeach;
                endfor;

                foreach ($nfeFiles as $nfeUpload):
                    if (strstr($nfeUpload['type'], '/pdf')):
                        if ($Read->getResult() && $Read->getResult()[0]['order_nfepdf'] && file_exists("../../uploads/{$Read->getResult()[0]['order_nfepdf']}") && !is_dir("../../uploads/{$Read->getResult()[0]['order_nfepdf']}")):
                            unlink("../../uploads/{$Read->getResult()[0]['order_nfepdf']}");
                        endif;
                        $Upload->File($nfeUpload, md5(base64_encode($OrderId)), "nfewc", 20);
                        $PostData['order_nfepdf'] = $Upload->getResult();
                        $jSON['nfepdf'] = BASE . "/uploads/{$Upload->getResult()}";
                        $Email->addFile('../../uploads/' . $Upload->getResult());
                    endif;

                    if (strstr($nfeUpload['type'], '/xml')):
                        if ($Read->getResult() && $Read->getResult()[0]['order_nfexml'] && file_exists("../../uploads/{$Read->getResult()[0]['order_nfexml']}") && !is_dir("../../uploads/{$Read->getResult()[0]['order_nfexml']}")):
                            unlink("../../uploads/{$Read->getResult()[0]['order_nfexml']}");
                        endif;
                        $Upload->File($nfeUpload, md5(base64_encode($OrderId)), "nfewc", 20);
                        $PostData['order_nfexml'] = $Upload->getResult();
                        $jSON['nfexml'] = BASE . "/uploads/{$Upload->getResult()}";
                        $Email->addFile('../../uploads/' . $Upload->getResult());
                    endif;
                endforeach;
            elseif ($Read->getResult()):
                $Email->addFile('../../uploads/' . $Read->getResult()[0]['order_nfepdf']);
                $Email->addFile('../../uploads/' . $Read->getResult()[0]['order_nfexml']);
            endif;

            $Read->ExeRead(DB_ORDERS, "WHERE order_id = :order", "order={$OrderId}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("<span class='icon-warning'>Opss {$_SESSION['userLogin']['user_name']}. Você está tentando gerenciar um pedido que não existe ou foi removido!</span>", E_USER_WARNING);
            else:
                extract($Read->getResult()[0]);
                $Read->ExeRead(DB_USERS, "WHERE user_id = :user", "user={$user_id}");
                $Client = $Read->getResult()[0];
                $Traking = ($order_shipcode < 40000 ? ECOMMERCE_SHIPMENT_COMPANY_LINK : 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=');

                if ($OrderMail):
                    if ($PostData['order_status'] == 6 && !$order_mail_processing):
                        //ENVIA E-MAIL DE PEDIDO EM PROCESSAMENTO
                        $BodyMail = "<p style='font-size: 1.2em;'>Caro(a) {$Client['user_name']},</p>";
                        $BodyMail .= "<p>Este e-mail é para informar que seu pedido #" . str_pad($OrderId, 7, 0, STR_PAD_LEFT) . ", foi processado aqui na " . SITE_NAME . " e que já estamos preparando ele!</p>";
                        $BodyMail .= "<p>Isso significa que já identificamos o pagamento do seu pedido, e o mesmo está sendo preparado para ser enviado para você!</p>";
                        $BodyMail .= "<p style='font-size: 1.4em;'>Detalhes do Pedido:</p>";
                        $BodyMail .= "<p>Pedido: <a href='" . BASE . "/conta/pedido/{$order_id}' title='Ver pedido' target='_blank'>#" . str_pad($OrderId, 7, 0, STR_PAD_LEFT) . "</a><br>Data: " . date('d/m/Y H\hi', strtotime($order_date)) . "<br>Valor: R$ " . number_format($order_price, '2', ',', '.') . "<br>Método de Pagamento: " . getOrderPayment($order_payment) . "</p>";
                        $BodyMail .= "<hr><table style='width: 100%'><tr><td>STATUS:</td><td style='color: #00AD8E; text-align: center;'>✓ Aguardando Pagamento</td><td style='color: #00AD8E; text-align: center;'>✓ Processando</td><td style='color: #888888; text-align: right;'>» Concluído</td></tr></table><hr>";
                        $Read->ExeRead(DB_ORDERS_ITEMS, "WHERE order_id = :order", "order={$OrderId}");
                        if ($Read->getResult()):
                            $i = 0;
                            $ItemsPrice = 0;
                            $ItemsAmount = 0;
                            $BodyMail .= "<p style='font-size: 1.4em;'>Produtos:</p>";
                            $BodyMail .= "<p>Abaixo você pode conferir os detalhes, quantidades e valores de cada produto adquirido em seu pedido. Confira:</p>";
                            $BodyMail .= "<table style='width: 100%' border='0' cellspacing='0' cellpadding='0'>";
                            foreach ($Read->getResult() as $Item):
                                $Read->FullRead("SELECT stock_code FROM " . DB_PDT_STOCK . " WHERE stock_id = :stid", "stid={$Item['stock_id']}");
                                $ProductSize = ($Read->getResult() && $Read->getResult()[0]['stock_code'] != 'default' ? " ({$Read->getResult()[0]['stock_code']})" : null);

                                $i++;
                                $ItemsAmount += $Item['item_amount'];
                                $ItemsPrice += $Item['item_amount'] * $Item['item_price'];
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>" . str_pad($i, 5, 0, STR_PAD_LEFT) . " - " . Check::Words($Item['item_name'], 5) . "{$ProductSize}</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($Item['item_price'], '2', ',', '.') . " * <b>{$Item['item_amount']}</b></td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($Item['item_amount'] * $Item['item_price'], '2', ',', '.') . "</td></tr>";
                            endforeach;
                            if (!empty($order_coupon)):
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Cupom:</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>{$order_coupon}% de desconto</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>- <strike>R$ " . number_format($ItemsPrice * ($order_coupon / 100), '2', ',', '.') . "</strike></td></tr>";
                            endif;
                            if (!empty($order_shipcode)):
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Frete via " . getShipmentTag($order_shipcode) . "</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_shipprice, '2', ',', '.') . " <b>* 1</b></td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_shipprice, '2', ',', '.') . "</td></tr>";
                            endif;
                            $BodyMail .= "<tr style='background: #cccccc;'><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px;'>{$i} produto(s) no pedido</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px; text-align: right;'>{$ItemsAmount} Itens</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px; text-align: right;'>R$ " . number_format($order_price, '2', ',', '.') . "</td></tr>";

                            if (!empty($order_installments) && $order_installments > 1):
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Pago em {$order_installments}x de R$ " . number_format($order_installment, '2', ',', '.') . "</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>Total: </td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_installments * $order_installment, '2', ',', '.') . "</td></tr>";
                            endif;
                            $BodyMail .= "</table>";
                        endif;
                        $BodyMail .= "<p>Qualquer dúvida não deixe de entrar em contato {$Client['user_name']}. Obrigado por sua preferência mais uma vez...</p>";
                        $BodyMail .= "<p><i>Atenciosamente " . SITE_NAME . "!</i></p>";

                        require '../_tpl/Client.email.php';
                        $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                        $Email->EnviarMontando("Identificamos seu pagamento #" . str_pad($order_id, 7, 0, 0), $Mensagem, SITE_NAME, MAIL_USER, "{$Client['user_name']} {$Client['user_lastname']}", $Client['user_email']);

                        //ESTOQUE: Remove produtos do estoque:
                        $Read->FullRead("SELECT pdt_id, item_amount FROM " . DB_ORDERS_ITEMS . " WHERE order_id = :order AND pdt_id IS NOT NULL", "order={$OrderId}");
                        if ($Read->getResult()):
                            foreach ($Read->getResult() as $Inventory):
                                $Read->FullRead("SELECT pdt_inventory, pdt_delivered FROM " . DB_PDT . " WHERE pdt_id = :pdt", "pdt={$Inventory['pdt_id']}");
                                if ($Read->getResult()):
                                    $UpdateInventory = [
                                        'pdt_inventory' => $Read->getResult()[0]['pdt_inventory'] - $Inventory['item_amount'],
                                        'pdt_delivered' => $Read->getResult()[0]['pdt_delivered'] + $Inventory['item_amount']
                                    ];
                                    $Update->ExeUpdate(DB_PDT, $UpdateInventory, "WHERE pdt_id = :pdt", "pdt={$Inventory['pdt_id']}");
                                endif;
                            endforeach;
                        endif;

                        //Impede envio dubplicado de e-mail de processamento
                        $PostData['order_mail_processing'] = 1;
                    endif;

                    if ($PostData['order_status'] == 1 && !$order_mail_completed && $PostData['order_tracking']):
                        //ENVIA E-MAIL DE PEDIDO CONCLUÍDO
                        $BodyMail = "<p style='font-size: 1.2em;'>Caro(a) {$Client['user_name']},</p>";
                        $BodyMail .= "<p>Este e-mail rápido é para informar que seu pedido #" . str_pad($OrderId, 7, 0, STR_PAD_LEFT) . " foi concluído, e que seus produtos estão a caminho!</p>";
                        if ($PostData['order_tracking'] && $PostData['order_tracking'] != 1):
                            $BodyMail .= "<p>Você pode acompanhar o envio <a title='Acompanhar Pedido' href='{$Traking}{$PostData['order_tracking']}' target='_blank'>clicando aqui!</a></p>";
                        endif;
                        $BodyMail .= "<p>A " . SITE_NAME . " gostaria de lhe agradecer mais uma vez pela preferência em adquirir seus produtos em nossa loja.</p>";
                        $BodyMail .= "<p>Esperamos ter proporcionado a melhor experiência!</p>";
                        $BodyMail .= "<p style='font-size: 1.4em;'>Detalhes do Pedido:</p>";
                        $BodyMail .= "<p>Pedido: <a href='" . BASE . "/conta/pedido/{$order_id}' title='Ver pedido' target=''>#" . str_pad($OrderId, 7, 0, STR_PAD_LEFT) . "</a><br>Data: " . date('d/m/Y H\hi', strtotime($order_date)) . "<br>Valor: R$ " . number_format($order_price, '2', ',', '.') . "<br>Método de Pagamento: " . getOrderPayment($order_payment) . (!empty($PostData['order_tracking']) && $PostData['order_tracking'] != 1 ? "<br>Código do Rastreio: <a title='Acompanhar Pedido' href='{$Traking}{$PostData['order_tracking']}' target='_blank'>{$PostData['order_tracking']}</a>" : '') . "</p>";
                        $BodyMail .= "<hr><table style='width: 100%'><tr><td>STATUS:</td><td style='color: #00AD8E; text-align: center;'>✓ Aguardando Pagamento</td><td style='color: #00AD8E; text-align: center;'>✓ Processando</td><td style='color: #00AD8E; text-align: right;'>✓ Concluído</td></tr></table><hr>";
                        $Read->ExeRead(DB_ORDERS_ITEMS, "WHERE order_id = :order", "order={$OrderId}");
                        if ($Read->getResult()):
                            $i = 0;
                            $ItemsPrice = 0;
                            $ItemsAmount = 0;
                            $BodyMail .= "<p style='font-size: 1.4em;'>Produtos:</p>";
                            $BodyMail .= "<p>Abaixo você pode conferir os detalhes, quantidades e valores de cada produto adquirido em seu pedido. Confira:</p>";
                            $BodyMail .= "<table style='width: 100%' border='0' cellspacing='0' cellpadding='0'>";
                            foreach ($Read->getResult() as $Item):
                                $Read->FullRead("SELECT stock_code FROM " . DB_PDT_STOCK . " WHERE stock_id = :stid", "stid={$Item['stock_id']}");
                                $ProductSize = ($Read->getResult() && $Read->getResult()[0]['stock_code'] != 'default' ? " ({$Read->getResult()[0]['stock_code']})" : null);

                                $i++;
                                $ItemsAmount += $Item['item_amount'];
                                $ItemsPrice += $Item['item_amount'] * $Item['item_price'];
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>" . str_pad($i, 5, 0, STR_PAD_LEFT) . " - " . Check::Words($Item['item_name'], 5) . "{$ProductSize}</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($Item['item_price'], '2', ',', '.') . " * <b>{$Item['item_amount']}</b></td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($Item['item_amount'] * $Item['item_price'], '2', ',', '.') . "</td></tr>";
                            endforeach;
                            if (!empty($order_coupon)):
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Cupom:</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>{$order_coupon}% de desconto</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>- <strike>R$ " . number_format($ItemsPrice * ($order_coupon / 100), '2', ',', '.') . "</strike></td></tr>";
                            endif;
                            if (!empty($order_shipcode)):
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Frete via " . getShipmentTag($order_shipcode) . "</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_shipprice, '2', ',', '.') . " <b>* 1</b></td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_shipprice, '2', ',', '.') . "</td></tr>";
                            endif;
                            $BodyMail .= "<tr style='background: #cccccc;'><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px;'>{$i} produto(s) no pedido</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px; text-align: right;'>{$ItemsAmount} Itens</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px; text-align: right;'>R$ " . number_format($order_price, '2', ',', '.') . "</td></tr>";

                            if (!empty($order_installments) && $order_installments > 1):
                                $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Pago em {$order_installments}x de R$ " . number_format($order_installment, '2', ',', '.') . "</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>Total: </td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_installments * $order_installment, '2', ',', '.') . "</td></tr>";
                            endif;
                            $BodyMail .= "</table>";
                        endif;
                        $BodyMail .= "<p>Qualquer dúvida não deixe de entrar em contato {$Client['user_name']}. Obrigado por sua preferência mais uma vez...</p>";
                        $BodyMail .= "<p><i>Atenciosamente " . SITE_NAME . "!</i></p>";

                        require '../_tpl/Client.email.php';
                        $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                        $Email->EnviarMontando("Seu pedido esta a caminho #" . str_pad($order_id, 7, 0, 0), $Mensagem, SITE_NAME, MAIL_USER, "{$Client['user_name']} {$Client['user_lastname']}", $Client['user_email']);

                        //Impede envio dubplicado de e-mail de concluído
                        $PostData['order_mail_completed'] = 1;
                    elseif ($PostData['order_status'] == 1 && !$order_mail_completed && empty($PostData['order_tracking'])):
                        $jSON['trigger'] = AjaxErro("<span class='icon-checkmark'>Pedido Atualizado com Sucesso!</span><p class='icon-warning'>Opss {$Client['user_name']}. <b>Informe o código do RASTREIO</b> para informar o cliente sobre seu pedido!</p>", E_USER_WARNING);
                    endif;
                endif;

                if (!empty($PostData['order_tracking']) && $PostData['order_tracking'] != 1):
                    $jSON['content'] = "<a title='Rastrear Pedido' target='_blanck' href='{$Traking}{$PostData['order_tracking']}'>RASTREIO:</a>";
                else:
                    $jSON['content'] = 'RASTREIO:';
                endif;

                $PostData['order_shipment'] = (empty($PostData['order_shipment']) ? null : $PostData['order_shipment']);
                $Update->ExeUpdate(DB_ORDERS, $PostData, "WHERE order_id = :order", "order={$OrderId}");

                if (empty($jSON['trigger'])):
                    $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>Pedido Atualizado com Sucesso!</b>");
                endif;
            endif;
            break;

        case 'cancel':
            $order_id = $PostData['order_id'];

            $Read->ExeRead(DB_ORDERS, "WHERE order_id = :ord", "ord={$order_id}");
            extract($Read->getResult()[0]);

            $Read->ExeRead(DB_USERS, "WHERE user_id = :user", "user={$user_id}");
            $Client = $Read->getResult()[0];

            $BodyMail = "<p style='font-size: 1.2em;'>Caro(a) {$Client['user_name']},</p>";
            $BodyMail .= "<p>Este e-mail é para informar que o seu pedido #" . str_pad($order_id, 7, 0, 0) . " foi cancelado.</p>";
            $BodyMail .= "<p>Isso ocorre quando o pagamento não é identificado no prazo, ou quando a operadora (em compras com cartão) nega o pagamento!</p>";

            $BodyMail .= "<p><b>Não desanime {$Client['user_name']}...</b></p>";
            $BodyMail .= "<p>...você ainda pode acessar nosso site e fazer um novo pedido. E assim que confirmado vamos processar e enviar o mais breve possível!</p>";
            $BodyMail .= "<p><a href='" . BASE . "' title='Conferir Produtos' target='_blank'>Confira aqui nossas novidades!</a></p>";

            $BodyMail .= "<p>Caso tenha qualquer dúvida por favor, entre em contato respondendo este e-mail ou pelo telefone " . SITE_ADDR_PHONE_A . ".</p>";
            $BodyMail .= "<p style='font-size: 1.4em;'>Detalhes do Pedido:</p>";
            $BodyMail .= "<p>Pedido: <a href='" . BASE . "/conta/pedido/{$order_id}' title='Ver pedido' target='_blank'>#" . str_pad($order_id, 7, 0, STR_PAD_LEFT) . "</a><br>Data: " . date('d/m/Y H\hi', strtotime($order_date)) . "<br>Valor: R$ " . number_format($order_price, '2', ',', '.') . "<br>Método de Pagamento: " . getOrderPayment($order_payment) . "</p>";
            $BodyMail .= "<hr><table style='width: 100%'><tr><td>STATUS:</td><td style='color: #00AD8E; text-align: center;'>✓ Aguardando Pagamento</td><td style='color: #888888; text-align: center;'>✓ Processando</td><td style='color: #CC4E4F; text-align: right;'>✓ Cancelado</td></tr></table><hr>";
            $Read->ExeRead(DB_ORDERS_ITEMS, "WHERE order_id = :order", "order={$order_id}");
            if ($Read->getResult()):
                $i = 0;
                $ItemsPrice = 0;
                $ItemsAmount = 0;
                $BodyMail .= "<p style='font-size: 1.4em;'>Produtos:</p>";
                $BodyMail .= "<p>Abaixo você pode conferir os detalhes, quantidades e valores de cada produto adquirido em seu pedido. Confira:</p>";
                $BodyMail .= "<table style='width: 100%' border='0' cellspacing='0' cellpadding='0'>";
                foreach ($Read->getResult() as $Item):
                    $Read->FullRead("SELECT stock_code FROM " . DB_PDT_STOCK . " WHERE stock_id = :stid", "stid={$Item['stock_id']}");
                    $ProductSize = ($Read->getResult() && $Read->getResult()[0]['stock_code'] != 'default' ? " ({$Read->getResult()[0]['stock_code']})" : null);

                    $i++;
                    $ItemsAmount += $Item['item_amount'];
                    $ItemsPrice += $Item['item_amount'] * $Item['item_price'];
                    $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>" . str_pad($i, 5, 0, STR_PAD_LEFT) . " - " . Check::Words($Item['item_name'], 5) . "{$ProductSize}</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($Item['item_price'], '2', ',', '.') . " * <b>{$Item['item_amount']}</b></td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($Item['item_amount'] * $Item['item_price'], '2', ',', '.') . "</td></tr>";
                endforeach;
                if (!empty($order_coupon)):
                    $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Cupom:</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>{$order_coupon}% de desconto</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>- <strike>R$ " . number_format($ItemsPrice * ($order_coupon / 100), '2', ',', '.') . "</strike></td></tr>";
                endif;
                if (!empty($order_shipcode)):
                    $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Frete via " . getShipmentTag($order_shipcode) . "</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_shipprice, '2', ',', '.') . " <b>* 1</b></td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_shipprice, '2', ',', '.') . "</td></tr>";
                endif;
                $BodyMail .= "<tr style='background: #cccccc;'><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px;'>{$i} produto(s) no pedido</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px; text-align: right;'>{$ItemsAmount} Itens</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 10px 10px 10px; text-align: right;'>R$ " . number_format($order_price, '2', ',', '.') . "</td></tr>";

                if (!empty($order_installments) && $order_installments > 1):
                    $BodyMail .= "<tr><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0;'>Pago em {$order_installments}x de R$ " . number_format($order_installment, '2', ',', '.') . "</td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>Total: </td><td style='border-bottom: 1px solid #cccccc; padding: 10px 0 10px 0; text-align: right;'>R$ " . number_format($order_installments * $order_installment, '2', ',', '.') . "</td></tr>";
                endif;
                $BodyMail .= "</table>";
            endif;

            $BodyMail .= "<p>Fique a vontade para escolher novos produtos e realizar um novo pedido em nossa loja! <a href='" . BASE . "' title='Produtos " . SITE_NAME . "'>Confira aqui nossos produtos!</a></p>";
            $BodyMail .= "<p>Qualquer dúvida não deixe de entrar em contato {$Client['user_name']}. Obrigado por sua preferência mais uma vez...</p>";
            $BodyMail .= "<p><i>Atenciosamente " . SITE_NAME . "!</i></p>";

            require '../../_cdn/widgets/ecommerce/cart.email.php';
            $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
            $Email->EnviarMontando("Pedido cancelado #" . str_pad($order_id, 7, 0, 0), $Mensagem, SITE_NAME, MAIL_USER, "{$Client['user_name']} {$Client['user_lastname']}", $Client['user_email']);

            //ORDER CANCEL
            if ($order_status != 2):
                $UpdateOrder = ['order_status' => 2, 'order_update' => date('Y-m-d H:i:s')];
                $Update->ExeUpdate(DB_ORDERS, $UpdateOrder, "WHERE order_id = :orid", "orid={$order_id}");
            endif;
            $jSON['success'] = true;
            $jSON['trigger'] = AjaxErro("<b class='icon-warning'>PEDIDO CANCELADO:</b> Um e-mail foi enviado para {$Client['user_name']} ({$Client['user_email']}) avisando!");
            break;

        case 'delete':
            $Delete->ExeDelete(DB_ORDERS, "WHERE order_id = :order", "order={$PostData['del_id']}");
            $Delete->ExeDelete(DB_ORDERS_ITEMS, "WHERE order_id = :order", "order={$PostData['del_id']}");

            $jSON['trigger'] = AjaxErro('<b class="icon-checkmark">PEDIDO REMOVIDO COM SUCESSO!</b> <a style="font-size: 0.8em; margin-left: 10px" class="btn btn_green" href="dashboard.php?wc=orders/home" title="Ver Pedidos">VER PEDIDOS!</a>');
            break;

        case 'wcOrderCreateApp':
            //SEARCH USER
            if (!empty($PostData['Search'])):
                $UserSearch = $PostData['Search'];
                $Read->FullRead("SELECT user_id, user_name, user_lastname, user_email, user_document FROM " . DB_USERS . " WHERE CONCAT_WS(' ', user_name, user_lastname) LIKE '%' :s '%' OR user_email LIKE '%' :s '%'", "s={$UserSearch}");
                if ($Read->getResult()):
                    $jSON['result'] = "<ul class='wc_createorder_user' style='margin-top: 15px;'>";
                    foreach ($Read->getResult() as $User):
                        $jSON['result'] .= "<li><label><input class='jwc_ordercreate_addr' type='radio' name='user_id' value='{$User['user_id']}'><p><b>NOME:</b> {$User['user_name']} {$User['user_lastname']}<br><b>E-MAIL:</b> {$User['user_email']}<br><b>CPF:</b> {$User['user_document']}</p></label></li>";
                    endforeach;
                    $jSON['result'] .= "</ul>";
                else:
                    $jSON['result'] = "<div class='trigger trigger_info' style='display: block; margin-top: 15px;'>Nada encontrado para {$UserSearch}!</div>";
                endif;
            endif;

            if (!empty($PostData['AddrUser'])):
                $UserId = $PostData['AddrUser'];
                $Read->ExeRead(DB_USERS_ADDR, "WHERE user_id = :id", "id={$UserId}");
                if ($Read->getResult()):
                    //CART SESSION
                    if (empty($_SESSION['oderCreate'])):
                        $_SESSION['oderCreate'] = array();
                    endif;
                    $_SESSION['oderCreate']['user_id'] = $UserId;

                    $jSON['result'] = "<ul class='wc_createorder_user'>";
                    foreach ($Read->getResult() as $Addr):
                        $jSON['result'] .= "<li><label><input class='jwc_ordercreate_addr' type='radio' name='addr_id' value='{$Addr['addr_id']}'><p><b>ENDEREÇO:</b> {$Addr['addr_name']}<br>{$Addr['addr_street']}, {$Addr['addr_number']}<br>{$Addr['addr_district']}, {$Addr['addr_city']}/{$Addr['addr_state']}<br>CEP: {$Addr['addr_zipcode']}</p></label></li>";
                    endforeach;
                    $jSON['result'] .= "</ul>";
                else:
                    $jSON['result'] = "<div class='trigger trigger_info' style='display: block'>O cliente não tem endereços cadastrados!</div>";
                endif;
            endif;

            //ADDR SET
            if (!empty($PostData['setAddr'])):
                $_SESSION['oderCreate']['addr_id'] = $PostData['setAddr'];
            endif;

            //SEARCH PRODUTCTS
            if (!empty($PostData['PdtSearch'])):
                $PdtSearch = $PostData['PdtSearch'];
                $Read->FullRead("SELECT stock_id, pdt_id, stock_code, stock_inventory FROM " . DB_PDT_STOCK . " WHERE stock_inventory >= 1 AND pdt_id IN(SELECT pdt_id FROM " . DB_PDT . " WHERE pdt_title LIKE '%' :s '%' OR pdt_code LIKE '%' :s '%') ORDER BY stock_inventory DESC LIMIT 5", "s={$PdtSearch}");
                if (!$Read->getResult()):
                    $jSON['result'] = "<div class='trigger trigger_info' style='display: block; margin: 15px 0 0 0;'>Não foram encontratos produtos para os termpos {$PdtSearch}!</div>";
                else:
                    $jSON['result'] = null;
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
                            $jSON['result'] .= "<article class='wc_order_create_item jwc_order_create_add' id='{$Stock['stock_id']}'>
                            <img src='../tim.php?src=uploads/{$pdt_cover}&w=180' alt='{$pdt_title}' title='{$pdt_title}'/><header>
                                <h1>{$pdt_title} <span>Tamanho: {$Stock['stock_code']}</span></h1>
                                <p>R$ " . number_format($PdtPrice, 2, ',', '.') . " - <span>{$Stock['stock_inventory']} em estoque</span></p>
                            </header><div class='add'>
                                <select name='pdt_inventory'>{$ForSelect}</select><span id='{$Stock['stock_id']}' class='btn btn_green'><b>ADD</b></span>
                            </div></article>";
                        endif;
                    endforeach;
                endif;
            endif;

            //ADD TO CARD
            if (!empty($PostData['StockId'])):
                $StockId = $PostData['StockId'];
                $StockQtd = $PostData['StockQtd'];
                $Read->ExeRead(DB_PDT, "WHERE pdt_id = (SELECT pdt_id FROM " . DB_PDT_STOCK . " WHERE stock_id = :id AND stock_inventory >= :st)", "id={$StockId}&st={$StockQtd}");
                $Product = $Read->getResult();

                if ($Product):
                    $jSON['trigger'] = AjaxErro("( √ ) <b>{$StockQtd}</b> unidades do produto <b>{$Product[0]['pdt_title']}</b> adicionadas com sucesso!<p>( ! ) Quando terminar basta finalizar o pedido!</p>");
                    $_SESSION['oderCreate']['item'][$StockId] = $StockQtd;

                    $jSON['result'] = null;
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

                        $jSON['result'] .= "<article class='item_{$Pdt} wc_ordercreate_itemcart'>
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
                    $_SESSION['oderCreate']['order_price'] = $CartTotalPrice;

                    $jSON['result'] .= "<div class='wc_ordercreate_totalcart'>";
                    $jSON['result'] .= "<p>{$CartTotalItems} produtos</p>";
                    $jSON['result'] .= "<p>Total: R$ " . number_format($CartTotalPrice, 2, ',', '.') . "</p>";
                    $jSON['result'] .= "<p><span class='btn btn_green jwc_orderapp_finish_order'>FINALIZAR PEDIDO</span></p>";
                    $jSON['result'] .= "</div>";
                else:
                    $jSON['trigger'] = AjaxErro("<b>( X )</b> O produto que você tentou adicionar não existe ou esta fora do estoque informado!", E_USER_WARNING);
                endif;
            endif;

            //REMOVE
            if (!empty($PostData['Remove'])):
                unset($_SESSION['oderCreate']['item'][$PostData['Remove']]);

                $jSON['result'] = null;
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

                    $jSON['result'] .= "<article class='item_{$Pdt} wc_ordercreate_itemcart'>
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
                $_SESSION['oderCreate']['order_price'] = $CartTotalPrice;

                $jSON['result'] .= "<div class='wc_ordercreate_totalcart'>";
                $jSON['result'] .= "<p>{$CartTotalItems} produtos</p>";
                $jSON['result'] .= "<p>Total: R$ " . number_format($CartTotalPrice, 2, ',', '.') . "</p>";
                $jSON['result'] .= "<p><span class='btn btn_green jwc_orderapp_finish_order'>FINALIZAR PEDIDO</span></p>";
                $jSON['result'] .= "</div>";
            endif;
            break;

        //CASE ORDER
        case 'OrderAppFinish':
            $CartTotal = 0;
            $HeightTotal = 0;
            $WidthTotal = 0;
            $DepthTotal = 0;
            $WeightTotal = 0;
            $AmountTotal = 0;
            foreach ($_SESSION['oderCreate']['item'] as $ItemId => $ItemAmount):
                $Read->ExeRead(DB_PDT, "WHERE pdt_id = (SELECT pdt_id FROM " . DB_PDT_STOCK . " WHERE stock_id = :id)", "id={$ItemId}");
                if (!$Read->getResult()):
                    unset($_SESSION['oderCreate']['item']);
                else:
                    extract($Read->getResult()[0]);
                    $CartTotal += ($pdt_offer_price && $pdt_offer_start <= date('Y-m-d H:i:s') && $pdt_offer_end >= date('Y-m-d H:i:s') ? $pdt_offer_price : $pdt_price) * $ItemAmount;
                    //$HeightTotal = ($HeightTotal < $pdt_dimension_heigth ? $pdt_dimension_heigth : $HeightTotal);
                    $HeightTotal += $pdt_dimension_heigth * $ItemAmount;
                    $WidthTotal += $pdt_dimension_width * $ItemAmount;
                    $DepthTotal += $pdt_dimension_depth * $ItemAmount;
                    $WeightTotal += $pdt_dimension_weight * $ItemAmount;
                    $AmountTotal += $ItemAmount;
                endif;
            endforeach;

            $CartTotalShip = number_format($CartTotal, '2', ',', '');
            $WeightTotalShip = floatval($WeightTotal / 1000);
            $HeightTotalShip = ($HeightTotal >= 2 ? $HeightTotal : 2);
            $WidthTotalShip = ($WidthTotal / $AmountTotal >= 11 ? $WidthTotal / $AmountTotal : 11);
            $DepthTotalShip = ($DepthTotal / $AmountTotal >= 16 ? $DepthTotal / $AmountTotal : 16);

            $data['nCdEmpresa'] = (!empty(ECOMMERCE_SHIPMENT_CDEMPRESA) ? ECOMMERCE_SHIPMENT_CDEMPRESA : 0);
            $data['sDsSenha'] = (!empty(ECOMMERCE_SHIPMENT_CDSENHA) ? ECOMMERCE_SHIPMENT_CDSENHA : 0);
            $data['sCepOrigem'] = str_replace('-', '', SITE_ADDR_ZIP);

            $Read->FullRead("SELECT addr_zipcode FROM " . DB_USERS_ADDR . " WHERE addr_id = :addr", "addr={$_SESSION['oderCreate']['addr_id']}");
            $ZipCode = (!empty($Read->getResult()[0]['addr_zipcode']) ? $Read->getResult()[0]['addr_zipcode'] : '00000-000');
            $data['sCepDestino'] = str_replace('-', '', $ZipCode);
            $data['nVlPeso'] = $WeightTotalShip;
            $data['nCdFormato'] = ECOMMERCE_SHIPMENT_FORMAT;
            $data['nVlComprimento'] = $DepthTotalShip;
            $data['nVlAltura'] = $HeightTotalShip;
            $data['nVlLargura'] = $WidthTotalShip;
            $data['nVlDiametro'] = '0';
            $data['sCdMaoPropria'] = (ECOMMERCE_SHIPMENT_OWN_HAND == 1 || ECOMMERCE_SHIPMENT_OWN_HAND == 's' ? 's' : 'n');
            $data['nVlValorDeclarado'] = (ECOMMERCE_SHIPMENT_DECLARE ? $CartTotalShip : '0');
            $data['sCdAvisoRecebimento'] = (ECOMMERCE_SHIPMENT_ALERT ? 's' : 'n');
            $data['StrRetorno'] = 'xml';
            $data['nCdServico'] = ECOMMERCE_SHIPMENT_SERVICE;
            $data = http_build_query($data);

            $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
            $curl = curl_init($url . '?' . $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            $resultXml = simplexml_load_string($result);

            //RETIRADA E LOCAL:
            $jSON['cart_shipment'] = "<li><label><input type='radio' name='shipment' value='0' id='10005'><span>Retirar na loja - R$ 0,00</span></label></li>";
            if (ECOMMERCE_SHIPMENT_LOCAL):
                $City = json_decode(file_get_contents("https://viacep.com.br/ws/" . str_replace('-', '', $ZipCode) . "/json/"));
                if (!empty($City) && !empty($City->localidade) && $City->localidade == ECOMMERCE_SHIPMENT_LOCAL):
                    $jSON['cart_shipment'] .= "<li><label><input type='radio' name='shipment' value='" . ECOMMERCE_SHIPMENT_LOCAL_PRICE . "' id='10004'><span>Taxa de entrega: R$ " . number_format(ECOMMERCE_SHIPMENT_LOCAL_PRICE, 2, ',', '.') . "</span></label></li>";
                endif;
            endif;

            //FRETE FIXO:
            if (ECOMMERCE_SHIPMENT_FIXED):
                $jSON['cart_shipment'] .= "<li><label><input type='radio' name='shipment' value='" . ECOMMERCE_SHIPMENT_FIXED_PRICE . "' id='10003'><span>Frete Fixo: 01 a " . str_pad(ECOMMERCE_SHIPMENT_DELAY + ECOMMERCE_SHIPMENT_FIXED_DAYS, 2, 0, 0) . " dias úteis - R$ " . number_format(ECOMMERCE_SHIPMENT_FIXED_PRICE, 2, ',', '.') . "</span></label></li>";
            endif;

            //CORREIOS:
            foreach ($resultXml->cServico as $row) :
                if ($row->Erro == 0) :
                    $jSON['cart_shipment'] .= "<li><label><input type='radio' name='shipment' value='" . str_replace(',', '.', $row->Valor) . "' id='{$row->Codigo}'><span>" . getShipmentTag(intval($row->Codigo)) . ": 01 a " . str_pad($row->PrazoEntrega + ECOMMERCE_SHIPMENT_DELAY, 2, 0, 0) . " dias úteis - R$ {$row->Valor}</span></label></li>";
                endif;
            endforeach;

            if (!empty($row->Erro) && $row->Erro == '-3'):
                $jSON['trigger'] = AjaxErro("<b>OPPSSS:</b> O CEP digitado não foi encontrado na base dos correios. Confira isso :)", E_USER_WARNING);
                $ErroZip = true;
            endif;

            //TRANSPORTADORA:
            $CompanyPrice = $CartTotal * (ECOMMERCE_SHIPMENT_COMPANY_VAL / 100);
            if (ECOMMERCE_SHIPMENT_COMPANY && $CompanyPrice >= ECOMMERCE_SHIPMENT_COMPANY_PRICE && empty($ErroZip)):
                $jSON['cart_shipment'] .= "<li><label><input type='radio' name='shipment' value='{$CompanyPrice}' id='10001'><span>Transportadora: 01 a " . str_pad(ECOMMERCE_SHIPMENT_DELAY + ECOMMERCE_SHIPMENT_COMPANY_DAYS, 2, 0, 0) . " dias úteis - R$ " . number_format($CompanyPrice, '2', ',', '.') . "</span></label></li>";
            endif;

            //GRATUITO POR VALOR:
            if (ECOMMERCE_SHIPMENT_FREE && $_SESSION['oderCreate']['order_price'] > ECOMMERCE_SHIPMENT_FREE && empty($ErroZip)):
                $jSON['cart_shipment'] .= "<li><label><input type='radio' name='shipment' value='0' id='10002'><span>Gratuito: 01 a " . str_pad(ECOMMERCE_SHIPMENT_DELAY + ECOMMERCE_SHIPMENT_FREE_DAYS, 2, 0, 0) . " dias úteis - R$ 0,00</span></label></li>";
            endif;

            $jSON['wc_cart_total'] = number_format($_SESSION['oderCreate']['order_price'], '2', ',', '.');
            $jSON['success'] = true;
            break;

        //ORDER CREATE
        case 'AppOrderCreate':
            if (!empty($PostData['action']) && $PostData['action'] == 'setship'):
                $_SESSION['oderCreate']['order_shipcode'] = $PostData['ShipCode'];
                $_SESSION['oderCreate']['order_shipprice'] = $PostData['ShipValue'];
            endif;

            if (!empty($PostData['action']) && $PostData['action'] == 'setcupom'):
                $_SESSION['oderCreate']['order_coupon'] = $PostData['OrderDisount'];
            endif;

            $OrderCupom = (!empty($_SESSION['oderCreate']['order_coupon']) ? $_SESSION['oderCreate']['order_coupon'] / 100 : 0);
            $OrderShip = (!empty($_SESSION['oderCreate']['order_shipprice']) ? $_SESSION['oderCreate']['order_shipprice'] : 0);
            $OrderCupomValue = ($OrderCupom != 0 ? ($_SESSION['oderCreate']['order_price'] + $OrderShip) * $OrderCupom : 0);

            $jSON['wc_cart_cupom'] = number_format(($_SESSION['oderCreate']['order_price'] + $OrderShip) * $OrderCupom, '2', ',', '.');
            $jSON['wc_cart_total'] = number_format(($_SESSION['oderCreate']['order_price'] + $OrderShip) - $OrderCupomValue, '2', ',', '.');
            $jSON['success'] = true;

            if (!empty($PostData['action']) && $PostData['action'] == 'create'):
                if (empty($_SESSION['oderCreate']['order_shipcode']) || !isset($_SESSION['oderCreate']['order_shipprice'])):
                    $jSON['trigger'] = AjaxErro("<b class='icon-warning'>ERRO AO CRIAR PEDIDO:</b> Por favor, selecione o tipo de frete para criar o pedido!", E_USER_WARNING);
                else:
                    $OrderCreate = $_SESSION['oderCreate'];
                    unset($OrderCreate['item'], $OrderCreate['addr_id']);
                    $OrderCreate['order_addr'] = $_SESSION['oderCreate']['addr_id'];
                    $OrderCreate['order_price'] = ($_SESSION['oderCreate']['order_price'] + $OrderShip) - $OrderCupomValue;
                    $OrderCreate['order_status'] = 3;
                    $OrderCreate['order_payment'] = 1;
                    $OrderCreate['order_date'] = date('Y-m-d H:i:s');
                    $OrderCreate['order_update'] = date('Y-m-d H:i:s');

                    $Create->ExeCreate(DB_ORDERS, $OrderCreate);
                    $OrderId = $Create->getResult();
                    $OrderCreateItem = array();
                    foreach ($_SESSION['oderCreate']['item'] as $Item => $Qtd):
                        $Read->FullRead("SELECT pdt_id, pdt_title, pdt_price, pdt_offer_price, pdt_offer_start, pdt_offer_end FROM " . DB_PDT . " WHERE pdt_id = (SELECT pdt_id FROM " . DB_PDT_STOCK . " WHERE stock_id = :st)", "st={$Item}");
                        if ($Read->getResult()):
                            extract($Read->getResult()[0]);
                            if ($pdt_offer_price && $pdt_offer_start <= date('Y-m-d H:i:s') && $pdt_offer_end >= date('Y-m-d H:i:s')):
                                $PdtPrice = $pdt_offer_price;
                            else:
                                $PdtPrice = $pdt_price;
                            endif;

                            $OrderCreateItem[] = [
                                'order_id' => $OrderId,
                                'pdt_id' => $pdt_id,
                                'stock_id' => $Item,
                                'item_name' => $pdt_title,
                                'item_price' => $PdtPrice,
                                'item_amount' => $Qtd,
                            ];
                        endif;
                    endforeach;
                    $Create->ExeCreateMulti(DB_ORDERS_ITEMS, $OrderCreateItem);
                    $jSON['wc_cart_link'] = "dashboard.php?wc=orders/order&id={$OrderId}";
                    $jSON['wc_cart_pay'] = BASE . "/pedido/pagamento/" . base64_encode($OrderId) . "#cart";

                    unset($_SESSION['oderCreate']);
                endif;
            endif;
            break;
    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
