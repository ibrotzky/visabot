<?php
$AdminLevel = 10;
if (!ADMIN_WC_CUSTOM || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

$Read = new Read;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-accessibility">APPs Personalizados</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Custom WC
        </p>
    </div>
</header>
<div class="dashboard_content custom_app">
    <article class="box box30">
        <header>
            <h1>O que é?</h1>
        </header>
        <div class="box_content">
            <p>A core de APPS personalizados foi criada para que você possa manter o Work Control na versão atual sem maiores problemas.</p>
            <p>Usando uma estrutura de pastas e arquivos independentes, os Updates de versão ficam muito mais simples e intuítivos!</p>
            Tudo fica muito simples assim!
        </div>
    </article>
    <article class="box box70">
        <header>
            <h1>Estrutura:</h1>
        </header>
        <div class="box_content">
            <p>O core custom conta com apenas alguns detalhes. Primeiro perceba a nova pasta <b>_siswc</b> dentro da estrutura. Todos os arquivos que você customizar devem ficar dentro dela. Assim as atualizações de versões nativas não vão afeta-las por estarem isoladas!</p>
            <p>Dentro da pasta <b>_siswc</b> você ainda tem um arquivo de menu (<b>wc_menu.php</b>) que será usado para implementar os links na sidebar do admin. Basta edita-lo seguindo o padrão do menu principal!</p>
            <h2>EXEMPLO:</h2>
            <p>Digamos que você queira implementar funções a APP IMOBI por exemplo. Em vez de personalizar a versão nativa, copie a pasta _sis/<b>imobi</b> para a pasta <b>_siswc</b> e faça sua personalização. Assim seus arquivos ficam seguros.</p>
            <p>O mesmo pode ser feito com os arquivos de AJAX. Em vez de personalizar o nativo, prefira duplicar o mesmo e personalizar este, trocando então o callback na sua APP custom! Os arquivos AJAX podem ser mantidos dentro da pasta padrão sem problemas. Prefira nomes como (no caso) Properties.<b>custom</b>ajax.php<p>
            <h2>RESUMO:</h2>
            <ol>
                <li>Habilite a opção ADMIN_WC_CUSTOM = 1</li>
                <li>Crie a pasta da sua APP dentro de <b>_siswc</b></li>
                <li>Crie os links do menu no arquivo _siswc/<b>wc_menu.php</b></li>
                <li>Mantenha suas APPS seguras!</li>
            </ol>
            Na hora de atualizar isole a pasta _siswc e caso tenha modificações no banco. Instale a versão atual e restaure os dados do banco assim como a pasta _siswc.
        </div>
    </article>
</div>