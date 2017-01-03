<?php
$AdminLevel = LEVEL_WC_PRODUCTS;
if (!APP_PRODUCTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_PDT_COUPONS, "WHERE cp_coupon IS NULL AND cp_id >= :st", "st=1");
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-ticket">Cupons de Desconto</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/home">Produtos</a>
            <span class="crumb">/</span>
            Cupons de Desconto
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Novo Desconto" href="dashboard.php?wc=products/coupon" class="btn btn_green icon-plus">Adicionar Cupom!</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Pager = new Pager("dashboard.php?wc=products/coupons&page=", "<<", ">>", 3);
    $Pager->ExePager($Page, 12);
    $Read->ExeRead(DB_PDT_COUPONS, "ORDER BY cp_end DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if (!$Read->getResult()):
        $Pager->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Ainda não existem cupons de desconto cadastrados {$Admin['user_name']}. Comece agora mesmo criando uma nova oferta e aumente suas conversões!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $Coupon):
            $Active = ($Coupon['cp_start'] <= date('Y-m-d H:i:s') && $Coupon['cp_end'] >= date('Y-m-d H:i:s') ? 'active' : '');
            echo "<article class='product_coupon {$Active} box box25' id='{$Coupon['cp_id']}'>
            <div class='box_content'>
                <header>
                    <h1 class='icon-ticket'>{$Coupon['cp_discount']}%<span>{$Coupon['cp_coupon']}</span></h1>
                    <p>{$Coupon['cp_title']}</p>
                    <p>Início: " . date("d.m.Y \a\s H\hi", strtotime($Coupon['cp_start'])) . "</p>
                    <p>Término: " . date("d.m.Y \a\s H\hi", strtotime($Coupon['cp_end'])) . "</p>
                    <p>Ativações: " . str_pad($Coupon['cp_hits'], 4, 0, 0) . "</p>
                </header>
                <a title='Editar Cupom!' href='dashboard.php?wc=products/coupon&id={$Coupon['cp_id']}' class='btn btn_blue icon-pencil icon-notext'></a>
                <span rel='product_coupon' class='j_delete_action btn btn_red icon-cancel-circle icon-notext' id='{$Coupon['cp_id']}'></span>
                <span rel='product_coupon' callback='Products' callback_action='coupon_remove' class='j_delete_action_confirm btn btn_yellow icon-warning' style='display: none;' id='{$Coupon['cp_id']}'>Deletar Cupom?</span>
            </div>
        </article>";
        endforeach;
        $Pager->ExePaginator(DB_PDT_COUPONS);
        echo $Pager->getPaginator();
    endif;
    ?>
</div>