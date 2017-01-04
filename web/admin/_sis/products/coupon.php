<?php
$AdminLevel = LEVEL_WC_PRODUCTS;
if (!APP_PRODUCTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$CouponId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($CouponId):
    $Read->ExeRead(DB_PDT_COUPONS, "WHERE cp_id = :id", "id={$CouponId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um cupom de desconto que não existe ou que foi removido recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=products/coupons');
    endif;
else:
    $CarCreate = ['cp_start' => date("Y-m-d H:i:s"), 'cp_end' => date("Y-m-d H:i:s", strtotime("+30day"))];
    $Create->ExeCreate(DB_PDT_COUPONS, $CarCreate);
    header('Location: dashboard.php?wc=products/coupon&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-ticket"><?= $cp_title ? $cp_title : 'Novo Cupom'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/home">Produtos</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/coupons">Cupons</a>
            <span class="crumb">/</span>
            Gerenciar Desconto
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Cupons!" href="dashboard.php?wc=products/coupons" class="btn btn_blue icon-eye">Cupons de Desconto!</a>
    </div>

</header>

<div class="dashboard_content">
    <div class="box box100">
        
        <div class="panel_header default">
            <h2 class="icon-ticket">Dados do Cupom</h2>
        </div>
        
        <div class="panel">
            <form class="auto_save" name="cupom_add" action="" method="post" enctype="multipart/form-data">
                <div class="callback_return"></div>
                <input type="hidden" name="callback" value="Products"/>
                <input type="hidden" name="callback_action" value="cupom_manage"/>
                <input type="hidden" name="cp_id" value="<?= $cp_id; ?>"/>
                <label class="label">
                    <span class="legend">Título do Cupom:</span>
                    <input style="font-size: 1.5em;" type="text" name="cp_title" value="<?= $cp_title; ?>" placeholder="Ex: Desconto de natal" required/>
                </label>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Código do Cupom:</span>
                        <input style="font-size: 1.5em;" type="text" name="cp_coupon" value="<?= $cp_coupon; ?>" placeholder="Ex: Desconto de natal" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Porcentagem de desconto:</span>
                        <input style="font-size: 1.5em;" type="number" min="1" max="100" name="cp_discount" value="<?= $cp_discount; ?>" placeholder="Ex: 20" required/>
                    </label>
                </div>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Início da Oferta:</span>
                        <input style="font-size: 1.5em;" type="text" class="formTime" name="cp_start" value="<?= date("d/m/Y H:i", strtotime($cp_start)); ?>" placeholder="dd/mm/yyyy hh:mm" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Término da Oferta:</span>
                        <input style="font-size: 1.5em;" type="text" class="formTime" name="cp_end" value="<?= date("d/m/Y H:i", strtotime($cp_end)); ?>" placeholder="dd/mm/yyyy hh:mm" required/>
                    </label>
                </div>

                <div class="m_top">&nbsp;</div>
                <img class="form_load fl_right none" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                <button class="btn btn_green icon-price-tags fl_right">Atualizar Cupom!</button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>