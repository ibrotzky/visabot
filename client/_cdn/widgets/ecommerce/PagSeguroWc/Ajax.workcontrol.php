<?php

session_start();

$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($getPost) || empty($getPost['workcontrol'])):
    die('Acesso Negado!');
endif;

$strPost = array_map('strip_tags', $getPost);
$POST = array_map('trim', $strPost);

$Action = $POST['workcontrol'];
$jSON = null;
unset($POST['workcontrol']);

require '../../../../_app/Config.inc.php';
$Read = new Read;
$Create = new Create;
$Update = new Update;

switch ($Action):
    //CARD DATA
    case 'creditCardData':
        if (empty($_SESSION['wc_payorder'])):
            $jSON['triggerError'] = '<p class="big">&#10008; Erro ao processar pagamento!</p><p class="min">Desculpe mas não foi possível verificar os dados do pedido. Por favor, experimente atualizar a página e tentar novamente!</p>';
        elseif (in_array("", $POST)):
            $jSON['error'] = "<p class='wc_order_error'>&#10008; Preencha esse campo!</p>";
        elseif (strlen(str_replace(" ", "", $POST['cardNumber'])) != 16):
            $jSON['field'] = 'cardNumber';
            $jSON['error'] = "<p class='wc_order_error'>&#10008; O número do cartão não é válido!</p>";
        elseif ($POST['expirationMonth'] < 1 || $POST['expirationMonth'] > 12):
            $jSON['field'] = 'expirationMonth';
            $jSON['error'] = "<p class='wc_order_error'>&#10008; O mês {$POST['expirationMonth']} não existe!</p>";
        elseif ($POST['expirationYear'] < date("y")):
            $jSON['field'] = 'expirationYear';
            $jSON['error'] = "<p class='wc_order_error'>&#10008; O ano deve ser maior que " . date('Y') . "!</p>";
        elseif ($POST['expirationYear'] == date("y") && $POST['expirationMonth'] < date("m")):
            $jSON['triggerError'] = '<p class="big">&#10008; Cartão expirado!</p><p class="min">A data de validade informada para o cartão de crédito é menor que a data atual. <b>Você informou ' . str_pad($POST['expirationMonth'], 2, 0, 0) . '/' . $POST['expirationYear'] . '.</b> Por favor confira esta informação!</p>';
        elseif (mb_strlen($POST['cardName']) < 5 || strpos($POST['cardName'], " ") === false):
            $jSON['field'] = 'cardName';
            $jSON['error'] = "<p class='wc_order_error'>&#10008; Favor informe o nome impresso no cartão!</p>";
        elseif (strlen($POST['cardCVV']) < 3 || strlen($POST['cardCVV']) > 4):
            $jSON['field'] = 'cardCVV';
            $jSON['error'] = "<p class='wc_order_error'>&#10008; O CVV deve ter 3 ou 4 números!</p>";
        elseif (!Check::CPF($POST['cardCPF'])):
            $jSON['field'] = 'cardCPF';
            $jSON['error'] = "<p class='wc_order_error' style='font-size: 1em'>&#10008; O CPF informado não é válido!</p>";
        elseif (!checkdate(explode("/", $POST['cardBirthDate'])[1], explode("/", $POST['cardBirthDate'])[0], explode("/", $POST['cardBirthDate'])[2])):
            $jSON['field'] = 'cardBirthDate';
            $jSON['error'] = "<p class='wc_order_error' style='font-size: 1em'>&#10008; Informe uma data de nascimento válida!</p>";
        else:
            $jSON['success'] = true;
        endif;
        break;

    //CART ADD
    case 'creditCard':
        require '../PagSeguro/PagSeguroLibrary.php';
        $WorkControlPayMentRequest = new PagSeguroDirectPaymentRequest();
        $WorkControlPayMentRequest->setPaymentMode('DEFAULT');
        $WorkControlPayMentRequest->setPaymentMethod('CREDIT_CARD');
        $WorkControlPayMentRequest->setCurrency("BRL");
        $WorkControlPayMentRequest->setReference($_SESSION['wc_payorder']['order_id']);
        $WorkControlPayMentRequest->addParameter("notificationURL", BASE . "/_cdn/widgets/ecommerce/PagSeguroWc/PagSeguroNotify.workcontrol.php");

        //PAYMER
        $Read->ExeRead(DB_USERS, "WHERE user_id = :usrid", "usrid={$_SESSION['wc_payorder']['user_id']}");
        $PayMer = $Read->getResult()[0];
        $PayMer['user_email'] = (PAGSEGURO_ENV == 'sandbox' ? time() . "@sandbox.pagseguro.com.br" : $PayMer['user_email']);
        $WorkControlPayMentRequest->setSender("{$PayMer['user_name']} {$PayMer['user_lastname']}", $PayMer['user_email'], substr($PayMer['user_cell'], 1, 2), substr(str_replace('-', '', $PayMer['user_cell']), 5), 'CPF', str_replace("-", "", $PayMer['user_document']));
        $WorkControlPayMentRequest->setSenderHash($POST['senderHash']);

        //ADDR
        $Read->ExeRead(DB_USERS_ADDR, "WHERE addr_id = :addid", "addid={$_SESSION['wc_payorder']['order_addr']}");
        $PayAddr = $Read->getResult()[0];
        $WorkControlPayMentRequest->setShippingAddress(str_replace('-', '', $PayAddr['addr_zipcode']), $PayAddr['addr_street'], $PayAddr['addr_number'], $PayAddr['addr_complement'], $PayAddr['addr_district'], $PayAddr['addr_city'], $PayAddr['addr_state'], $PayAddr['addr_country']);

        //SHIP
        $ShipmentCost = (!empty($_SESSION['wc_payorder']['order_shipprice']) ? number_format($_SESSION['wc_payorder']['order_shipprice'], '2', '.', '') : 0);
        $WorkControlPayMentRequest->setShippingType(3); //Frete
        $WorkControlPayMentRequest->setShippingCost($ShipmentCost);

        //ORDER ITEM IDENTIFY
        $WorkControlPayMentRequest->addItem($_SESSION['wc_payorder']['order_id'], "Pedido #" . str_pad($_SESSION['wc_payorder']['order_id'], 7, 0, 0), 1, number_format($_SESSION['wc_payorder']['order_price'] - $ShipmentCost, '2', '.', ''));

        //CREDIT CARD
        $creditCardToken = $POST['creditCardToken'];
        $installments = new PagSeguroDirectPaymentInstallment(
                array(
            "quantity" => explode("x", $POST['cardInstallmentQuantity'])[0],
            "value" => number_format(explode("x", $POST['cardInstallmentQuantity'])[1], 2, '.', ''),
            "noInterestInstallmentQuantity" => (ECOMMERCE_PAY_SPLIT_ACN < 2 ? 0 : ECOMMERCE_PAY_SPLIT_ACN)
                )
        );

        $billingAddress = new PagSeguroBilling(
                array(
            'postalCode' => str_replace("-", "", $PayAddr['addr_zipcode']),
            'street' => $PayAddr['addr_street'],
            'number' => $PayAddr['addr_number'],
            'complement' => $PayAddr['addr_complement'],
            'district' => $PayAddr['addr_district'],
            'city' => $PayAddr['addr_city'],
            'state' => $PayAddr['addr_state'],
            'country' => $PayAddr['addr_country']
                )
        );

        $cardCpfClear = ['.', '-'];
        $creditCardData = new PagSeguroCreditCardCheckout(
                array(
            'token' => $creditCardToken,
            'installment' => $installments,
            'billing' => $billingAddress,
            'holder' => new PagSeguroCreditCardHolder(
                    array(
                'name' => $POST['cardName'],
                'birthDate' => $POST['cardBirthDate'],
                'areaCode' => substr($PayMer['user_cell'], 1, 2),
                'number' => substr(str_replace('-', '', $PayMer['user_cell']), 5),
                'documents' => array(
                    'type' => 'CPF',
                    'value' => str_replace($cardCpfClear, "", $POST['cardCPF'])
                )
                    )
            )
                )
        );

        //ORDER SUBMIT
        $WorkControlPayMentRequest->setCreditCard($creditCardData);
        try {
            $credentials = PagSeguroConfig::getAccountCredentials();
            $response = $WorkControlPayMentRequest->register($credentials);
            $UpdateOrder = [
                'order_payment' => 101,
                'order_installments' => explode("x", $POST['cardInstallmentQuantity'])[0],
                'order_installment' => explode("x", $POST['cardInstallmentQuantity'])[1],
                'order_status' => ($response->getStatus()->getValue() == 3 ? 6 : 4),
                'order_code' => $response->getCode(),
                'order_free' => $response->getFeeAmount()
            ];
            $Update->ExeUpdate(DB_ORDERS, $UpdateOrder, "WHERE order_id = :ord", "ord={$_SESSION['wc_payorder']['order_id']}");

            $jSON['resume'] = BASE . "/pedido/obrigado#cart";
        } catch (PagSeguroServiceException $e) {
            $jSON['triggerError'] = '<p class="big">&#10008; Encontramos um erro!</p><p class="min">Por favor, entre em contato via <b>' . SITE_ADDR_PHONE_A . '</b> e informe o erro <b>' . $e->getMessage() . '</b> para o pedido <b>' . $_SESSION['wc_payorder']['order_id'] . '</b>!</p>';
        }
        break;

    case 'billet':
        if (empty($_SESSION['wc_payorder'])):
            $jSON['triggerError'] = '<p class="big">&#10008; Erro ao processar pagamento!</p><p class="min">Desculpe mas não foi possível verificar os dados do pedido. Por favor, experimente atualizar a página e tentar novamente!</p>';
        else:
            require '../PagSeguro/PagSeguroLibrary.php';
            $WorkControlPayMentRequest = new PagSeguroDirectPaymentRequest();
            $WorkControlPayMentRequest->setPaymentMode('DEFAULT');
            $WorkControlPayMentRequest->setPaymentMethod('BOLETO');
            $WorkControlPayMentRequest->setExtraAmount('-1.00');
            $WorkControlPayMentRequest->setCurrency("BRL");
            $WorkControlPayMentRequest->setReference($_SESSION['wc_payorder']['order_id']);
            $WorkControlPayMentRequest->addParameter("notificationURL", BASE . "/_cdn/widgets/ecommerce/PagSeguroWc/PagSeguroNotify.workcontrol.php");

            //PAYMER
            $Read->ExeRead(DB_USERS, "WHERE user_id = :usrid", "usrid={$_SESSION['wc_payorder']['user_id']}");
            $PayMer = $Read->getResult()[0];
            $PayMer['user_email'] = (PAGSEGURO_ENV == 'sandbox' ? time() . "@sandbox.pagseguro.com.br" : $PayMer['user_email']);
            $WorkControlPayMentRequest->setSender("{$PayMer['user_name']} {$PayMer['user_lastname']}", $PayMer['user_email'], substr($PayMer['user_cell'], 1, 2), substr(str_replace('-', '', $PayMer['user_cell']), 5), 'CPF', str_replace("-", "", $PayMer['user_document']));
            $WorkControlPayMentRequest->setSenderHash($POST['senderHash']);

            //ADDR
            $Read->ExeRead(DB_USERS_ADDR, "WHERE addr_id = :addid", "addid={$_SESSION['wc_payorder']['order_addr']}");
            $PayAddr = $Read->getResult()[0];
            $WorkControlPayMentRequest->setShippingAddress(str_replace('-', '', $PayAddr['addr_zipcode']), $PayAddr['addr_street'], $PayAddr['addr_number'], $PayAddr['addr_complement'], $PayAddr['addr_district'], $PayAddr['addr_city'], $PayAddr['addr_state'], $PayAddr['addr_country']);

            //SHIP
            $ShipmentCost = (!empty($_SESSION['wc_payorder']['order_shipprice']) ? number_format($_SESSION['wc_payorder']['order_shipprice'], '2', '.', '') : 0);
            $WorkControlPayMentRequest->setShippingType(3); //Frete
            $WorkControlPayMentRequest->setShippingCost($ShipmentCost);

            //ORDER ITEM IDENTIFY
            $WorkControlPayMentRequest->addItem($_SESSION['wc_payorder']['order_id'], "Pedido #" . str_pad($_SESSION['wc_payorder']['order_id'], 7, 0, 0), 1, number_format($_SESSION['wc_payorder']['order_price'] - $ShipmentCost, '2', '.', ''));

            try {
                $credentials = PagSeguroConfig::getAccountCredentials();
                $response = $WorkControlPayMentRequest->register($credentials);
                $UpdateOrder = [
                    'order_payment' => 102,
                    'order_status' => ($response->getStatus()->getValue() == 3 ? 6 : 4),
                    'order_installments' => 1,
                    'order_installment' => $_SESSION['wc_payorder']['order_price'],
                    'order_code' => $response->getCode(),
                    'order_free' => $response->getFeeAmount(),
                    'order_billet' => $response->getPaymentLink()
                ];
                $Update->ExeUpdate(DB_ORDERS, $UpdateOrder, "WHERE order_id = :ord", "ord={$_SESSION['wc_payorder']['order_id']}");

                $jSON['billet'] = $response->getPaymentLink();
                $jSON['resume'] = BASE . "/pedido/obrigado#cart";
            } catch (PagSeguroServiceException $e) {
                $jSON['triggerError'] = '<p class="big">&#10008; Encontramos um erro!</p><p class="min">Por favor, entre em contato via <b>' . SITE_ADDR_PHONE_A . '</b> e informe o erro <b>' . $e->getMessage() . '</b> para o pedido <b>' . $_SESSION['wc_payorder']['order_id'] . '</b>!</p>';
            }
        endif;
        break;
endswitch;

sleep(1);
echo json_encode($jSON);
