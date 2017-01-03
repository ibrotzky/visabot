<?php
$AdminLevel = LEVEL_WC_USERS;
if (!APP_USERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

$Read = new Read;
$LV = 6;

$Search = filter_input_array(INPUT_POST);
if ($Search && $Search['s']):
    $S = urlencode($Search['s']);
    header("Location: dashboard.php?wc=users/search&s={$S}");
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-user-check">Equipe</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=users/home">Usuários</a>
            <span class="crumb">/</span>
            Equipe
        </p>
    </div>

    <div class="dashboard_header_search">
        <form name="searchUsers" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" name="s" placeholder="Pesquisar Cliente:" required/>
            <button class="btn btn_green icon icon-search icon-notext"></button>
        </form>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Pager = new Pager("dashboard.php?wc=users/team&page=", "<<", ">>", 5);
    $Pager->ExePager($Page, 12);
    $Read->ExeRead(DB_USERS, "WHERE user_level >= {$LV} ORDER BY user_name ASC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if (!$Read->getResult()):
        $Pager->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Ainda não existem membros de equipe cadastrados {$Admin['user_name']}. Comece agora mesmo cadastrando um novo membro para seu time!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $Users):
            extract($Users);
            $UserThumb = "../uploads/{$user_thumb}";
            $user_thumb = (file_exists($UserThumb) && !is_dir($UserThumb) ? "uploads/{$user_thumb}" : 'admin/_img/no_avatar.jpg');
            echo "<article class='single_user box box25 al_center'>
                    <div class='panel'>
                        <img alt='Este é {$user_name}' title='Este é {$user_name}' src='../tim.php?src={$user_thumb}&w=600&h=600'/>
                        <h1>{$user_name} {$user_lastname}</h1>
                        <p class='nivel icon-equalizer'>" . getWcLevel($user_level) . "</p>
                        <p class='info icon-envelop'>{$user_email}</p>
                        <p class='info icon-calendar'>Desde " . date('d/m/Y \a\s H\h\si', strtotime($user_registration)) . "</p>
                        <a class='btn btn_green icon-user' href='dashboard.php?wc=users/create&id={$user_id}' title='Gerenciar Usuário!'>Gerenciar Usuário!</a>
                    </div>
                </article>";
        endforeach;

        $Pager->ExePaginator(DB_USERS, "WHERE user_level >= {$LV}");
        echo $Pager->getPaginator();
    endif;
    ?>
</div>