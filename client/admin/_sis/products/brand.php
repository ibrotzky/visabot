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

$BrandId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($BrandId):
    $Read->ExeRead(DB_PDT_BRANDS, "WHERE brand_id = :id", "id={$BrandId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um fabricante que não existe ou que foi removido recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=products/brands');
    endif;
else:
    $Date = date('Y-m-d H:i:s');
    $Title = "Novo Fabricante - {$Date}";
    $Name = Check::Name($Title);
    $CarCreate = ['brand_name' => $Name, 'brand_created' => $Date];
    $Create->ExeCreate(DB_PDT_BRANDS, $CarCreate);
    header('Location: dashboard.php?wc=products/brand&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-price-tags"><?= $brand_title ? $brand_title : 'Nova Marca ou Fabricante'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/home">Produtos</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/brands">Fabricantes</a>
            <span class="crumb">/</span>
            Gerenciar Marca/Fabricante
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Fabricantes!" href="dashboard.php?wc=products/brands" class="btn btn_blue icon-eye">Ver Fabricantes!</a>
        <a title="Novo Fabricantes" href="dashboard.php?wc=products/brand" class="btn btn_green icon-plus">Adicionar Fabricante!</a>
    </div>

</header>

<div class="dashboard_content">
    <div class="box box100">
        
        <div class="panel_header default">
            <h2 class="icon-price-tags">Dados da Marca/Fabricante</h2>
        </div>
        <div class="panel">
            <form class="auto_save" name="category_add" action="" method="post" enctype="multipart/form-data">
                <div class="callback_return"></div>
                <input type="hidden" name="callback" value="Products"/>
                <input type="hidden" name="callback_action" value="brand_manager"/>
                <input type="hidden" name="brand_id" value="<?= $BrandId; ?>"/>
                <label class="label">
                    <span class="legend">Marca/Fabricante:</span>
                    <input style="font-size: 1.5em;" type="text" name="brand_title" value="<?= $brand_title; ?>" placeholder="Nome do Fabricante:" required/>
                </label>

                <div class="m_top">&nbsp;</div>
                <img class="form_load fl_right none" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                <button class="btn btn_green icon-price-tags fl_right">Atualizar Fabricante!</button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>