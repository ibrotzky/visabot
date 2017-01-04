<?php
$AdminLevel = LEVEL_WC_POSTS;
if (!APP_POSTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$CatId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($CatId):
    $Read->ExeRead(DB_CATEGORIES, "WHERE category_id = :id", "id={$CatId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['user_name']}</b>, você tentou editar uma categoria que não existe ou que foi removida recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=posts/categories');
    endif;
else:
    $Date = date('Y-m-d H:i:s');
    $Title = "Nova Categoria - {$Date}";
    $Name = Check::Name($Title);
    $PostCreate = ['category_name' => $Name, 'category_date' => $Date];
    $Create->ExeCreate(DB_CATEGORIES, $PostCreate);
    header('Location: dashboard.php?wc=posts/category&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-price-tags"><?= $category_title ? $category_title : 'Nova Categoria'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/home">Posts</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/categories">Categorias</a>
            <span class="crumb">/</span>
            Gerenciar Categoria
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Categorias!" href="dashboard.php?wc=posts/categories" class="btn btn_blue icon-eye">Ver Categorias!</a>
        <a title="Nova Categoria" href="dashboard.php?wc=posts/category" class="btn btn_green icon-plus">Adicionar Categoria!</a>
    </div>
</header>

<div class="dashboard_content">
    
    <div class="box box100">
        <div class="panel_header default">
            <h2 class="icon-price-tags">Dados da Categoria</h2>
        </div>
        <div class="panel">
            
            <form class="auto_save" name="category_add" action="" method="post" enctype="multipart/form-data">
                <div class="callback_return"></div>
                <input type="hidden" name="callback" value="Posts"/>
                <input type="hidden" name="callback_action" value="category_add"/>
                <input type="hidden" name="category_id" value="<?= $CatId; ?>"/>
                
                <label class="label">
                    <span class="legend">Nome:</span>
                    <input style="font-size: 1.5em;" type="text" name="category_title" value="<?= $category_title; ?>" placeholder="Título da Categoria:" required/>
                </label>

                <label class="label">
                    <span class="legend">Descrição:</span>
                    <textarea style="font-size: 1.2em;" name="category_content" rows="3" placeholder="Sobre a Categoria:" required><?= $category_content; ?></textarea>
                </label>

                <label class="label">
                    <span class="legend">Seção:</span>
                    <select name="category_parent">
                        <option value="">Essa é uma Seção!</option>
                        <?php
                        $Read->FullRead("SELECT category_id, category_title FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL AND category_id != :ci ORDER BY category_title ASC", "ci={$CatId}");
                        if ($Read->getResult()):
                            foreach ($Read->getResult() as $Sess):
                                echo "<option";
                                if ($Sess['category_id'] == $category_parent):
                                    echo " selected='selected'";
                                endif;
                                echo " value='{$Sess['category_id']}'>&raquo;{$Sess['category_title']}</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </label>

                <div class="m_top">&nbsp;</div>
                <img class="form_load fl_right none" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                <button class="btn btn_green icon-price-tags fl_right">Atualizar Categoria!</button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>