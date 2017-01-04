<?php
$AdminLevel = LEVEL_WC_PRODUCTS;
if (!APP_PRODUCTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

$Search = filter_input_array(INPUT_POST);
if ($Search && $Search['s']):
    $S = urlencode($Search['s']);
    header("Location: dashboard.php?wc=products/search&s={$S}");
endif;

$GetSearch = filter_input(INPUT_GET, 's', FILTER_DEFAULT);
$ThisSearch = urldecode($GetSearch);
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-search">Pesquisar Produtos:</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/home">Produtos</a>
            <span class="crumb">/</span>
            Pesquisa
        </p>
    </div>

    <div class="dashboard_header_search">
        <form name="searchPdts" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" name="s" value="<?= htmlspecialchars($ThisSearch); ?>" placeholder="Pesquisar Produto:" required/>
            <button class="btn btn_green icon icon-search icon-notext"></button>
        </form>
    </div>

</header>
<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=products/search&s={$GetSearch}&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;
    
    $Read->ExeRead(DB_PDT, "WHERE pdt_title LIKE '%' :s '%' OR pdt_content LIKE '%' :s '%' OR pdt_code = :s ORDER BY pdt_created DESC LIMIT :limit OFFSET :offset", "s={$ThisSearch}&limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}. Sua pesquisa para {$ThisSearch} não obteve resultados. Você pode tentar outros termos!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $Products):
            extract($Products);
            $PdtImage = ($pdt_cover && file_exists("../uploads/{$pdt_cover}") && !is_dir("../uploads/{$pdt_cover}") ? "uploads/{$pdt_cover}" : 'admin/_img/no_image.jpg');
            $PdtTitle = ($pdt_title ? Check::Chars($pdt_title, 45) : 'Edite este produto para coloca-lo a venda!');
            $PdtCode = ($pdt_code ? $pdt_code : 'indefinido');
            $PdtClass = ($pdt_status != 1 ? 'inactive' : (is_numeric($pdt_inventory) && $pdt_inventory <= 0 ? 'outsale' : ''));
            echo "<article class='box box25 single_pdt {$PdtClass}' id='{$pdt_id}'>
            <div class='single_pdt_thumb'>
            <img title='{$PdtTitle}' alt='{$PdtTitle}' src='../tim.php?src={$PdtImage}&w=" . THUMB_W . "&h=" . THUMB_H . "'/>
                <header>
                    <h1><a target='_blank' href='" . BASE . "/produto/{$pdt_name}' title='Ver {$PdtTitle} no site'>{$PdtTitle}</a></h1>";

            if ($pdt_offer_price && strtotime($pdt_offer_start) <= time() && strtotime($pdt_offer_end) >= time()):
                echo "<p class='tagline'><span class='offer'>de <strike>R$ " . number_format($pdt_price, "2", ",", ".") . "</strike> por</span>R$ " . number_format($pdt_offer_price, "2", ",", ".") . "</p>";
            else:
                echo "<p class='tagline'><span class='offer'>por apenas</span>R$ " . number_format($pdt_price, "2", ",", ".") . "</p>";
            endif;

            $Read->FullRead("SELECT brand_title FROM " . DB_PDT_BRANDS . " WHERE brand_id = :bid", "bid={$pdt_brand}");
            $Brand = ($Read->getResult() ? $Read->getResult()[0]['brand_title'] : 'indefinida');

            $Read->FullRead("SELECT cat_title FROM " . DB_PDT_CATS . " WHERE cat_id = :cat", "cat={$pdt_category}");
            $Category = ($Read->getResult() ? $Read->getResult()[0]['cat_title'] : 'indefinida');

            $Read->FullRead("SELECT cat_title FROM " . DB_PDT_CATS . " WHERE cat_id = :cat", "cat={$pdt_subcategory}");
            $SubCategory = ($Read->getResult() ? $Read->getResult()[0]['cat_title'] : 'indefinida');

            $PdtSoldVar = null;
            $PdtStockVar = null;
            $Read->FullRead("SELECT stock_code, stock_inventory, stock_sold FROM " . DB_PDT_STOCK . " WHERE pdt_id = :id", "id={$pdt_id}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $StockVarKey):
                    if ($StockVarKey['stock_code'] != 'default'):
                        $PdtSoldVar .= " | {$StockVarKey['stock_code']}: {$StockVarKey['stock_sold']}";
                        $PdtStockVar .= " | {$StockVarKey['stock_code']}: {$StockVarKey['stock_inventory']}";
                    endif;
                endforeach;
            else:
                //RETRO COMPATIBILIDADE WC
                $CreateStock = ['pdt_id' => $pdt_id, 'stock_code' => 'default', 'stock_inventory' => $pdt_inventory, 'stock_sold' => ($pdt_delivered ? $pdt_delivered : 0)];
                $Create->ExeCreate(DB_PDT_STOCK, $CreateStock);
            endif;

            echo "</header>
            </div>
            <div class='box_content'>
                <div class='single_pdt_info'>
                    <p>Código: <b>{$PdtCode}</b></p>
                    <p>Vendas: <b>" . str_pad($pdt_delivered, 3, 0, STR_PAD_LEFT) . "</b>{$PdtSoldVar}</p>
                    <p>Estoque: <b>" . (is_numeric($pdt_inventory) ? ($pdt_inventory >= 1 ? str_pad($pdt_inventory, 3, 0, STR_PAD_LEFT) : str_pad($pdt_inventory, 3, 0, STR_PAD_LEFT)) : "+100") . "</b>{$PdtStockVar}</p>
                    <p>Fabricante: <b>{$Brand}</b></p>
                    <p>Em: <b>{$Category}</b> &raquo; <b>{$SubCategory}</b></p>
                </div>
                <a title='Ver produto no site' target='_blank' href='" . BASE . "/produto/{$pdt_name}' class='icon-notext icon-eye btn btn_green'></a>
                <a title='Editar produto' href='dashboard.php?wc=products/create&id={$pdt_id}' class='post_single_center icon-notext icon-pencil btn btn_blue'></a>
                <span rel='single_pdt' class='j_delete_action icon-notext icon-cancel-circle btn btn_red' id='{$pdt_id}'></span>
                <span rel='single_pdt' callback='Products' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$pdt_id}'>Remover Produto?</span>
            </div>
        </article>";
        endforeach;

        $Paginator->ExePaginator(DB_PDT, "WHERE pdt_title LIKE '%' :s '%' OR pdt_content LIKE '%' OR pdt_code = :s", "s={$ThisSearch}");
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>