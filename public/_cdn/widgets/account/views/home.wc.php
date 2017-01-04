<?php

if (empty($_SESSION['userLogin'])):
    die('<h1 style="padding: 50px 0; text-align: center; font-size: 3em; font-weight: 300; color: #C63D3A">Acesso Negado!</h1>');
endif;

echo "<div class='workcontrol_account_view'>";
echo "<p class='wc_account_title'><span>Meus Dados:</span><p>";
echo "<div class='workcontrol_account_home'>";
echo "<p><b>Nome: </b>{$user_name} {$user_lastname}</p>";
echo "<p><b>E-mail: </b>{$user_email}</p>";
echo "<p><b>CPF: </b>{$user_document}</p>";
echo "<p><b>Sexo: </b>" . ($user_genre == 1 ? 'Masculino' : ($user_genre == 2 ? 'Feminino' : null)) . "</p>";
echo "<p><b>Telefone: </b>{$user_telephone}</p>";
echo "<p><b>Celular: </b>{$user_cell}</p>";
echo "<p><b>Cadastro em " . date('d/m/Y H\hi', strtotime($user_registration)) . "</b></p>";
echo "</div>";
echo "<div class='wc_spacer'></div>";

if (APP_PRODUCTS):
    $Read->ExeRead(DB_ORDERS, "WHERE user_id = :id ORDER BY order_date DESC LIMIT 5", "id={$user_id}");
    if ($Read->getResult()):
        echo "<p class='wc_account_title'><span>Últimos Pedidos:</span><p>";
        foreach ($Read->getResult() as $Order):
            extract($Order);
            $order_installments = (empty($order_installments) ? 1 : $order_installments);
            $order_installment = (empty($order_installment) ? $order_price : $order_installment);
            echo "<div class='wc_account_order'><p><a title='Ver Pedido' href='{$AccountBaseUI}/pedido/{$order_id}#acc'>#" . str_pad($order_id, 7, 0, 0) . "</a></p><p>" . date('d/m/Y H\hi', strtotime($order_date)) . "</p><p>R$ " . number_format($order_installments * $order_installment, '2', ',', '.') . "</p><p>" . getOrderPayment($order_payment) . "</p><p>" . getOrderStatus($order_status) . "</p></div>";
        endforeach;
    endif;
endif;

echo "</div>";
