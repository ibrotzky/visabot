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
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=samples/boxs">Box's</a>
        </p>
    </div>
</header>
<!-- Finaliza a navegação do usuário -->

<!-- Inicializa o STAGE, o palco de onde será desenvolvida a aplicação -->
<div class="dashboard_content">

    <div class="box box100">
        <div class="panel_header">
            <h2 class="icon-display">CONJUNTOS DE BOX</h2>
            <p class="subtitle">Para trabalhar com o sistema de box é bem simples! Basta que você crie uma div e insira a class box + o tamanho da box que você deseja! Lembre-se que a soma do tamanho das box's deve ser igual a 100! Logo se quiser um painel com 100% de largura crie uma div com as classes box box100, ou se desejar ter dois painéis com 50% de largura cada um, crie duas divs, cada uma delas com as classes box box50 respectivamente.</p>
            <p class="subtitle"><strong>* Atenção:</strong> Somente a box33 a soma não é igual a 100, pois 3 box de 33,33% de largura é igual a 99,99% e esse 1% é descontado na margem.</p>
        </div>
    </div>

    <!-- PRIMEIRA LINHA COMPLETA -->
    <div class="box box100">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box100"></a>
            </span>
            <h2>BOX 100</h2>
        </div>
    </div>
    <!-- PRIMEIRA LINHA COMPLETA -->

    <!-- SEGUNDA LINHA COMPLETA-->
    <div class="box box50">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box50"></a>
            </span>
            <h2>BOX 50</h2>
        </div>
    </div>

    <div class="box box50">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box50"></a>
            </span>
            <h2>BOX 50</h2>
        </div>
    </div>
    <!-- SEGUNDA LINHA COMPLETA-->

    <!-- TERCEIRA LINHA COMPLETA-->
    <div class="box box33">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box33"></a>
            </span>
            <h2>BOX 33</h2>
        </div>
    </div>

    <div class="box box33">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box33"></a>
            </span>
            <h2>BOX 33</h2>
        </div>
    </div>

    <div class="box box33">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box33"></a>
            </span>
            <h2>BOX 33</h2>
        </div>
    </div>
    <!-- TERCEIRA LINHA COMPLETA-->

    <!-- QUARTA LINHA COMPLETA -->
    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>
    <!-- QUARTA LINHA COMPLETA -->

    <div class="box box100">
        <div class="panel_footer_external">
            <p>Box's uniformemente distribuídos</p>
        </div>
    </div>

    <!-- 
        MESCLANDO BOXS
        A SOMA DE TODAS AS BOX DEVE SER IGUAL A 100 
    -->

    <div class="box box100">
        <div class="panel_header">
            <h2 class="icon-display">MESCLANDO CONJUNTOS DE BOX</h2>
            <p class="subtitle">O conceito da soma deve ser 100 assim como nas box uniformes, mas você tem a possibilidade de mesclar diversos tamanhos de painéis para distribuir melhor o seu conteúdo na tela do usuário. Veja os exemplos abaixo!</p>
        </div>
    </div>

    <!-- PRIMEIRA LINHA COMPLETA -->
    <div class="box box30">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box30"></a>
            </span>
            <h2>BOX 30</h2>
        </div>
    </div>

    <div class="box box70">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box70"></a>
            </span>
            <h2>BOX 70</h2>
        </div>
    </div>
    <!-- PRIMEIRA LINHA COMPLETA -->

    <!-- SEGUNDA LINHA COMPLETA -->
    <div class="box box70">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box70"></a>
            </span>
            <h2>BOX 70</h2>
        </div>
    </div>

    <div class="box box30">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box30"></a>
            </span>
            <h2>BOX 30</h2>
        </div>
    </div>
    <!-- SEGUNDA LINHA COMPLETA -->

    <!-- TERCEIRA LINHA COMPLETA -->
    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>

    <div class="box box50">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box50"></a>
            </span>
            <h2>BOX 50</h2>
        </div>
    </div>
    <!-- TERCEIRA LINHA COMPLETA -->

    <!-- QUARTA LINHA COMPLETA -->
    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>

    <div class="box box50">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box50"></a>
            </span>
            <h2>BOX 50</h2>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="box25"></a>
            </span>
            <h2>BOX 25</h2>
        </div>
    </div>
    <!-- QUARTA LINHA COMPLETA -->

    <div class="box box100">
        <div class="panel_footer_external">
            <p>Box's distribuídos de forma irregular</p>
        </div>
    </div>

</div>

<!--
###################################################################################
         ##########      UTILIZADO PARA O CLIPBOARD        #############
                                 NÃO ALTERAR
      O CONTEÚDO DOS TEXT ÁREA É O QUE SERÁ COPIADO PARA A ÁREA DE TRANSFERÊNCIA
###################################################################################
-->
<textarea id="box100" style="position: absolute; left: -10000px;">
<div class="box box100">
    <!-- INSIRA AQUI O SEU panel_header -->
    <div class="panel">
        <p>Insira seu texto...</p>
    </div>
    <!-- INSIRA AQUI O SEU panel_footer -->
</div>
</textarea>

<textarea id="box70" style="position: absolute; left: -10000px;">
<div class="box box70">
    <!-- INSIRA AQUI O SEU panel_header -->
    <div class="panel">
        <p>Insira seu texto...</p>
    </div>
    <!-- INSIRA AQUI O SEU panel_footer -->
</div>
</textarea>

<textarea id="box50" style="position: absolute; left: -10000px;">
<div class="box box50">
    <!-- INSIRA AQUI O SEU panel_header -->
    <div class="panel">
        <p>Insira seu texto...</p>
    </div>
    <!-- INSIRA AQUI O SEU panel_footer -->
</div>
</textarea>

<textarea id="box33" style="position: absolute; left: -10000px;">
<div class="box box33">
    <!-- INSIRA AQUI O SEU panel_header -->
    <div class="panel">
        <p>Insira seu texto...</p>
    </div>
    <!-- INSIRA AQUI O SEU panel_footer -->
</div>
</textarea>

<textarea id="box30" style="position: absolute; left: -10000px;">
<div class="box box30">
    <!-- INSIRA AQUI O SEU panel_header -->
    <div class="panel">
        <p>Insira seu texto...</p>
    </div>
    <!-- INSIRA AQUI O SEU panel_footer -->
</div>
</textarea>

<textarea id="box25" style="position: absolute; left: -10000px;">
<div class="box box25">
    <!-- INSIRA AQUI O SEU panel_header -->
    <div class="panel">
        <p>Insira seu texto...</p>
    </div>
    <!-- INSIRA AQUI O SEU panel_footer -->
</div>
</textarea>