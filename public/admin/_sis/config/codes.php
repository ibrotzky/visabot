<?php
$AdminLevel = LEVEL_WC_CONFIG_CODES;
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
        <h1 class="icon-cog">Work Control Codes</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=config/home">Configurações</a>
            <span class="crumb">/</span>
            Codes
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Cadastrar Codes" href="#" class="jwc_codes_create btn btn_green icon-plus">Cadastrar WC CODE</a>
    </div>
</header>
<div class="dashboard_content">
    
    <div class="wc_codes_create">
        <form class="wc_codes_create_form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Codes"/>
            <input type="hidden" name="callback_action" value="workcodes"/>
            <input type="hidden" name="code_id" value=""/>

            <p class="wc_codes_create_form_title">Novo Code Work Control:</p>
            <label>
                <span>Título de Identificação: (ex: Google analitycs)</span>
                <input type="text" name="code_name" placeholder="Título:" required/>
            </label>
            <label>
                <span>Carregar somente em: (opcional)</span>
                <input type="text" name="code_condition" placeholder="Caminho ou Caminho/Argumento:"/>
            </label>
            <label>
                <span>Script, Código do Pixel:</span>
                <textarea required name="code_script" rows="9" placeholder="<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-00000000000-1', 'auto');
    ga('send', 'pageview');
</script>"></textarea>
            </label>

            <div class="wc_codes_create_form_actions">
                <span class="icon icon-cancel-circle btn btn_red jwc_codes_close">Fechar</span>
                <button class="icon icon-cogs btn btn_green">Cadastrar Wc Code</button>
                <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
            </div>
        </form>
    </div>

    <?php
    $Read->ExeRead(DB_WC_CODE, "ORDER BY code_created DESC");
    if (!$Read->getResult()):
        echo Erro("<span class='al_center icon-notification'>Ainda não existem Codes cadastrados {$Admin['user_name']}. Comece a reastrar seus resultados agora cadastrando seus codes!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $CODE):
            extract($CODE);
            ?>
            <article class="box box25 code_single" id="<?= $code_id; ?>">
                <header>
                    <h1><?= $code_name; ?></h1>
                </header>
                <div class="box_content al_center" id="<?= $code_id; ?>">
                    <div class="codes_loaded"><?= date('d/m/Y H:i:s', strtotime($code_created)); ?><span><?= str_pad($code_views, 5, 0, 0); ?></span><?= (!empty($code_condition) ? $code_condition : '√ Pixel carregado sempre e em todas as páginas do site!'); ?></div>
                    <span class="btn btn_green jwc_codes_edit" id="<?= $code_id; ?>">Editar</span>
                    <span rel="code_single" class="j_delete_action icon-notext icon-cancel-circle btn btn_red" id="<?= $code_id; ?>"></span>
                    <span rel="code_single" callback="Codes" callback_action="delete" class="j_delete_action_confirm icon-warning btn btn_yellow" style="display: none" id="<?= $code_id; ?>">Excluir agora?</span>
                </div>
            </article>
            <?php
        endforeach;
    endif;
    ?>
</div>