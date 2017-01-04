<?php
// Define o nível de permissão de quem pode acessar essa página e evita acesso direto no arquivo.
$AdminLevel = LEVEL_WC_CONFIG_MASTER;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<!-- Inicializa a navegação do usuário -->
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h2 class="icon-command">Painéis</h2>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=samples/panels">Painéis</a>
        </p>
    </div>
</header>
<!-- Finaliza a navegação do usuário -->

<!-- Inicializa o STAGE, o palco de onde será desenvolvida a aplicação -->
<div class="dashboard_content">

   

    <div class="box box100">
        <div class="panel_header default">
            <h2>Botões</h2>
        </div>

        <div class="panel">

            <p>Todas as variações abaixo podem ser aplicadas a elementos do tipo <b>&LT;a></b> ou <b>&LT;button></b> adicionando obrigatoriamente a classe <b>.btn</b> e depois a variação.</p>
            <p>Cores:</p>
            <a href="javascript:void(0)" class="btn">Botão Cor Padrão</a>
            <a href="javascript:void(0)" class="btn btn_green">Botão .btn_green</a>
            <a href="javascript:void(0)" class="btn btn_yellow">Botão .btn_yellow</a>
            <a href="javascript:void(0)" class="btn btn_blue">Botão .btn_blue</a>
            <a href="javascript:void(0)" class="btn btn_red">Botão .btn_red</a>

            <p>Tamanho:</p>
            <a href="javascript:void(0)" class="btn btn_green">Botão Tamanho Padrão</a>
            <a href="javascript:void(0)" class="btn btn_green btn_small">Botão .btn_small</a>
            <a href="javascript:void(0)" class="btn btn_green btn_medium">Botão .btn_medium</a>
            <a href="javascript:void(0)" class="btn btn_green btn_large">Botão .btn_large</a>
            <a href="javascript:void(0)" class="btn btn_green btn_xlarge">Botão .btn_xlarge</a>
            <a href="javascript:void(0)" class="btn btn_green btn_xxlarge">Botão .btn_xxlarge</a>

            <p>Ícone:</p>
            <a href="javascript:void(0)" class="btn btn_yellow icon-IcoMoon">Botão com Ícone .icon-IcoMoon</a>
            <a href="javascript:void(0)" class="btn btn_yellow icon-IcoMoon icon-notext"></a> - icon-notext

            <p>Borda:</p>
            <a href="javascript:void(0)" class="btn btn_blue">Botão Borda Padrão</a>
            <a href="javascript:void(0)" class="btn btn_blue rounded">Botão .rounded</a>
            <a href="javascript:void(0)" class="btn btn_blue icon-calendar icon-notext rounded"></a> - icon-calendar icon-notext rounded

        </div>
    </div>
</div>

<!-- Finaliza o STAGE, o palco de onde será desenvolvida a aplicação -->