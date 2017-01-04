<?php
$AdminLevel = LEVEL_WC_POSTS;
if (!APP_POSTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_CATEGORIES, "WHERE category_title IS NULL AND category_content IS NULL AND category_id >= :st", "st=1");
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-price-tags">Categorias</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/home">Posts</a>
            <span class="crumb">/</span>
            Categorias
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Nova Categoria" href="dashboard.php?wc=posts/category" class="btn btn_green icon-plus">Adicionar Categoria!</a>
    </div>

</header>
<div class="dashboard_content">

    <?php
    $Read->ExeRead(DB_CATEGORIES, "WHERE category_parent IS NULL ORDER BY category_title ASC");
    if (!$Read->getResult()):
        echo Erro("<span class='al_center icon-notification'>Ainda não existem categorias cadastradas {$Admin['user_name']}. Comece agora mesmo criando sua primera seção e então suas categorias!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $Sess):
            echo "<article class='single_category box box100' id='{$Sess['category_id']}'>
                    <header>
                        <h1 class='icon-price-tags'>{$Sess['category_title']}:</h1>
                        <p class='tagline'>" . Check::Words($Sess['category_content'], 60) . "</p>
                        <div class='single_category_actions'>
                            <a target='_blank' title='Ver Categoria!' href='" . BASE . "/artigos/{$Sess['category_name']}' class='btn btn_green icon-eye icon-notext'></a>
                            <a title='Editar Categoria!' href='dashboard.php?wc=posts/category&id={$Sess['category_id']}' class='btn btn_blue icon-pencil icon-notext'></a>
                            <span rel='single_category' class='j_delete_action btn btn_red icon-cancel-circle icon-notext' id='{$Sess['category_id']}'></span>
                            <span rel='single_category' callback='Posts' callback_action='category_remove' class='j_delete_action_confirm btn btn_yellow icon-warning' style='display: none;' id='{$Sess['category_id']}'>Deletar Categoria?</span>
                        </div>
                    </header>";

            $Read->ExeRead(DB_CATEGORIES, "WHERE category_parent = :cid ORDER BY category_title ASC", "cid={$Sess['category_id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $Cat):
                    echo "<article class='box_content single_category_sub' id='{$Cat['category_id']}'>
                            <h1 class='icon-price-tag'>{$Cat['category_title']}</h1>
                            <p class='tagline'>" . Check::Words($Cat['category_content'], 60) . "</p>
                            <div class='single_category_actions'>
                                <a target='_blank' title='Ver Categoria!' href='" . BASE . "/artigos/{$Cat['category_name']}' class='btn btn_green icon-eye icon-notext'></a>
                                <a title='Editar Categoria!' href='dashboard.php?wc=posts/category&id={$Cat['category_id']}' class='btn btn_blue icon-pencil icon-notext'></a>
                                <span rel='single_category_sub' class='j_delete_action btn btn_red icon-cancel-circle icon-notext' id='{$Cat['category_id']}'></span>
                                <span rel='single_category_sub' callback='Posts' callback_action='category_remove' class='j_delete_action_confirm btn btn_yellow icon-warning' style='display: none;' id='{$Cat['category_id']}'>Deletar Categoria?</span>
                            </div>
                        </article>";
                endforeach;
            endif;
            echo "</article>";
        endforeach;
    endif;
    ?>
</div>