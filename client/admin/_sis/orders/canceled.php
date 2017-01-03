<?php
$AdminLevel = LEVEL_WC_PRODUCTS_ORDERS;
if (!APP_ORDERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$Search = filter_input_array(INPUT_POST);
if ($Search && $Search['s']):
    $s = intval($Search['s']);
    $Read->FullRead("SELECT order_id FROM " . DB_ORDERS . " WHERE order_id = :order", "order={$s}");
    if ($Read->getResult()):
        header("Location: dashboard.php?wc=orders/order&id={$s}");
    else:
        $_SESSION['trigger_controll'] = "Desculpe {$Admin['user_name']}, mas não existe o pedido {$Search['s']}!";
        header('Location: dashboard.php?wc=orders/canceled');
    endif;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-cart">Pedidos Cancelados</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=orders/home">Pedidos</a>
            <span class="crumb">/</span>
            Pedidos Cancelados
        </p>
    </div>

    <div class="dashboard_header_search">
        <form name="searchOrders" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" name="s" placeholder="Pesquisar Pedido:" required/>
            <button class="btn btn_green icon icon-search icon-notext"></button>
        </form>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box100">
        
        <div class="panel_header default">
            <h2 class="icon-cart">Pedidos Cancelados</h2>
        </div>
        <div class="panel">
            <?php
            $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
            $Page = ($getPage ? $getPage : 1);
            $Pager = new Pager("dashboard.php?wc=orders/home&page=", "<<", ">>", 5);
            $Pager->ExePager($Page, 15);
            $Read->ExeRead(DB_ORDERS, "WHERE order_status = 2 ORDER BY order_status DESC, order_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
            if (!$Read->getResult()):
                $Pager->ReturnPage();
                echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}. Ainda não existem pedidos cancelados :)</span>", E_USER_NOTICE);
            else:
                foreach ($Read->getResult() as $Order):
                    $Read->FullRead("SELECT user_name, user_lastname FROM " . DB_USERS . " WHERE user_id = :user", "user={$Order['user_id']}");
                    $Client = ($Read->getResult() ? "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}" : 'N/A');
                    echo "<article class='single_order'>
                        <p class='coll coll_r icon-cart'><b><a class='order' href='dashboard.php?wc=orders/order&id={$Order['order_id']}' title='Ver Pedido'>" . str_pad($Order['order_id'], 7, 0, STR_PAD_LEFT) . "</a></b></p>
                        <p class='coll'>{$Client}</p>                        
                        <p class='coll'>" . date('d/m/Y H\hi', strtotime($Order['order_date'])) . "</p>
                        <p class='coll'>R$ " . number_format($Order['order_price'], '2', ',', '.') . "</p>
                        <p class='coll'>" . getOrderPayment($Order['order_payment']) . "</p>
                        <p class='coll'>" . getOrderStatus($Order['order_status']) . "</p>
                        <p class='coll coll_r'><b><a href='dashboard.php?wc=orders/order&id={$Order['order_id']}' title='Ver Pedido' class='see'>Ver</a></b></p>
                </article>";
                endforeach;
            endif;

            $Pager->ExePaginator(DB_ORDERS, "WHERE order_status = 2");
            echo $Pager->getPaginator();
            ?>
            <div class="clear"></div>
        </div>
    </div>
</div>