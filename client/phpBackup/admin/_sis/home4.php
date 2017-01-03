<?php
$AdminLevel = 6;
if (!APP_EAD || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$Read->FullRead("SELECT COUNT(online_id) AS TotalOnline FROM " . DB_VIEWS_ONLINE . " WHERE online_endview >= NOW() AND online_user IN(SELECT user_id FROM " . DB_EAD_ENROLLMENTS . ")");
$StudentOnline = str_pad($Read->getResult()[0]['TotalOnline'], 4, 0, 0);

$Read->FullRead("SELECT COUNT(user_id) AS TotalStudent FROM " . DB_USERS . " WHERE user_id IN(SELECT user_id FROM " . DB_EAD_ENROLLMENTS . ")");
$StudentCount = str_pad($Read->getResult()[0]['TotalStudent'], 4, 0, 0);

$Read->FullRead("SELECT COUNT(online_id) AS OnlineNow FROM " . DB_VIEWS_ONLINE . " WHERE online_endview >= NOW()");
$OnlineNow = str_pad($Read->getResult()[0]['OnlineNow'], 4, 0, 0);
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-home">Dashboard</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
        </p>
    </div>
</header>

<div class="dashboard_content">
    
    <div class="box box25">
        <div class="panel_header warning">
            <h2 class="icon icon-users">Alunos: <a title="Online Agora!" class="icon-earth fl_right btn_link wc_useronline" style="clear: both" href="dashboard.php?wc=onlinenow"><?= $OnlineNow; ?></a></h2>
        </div>
        <div class="panel">
            <p class="wc_ead_dashteach"><b class="wc_ead_dashteach_count wc_studentonline"><?= $StudentOnline; ?></b>ONLINE DE <b><?= $StudentCount; ?></b> ATIVOS</p>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header info">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-loop icon-notext" id="loopDashboard"></a>
            </span>
            <h2 class="icon icon-stats-dots">Visitas:</h2>
        </div>
        <div class="panel dashboard_stats">
            <?php
            $Read->FullRead("SELECT sum(views_users) AS users, sum(views_views) AS views, sum(views_pages) AS pages FROM " . DB_VIEWS_VIEWS . " WHERE year(views_date) = year(NOW()) AND month(views_date) = month(NOW())");
            if (!$Read->getResult()):
                echo "<p>0000<span>Usuários</span></p>";
                echo "<p>0000<span>Visitas</span></p>";
                echo "<p>0000<span>Páginas</span></p>";
                echo "<h3 class='icon-shuffle'>0.00 Páginas por Visita</h3>";
            else:
                $mViews = $Read->getResult()[0];
                $Stats = (!empty($mViews['pages']) ? number_format($mViews['pages'] / $mViews['views'], 2, '.', '') : '0.00');
                echo "<p>" . str_pad($mViews['users'], 4, 0, STR_PAD_LEFT) . "<span>Usuários</span></p>";
                echo "<p>" . str_pad($mViews['views'], 4, 0, STR_PAD_LEFT) . "<span>Visitas</span></p>";
                echo "<p>" . str_pad($mViews['pages'], 4, 0, STR_PAD_LEFT) . "<span>Páginas</span></p>";
                echo "<h3 class='icon-shuffle'>{$Stats} Páginas por Visita</h3>";
            endif;
            ?>
            <div class="clear"></div>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header alert">
            <h2 class="icon icon-filter">Pedidos:</h2>
        </div>
        <div class="panel dashboard_stats">
            <?php
            $Read->FullRead("SELECT COUNT(order_id) as OrderNow FROM " . DB_EAD_ORDERS . " WHERE DATE(order_purchase_date) = DATE(NOW())");
            $OrderNow = str_pad($Read->getResult()[0]['OrderNow'], 4, 0, 0);

            $Read->FullRead("SELECT COUNT(order_id) as OrderNow FROM " . DB_EAD_ORDERS . " WHERE WEEKOFYEAR(order_purchase_date) = WEEKOFYEAR(NOW())");
            $OrderWeek = str_pad($Read->getResult()[0]['OrderNow'], 4, 0, 0);

            $Read->FullRead("SELECT COUNT(order_id) as OrderNow FROM " . DB_EAD_ORDERS . " WHERE YEAR(order_purchase_date) = YEAR(NOW()) AND MONTH(order_purchase_date) = MONTH(NOW())");
            $OrderMonth = str_pad($Read->getResult()[0]['OrderNow'], 4, 0, 0);

            $Read->FullRead("SELECT COUNT(order_id) as OrderNow FROM " . DB_EAD_ORDERS);
            $OrderTotal = str_pad($Read->getResult()[0]['OrderNow'], 4, 0, 0);

            echo "<p>{$OrderNow}<span>HOJE</span></p>";
            echo "<p>{$OrderWeek}<span>SEMANA</span></p>";
            echo "<p>{$OrderMonth}<span>MÊS</span></p>";
            echo "<h3 class='icon-shuffle'>{$OrderTotal} Pedidos no Total</h3>";
            ?>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header success">
            <h2 class="icon icon-coin-dollar">Financeiro:</h2>
        </div>
        <div class="panel">
            <?php
            $Read->FullRead("SELECT SUM(order_cms_vendor) as OrderCms FROM " . DB_EAD_ORDERS . " WHERE order_status IN('approved', 'completed') AND (order_currency IS NULL OR order_currency = 'BRL') AND YEAR(order_purchase_date) = YEAR(NOW()) AND MONTH(order_purchase_date) = MONTH(NOW())");
            $OrderCms = number_format($Read->getResult()[0]['OrderCms'], 2, ',', '.');

            $Read->FullRead("SELECT SUM(order_cms_vendor) as OrderCurrency FROM " . DB_EAD_ORDERS . " WHERE order_status IN('approved', 'completed') AND order_currency != 'BRL' AND YEAR(order_purchase_date) = YEAR(NOW()) AND MONTH(order_purchase_date) = MONTH(NOW())");
            $OrderCurrency = number_format($Read->getResult()[0]['OrderCurrency'], 2, ',', '.');
            ?>
            <p class="wc_ead_dashstatus" style="text-transform: uppercase;">R$ <?= $OrderCms; ?><span>$ <?= $OrderCurrency; ?> em outras moedas! <b><?= date('m/Y'); ?></b></span></p>
        </div>
    </div>


    <div class="box box50">
        <div class="panel_header default">
            <h2 class="icon icon-users">Últimos Alunos:</h2>
        </div>
        <div class="panel">
            <?php
            $Read->ExeRead(DB_USERS, "ORDER BY user_registration DESC LIMIT 5");
            if (!$Read->getResult()):
                Erro("Ainda não foram identificados alunos em sua plataforma EAD!", E_USER_NOTICE);
            else:
                foreach ($Read->getResult() as $Study):
                    extract($Study);

                    $UserThumb = "../uploads/{$user_thumb}";
                    $user_thumb = (file_exists($UserThumb) && !is_dir($UserThumb) ? "uploads/{$user_thumb}" : 'admin/_img/no_avatar.jpg');

                    echo "<article class='wc_ead_dashstudent'>";
                    echo "<img src='" . BASE . "/tim.php?src={$user_thumb}' alt='{$user_name} {$user_lastname}' title='{$user_name} {$user_lastname}'/>";
                    echo "<h1>{$user_name} {$user_lastname} <span>{$user_email}</span></h1>";
                    echo "<p>" . date('d/m/y H\hi', strtotime($user_registration)) . "</p>";
                    echo "<p><a class='btn btn_green' href='dashboard.php?wc=teach/students_gerent&id={$user_id}' title='{$user_name} {$user_lastname}'><b>VER ALUNO</b></a></p>";
                    echo "</article>";
                endforeach;
            endif;
            ?>
            <div class="clear"></div>
        </div>
    </div>

    <div class="box box50">
        <div class="panel_header default">
            <h2 class="icon icon-filter">Últimos Pedidos:</h2>
        </div>
        <div class="panel">
            <?php
            $Read->ExeRead(DB_EAD_ORDERS, "ORDER BY order_purchase_date DESC LIMIT 5");
            if (!$Read->getResult()):
                echo "<div class='trigger trigger_info trigger_none icon-info al_center'>Ainda não existem pedidos registrados!</div><div class='clear'></div>";
            else:
                foreach ($Read->getResult() as $EADOrders):
                    $EADOrders['order_currency'] = ($EADOrders['order_currency'] ? $EADOrders['order_currency'] : 'BRL');

                    $Read->LinkResult(DB_USERS, "user_id", $EADOrders['user_id'], 'user_id, user_name, user_lastname');
                    $OrderUser = $Read->getResult()[0];

                    $Read->LinkResult(DB_EAD_COURSES, "course_id", $EADOrders['course_id']);
                    $OderCourse = ($Read->getResult() ? $Read->getResult()[0]['course_title'] : "Produto #{$EADOrders['order_product_id']} na Hotmart");

                    echo "<article class='wc_ead_dashsign'>";
                    echo "<h1><a title='Pedido " . getWcHotmartStatus($EADOrders['order_status']) . "!' class='btn_" . getWcHotmartStatusClass($EADOrders['order_status']) . "' href='dashboard.php?wc=teach/orders_gerent&id={$EADOrders['order_id']}#orders'>" . str_pad($EADOrders['order_id'], 5, 0, 0) . "</a></h1>";
                    echo "<p><a href='dashboard.php?wc=teach/students_gerent&id={$OrderUser['user_id']}'>{$OrderUser['user_name']} {$OrderUser['user_lastname']}</a> " . Check::Chars($OderCourse, 25) . "</p>";
                    echo "<p class='item'><span style='font-size: 1em;' class='icon-calendar'>" . date('d/m/Y H\hi', strtotime($EADOrders['order_purchase_date'])) . "</span> <b class='icon-coin-dollar'>" . number_format($EADOrders['order_cms_vendor'], 2, ',', '.') . "</b>&nbsp;&nbsp;({$EADOrders['order_currency']})&nbsp;&nbsp;&nbsp;<img alt='' title='' width='20' src='" . BASE . "/_cdn/bootcss/images/pay_{$EADOrders['order_payment_type']}.png'/></p>";
                    echo "</article>";
                endforeach;
            endif;
            ?>
        </div>
    </div>
</div>

<script>
    //ICON REFRESH IN DASHBOARD
    $('#loopDashboard').click(function(){
        Dashboard();
    });
    
    //DASHBOARD REALTIME
    setInterval(function () {
        Dashboard();
    }, 10000);
</script>