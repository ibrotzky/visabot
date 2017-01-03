<?php

/*
 * EXECUTE THIS 1X HOUR
 */

if (!function_exists('MyAutoLoad')):
    require '../_app/Config.inc.php';
endif;


/*
 * CANCELA PEDIDOS QUANDO
 * Status = 3 (Novo pedido)
 * Dias do Pedido Maior que E_ORDER_DAYS
 */
if (APP_ORDERS && E_ORDER_DAYS):
    if (empty($Read)):
        $Read = new Read;
    endif;
    if (empty($Update)):
        $Update = new Update;
    endif;

    //LIMIT TO PREVINE OVERLOADER MAIL BOX
    $LIMIT = 20;
    $Read->ExeRead(DB_ORDERS, " WHERE order_status = :st AND order_date <= DATE_SUB(NOW(),INTERVAL " . E_ORDER_DAYS . " DAY) LIMIT :limit", "st=3&limit={$LIMIT}");
    if ($Read->getResult()):
        foreach ($Read->getResult() as $OrderCancel):
            extract($OrderCancel);

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

            require '../_cdn/widgets/ecommerce/cart.email.php';
            $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
            $Email = new Email;
            $Email->EnviarMontando("Pedido cancelado #" . str_pad($order_id, 7, 0, 0), $Mensagem, SITE_NAME, MAIL_USER, "{$Client['user_name']} {$Client['user_lastname']}", $Client['user_email']);

            //ORDER CANCEL
            if ($order_status != 2):
                $UpdateOrder = ['order_status' => 2, 'order_update' => date('Y-m-d H:i:s')];
                $Update->ExeUpdate(DB_ORDERS, $UpdateOrder, "WHERE order_id = :orid", "orid={$order_id}");
            endif;
        endforeach;
    endif;
endif;


#######################################################################
######## NÃO REMOVA OU ALTERE AS LINHAS ABAIXO DE LICENCIAMENTO #######
#######################################################################
if (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != 'localhost' && !in_array('localhost', explode('/', BASE))):
    $CronJobFile = __DIR__ . '/cronjob.txt';
    $CoreControl = (!empty(get_meta_tags('../admin/index.php')['mit']) ? get_meta_tags('../admin/index.php')['mit'] : null);
    if ($CoreControl && function_exists('curl_init')):
        $LicenceUser = $CoreControl;
        $LicenceDomi = $_SERVER['SERVER_NAME'];
        $LicenceKey = base64_encode("{$LicenceUser}(wc){$LicenceDomi}(wc)" . BASE);

        if (file_exists($CronJobFile)):
            $CronCheck = file_get_contents($CronJobFile);
            $CronVars = explode("(wc)", $CronCheck);

            if (empty($CronVars[0]) || empty($CronVars[1]) || empty($CronVars[2])):
                unlink($CronJobFile);
            elseif ($CronVars[2] != BASE):
                unlink($CronJobFile);
            elseif ($CronVars[1] != $LicenceDomi):
                $LicenceKey = base64_encode("{$CronVars[0]}(wc){$LicenceDomi}(wc)" . BASE);
                $curl = curl_init("https://www.workcontrol.com.br/licence/register.php?lk={$LicenceKey}&t=" . time() . "&v=" . ADMIN_VERSION);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($curl);
                curl_close($curl);
            endif;
        else:
            $curl = curl_init("https://www.workcontrol.com.br/licence/register.php?wc={$LicenceKey}&t=" . time() . "&v=" . ADMIN_VERSION);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            curl_close($curl);
        endif;

        if (!empty($result) && !empty(json_decode($result)->licence)):
            $cronCrarte = fopen($CronJobFile, "w");
            fwrite($cronCrarte, str_replace("'", '"', json_decode($result)->licence) . "(wc){$LicenceDomi}(wc)" . BASE);
            fclose($cronCrarte);
        elseif (!empty($result) && file_exists($CronJobFile)):
            unlink($CronJobFile);
        endif;
    endif;
endif;