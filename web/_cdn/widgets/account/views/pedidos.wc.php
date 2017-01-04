<?php

if (empty($_SESSION['userLogin']) || !APP_PRODUCTS):
    die('<h1 style="padding: 50px 0; text-align: center; font-size: 3em; font-weight: 300; color: #C63D3A">Acesso Negado!</h1>');
endif;

echo "<div class='workcontrol_account_view'>";
echo "<p class='wc_account_title'><span>Meus Pedidos:</span><p>";

$Page = (!empty($URL[2]) ? $URL[2] : 1);
$Pager = new Pager("{$AccountBaseUI}/pedidos/", "<<", ">>", 2);
$Pager->ExePager($Page, 10);
$Read->ExeRead(DB_ORDERS, "WHERE user_id = :id ORDER BY order_date DESC LIMIT :limit OFFSET :offset", "id={$user_id}&limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
if (!$Read->getResult()):
    $Pager->ReturnPage();
    Erro("<b>Você ainda não possui pedidos em nosso site!</b>");
else:
    foreach ($Read->getResult() as $Order):
        extract($Order);
        $order_installments = (empty($order_installments) ? 1 : $order_installments);
        $order_installment = (empty($order_installment) ? $order_price : $order_installment);
        echo "<div class='wc_account_order'><p><a title='Ver Pedido' href='{$AccountBaseUI}/pedido/{$order_id}#acc'>#" . str_pad($order_id, 7, 0, 0) . "</a></p><p>" . date('d/m/Y H\hi', strtotime($order_date)) . "</p><p>R$ " . number_format($order_installments * $order_installment, '2', ',', '.') . "</p><p>" . getOrderPayment($order_payment) . "</p><p>" . getOrderStatus($order_status) . "</p></div>";
    endforeach;
endif;

$Pager->ExePaginator(DB_ORDERS, "WHERE user_id = :id", "id={$user_id}", "#acc");
echo $Pager->getPaginator("acc");
echo "</div>";
