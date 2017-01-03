<?php
$AdminLevel = LEVEL_WC_CONFIG_API;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-cog">Work Control API</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=config/home">Configurações</a>
            <span class="crumb">/</span>
            API
        </p>
    </div>
</header>
<div class="dashboard_content">
    <div class="wc_api_new">
        <form action="" method="post" enctype="multipart/form-data">
            <div style="text-align: center"><img class="form_load none" style="margin: 0 auto 20px auto;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/></div>
            <input type="hidden" name="callback" value="Api"/>
            <input type="hidden" name="callback_action" value="create"/>
            <input required type="text" name="api_key" value="" placeholder="Api Key, Ex: www.workcontrol.com.br"/><button class="btn btn_green">Criar APP</button>
        </form>
        <p class="wc_api_new_info">( ! ) Sua Key deve identificar o uso da API e por isso aconselhamos sempre definir a mesma com o link do site que vai consumir os dados. <a href="https://github.com/RobsonVLeite/WorkControlAPI" target="_blank">Classe de consumo</a></p>
    </div>
    <?php
    $Read->ExeRead(DB_WC_API, "ORDER BY api_date DESC");
    if (!$Read->getResult()):
        Erro("<div style='text-align: center;' class='icon-notification'>Ainda não existem APPs cadastrados para consumo de API. Você pode começar agora!</div>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $APP):
            extract($APP);
            ?>
            <article class="box box50 wc_api_app api_single" id="<?= $api_id; ?>">
                <header>
                    <h1 class="icon-power-cord">APP <?= $api_id; ?></h1>
                </header>
                <div class="box_content">
                    <p>
                        <b><span>Key:</span></b> <textarea spellcheck="false" onclick="this.select();" rows="1" style="resize: none;"><?= $api_key; ?></textarea></p><p>
                        <b><span>Token:</span></b> <textarea spellcheck="false" onclick="this.select();" rows="1" style="resize: none;"><?= $api_token; ?></textarea></p><p class="box50">
                        <b><span>Loads:</span></b> <?= str_pad($api_loads, 4, 0, 0); ?></p><p class="box50">
                        <b><span>Último Load:</span></b> <?= date('d/m/y H\hi', strtotime($api_lastload)); ?></p><p class="box50">
                        <b><span>Cadastro:</span></b> <?= date('d/m/y H\hi', strtotime($api_date)); ?></p><p class="box50">
                        <b><span>Status:</span></b> <?= ($api_status == 1 ? '<span class="font_green jwc_status">Ativo</span>' : '<span class="font_red jwc_status">Inativo</span>'); ?>
                    </p>
                    <a target="_blank" class="wc_api_test jwc_api_test" href="<?= BASE; ?>/_api/post.php?key=<?= $api_key; ?>&token=<?= $api_token; ?>&limit=1" title="Testar Post">Testar post via API</a>
                    <div class="wc_api_app_actions">
                        <span rel="api_single" style="<?= ($api_status == 1 ? '' : 'display: none;'); ?>" callback="Api" callback_action="inactive" class="jwc_active_action jwc_inactive icon-checkmark icon-notext btn btn_green" id="<?= $api_id; ?>"></span>
                        <span rel="api_single" style="<?= ($api_status == 1 ? 'display: none;' : ''); ?>" callback="Api" callback_action="active" class="jwc_active_action jwc_active icon-warning icon-notext btn btn_yellow" id="<?= $api_id; ?>"></span>
                        <span rel="api_single" class="j_delete_action icon-notext icon-cancel-circle btn btn_red" id="<?= $api_id; ?>"></span>
                        <span rel="api_single" callback="Api" callback_action="delete" class="j_delete_action_confirm icon-warning btn btn_yellow" style="display: none" id="<?= $api_id; ?>">Deletar App?</span>
                    </div>
                </div>
            </article>
            <?php
        endforeach;
    endif;
    ?>
</div>