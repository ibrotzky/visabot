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
        <h2 class="icon-command">Box's</h2>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=samples/teste">Sistema de Teste</a>
        </p>
    </div>
</header>
<!-- Finaliza a navegação do usuário -->

<!-- Inicializa o STAGE, o palco de onde será desenvolvida a aplicação -->
<div class="dashboard_content">
</div>