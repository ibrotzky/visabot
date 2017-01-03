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

    <div class="box box50">
        <div class="panel_header">
            <span>
                <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_icon_action_subtitle"></a>
            </span>
            <h2><span class="icon-command">Título do Painel</span></h2>
            <p class="subtitle">Subtítulo do Painel</p>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
    </div>

    <div class="box box50">    
        <div class="panel_header">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_icon_subtitle"></a>
            </span>
            <h2 class="icon-command">Título do Painel</h2>
            <p class="subtitle">Subtítulo do Painel</p>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
    </div>

    <div class="box box50">
        <div class="panel_header">
            <span>
                <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_action_subtitle"></a>
            </span>
            <h2>Título do Painel</h2>
            <p>Subtítulo do Painel</p>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
    </div>

    <div class="box box50">    
        <div class="panel_header">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_subtitle"></a>
            </span>
            <h2>Título do Painel</h2>
            <p>Subtítulo do Painel</p>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
    </div>

    <div class="box box50">
        <div class="panel_header">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_icon"></a>
            </span>
            <h2><span class="icon-command">Título do Painel</span></h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
    </div>

    <div class="box box50">    
        <div class="panel_header default">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_default"></a>
            </span>
            <h2 class="icon-command">Título do Painel</h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header success">
            <span>
                <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_success"></a>
            </span>
            <h2 class="icon-arrow-up">Sucesso</h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor.</p>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header alert">
            <span>
                <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_alert"></a>
            </span>
            <h2 class="icon-arrow-up">Alerta</h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor.</p>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header info">
            <span>
                <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_info"></a>
            </span>
            <h2 class="icon-arrow-up">Informação</h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor.</p>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header warning">
            <span>
                <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_header_warning"></a>
            </span>
            <h2 class="icon-arrow-up">Cuidado</h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor.</p>
        </div>
    </div>

    <div class="box box100">
        <div class="panel_header">
            <h2 class="icon-command">Título do Painel</h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
        <div class="panel_footer">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_footer"></a>
            </span>
            <p>&reg; Conteúdo do rodapé interno</p>
        </div>
    </div>

    <div class="box box100">
        <div class="panel_header">
            <h2 class="icon-command">Título do Painel</h2>
        </div>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar. Vestibulum metus lacus, porta non orci in, euismod semper quam. 
                Quisque tincidunt tortor non nibh sagittis porttitor. Ut justo lacus, mattis ut fringilla ac, lacinia vel diam.</p>
        </div>
        <div class="panel_footer_external">
            <span>
                <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-embed2 wc_clip" rel="panel_footer_external"></a>
            </span>
            <p>&reg; Conteúdo do rodapé externo</p>
        </div>
    </div>

    <div class="box box100">
        <div class="panel_header">
            <h2>Título de Cabeçalho em h2</h2>
            <h3>Título de Cabeçalho em h3</h3>
            <h4>Título de Cabeçalho em h4</h4>
            <h5>Título de Cabeçalho em h5</h5>
            <h6>Título de Cabeçalho em h6</h6>

            <br>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Esse <i>parágrafo</i> possui um <u>elemento</u> <mark>mark</mark> com formatação padrão do CSS para <b>destaque!</b></p>

            <ul>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
            </ul>

            <ol>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
            </ol>
        </div>

        <div class="panel">
            <h2>Título do Painel em h2</h2>
            <h3>Título do Painel em h3</h3>
            <h4>Título do Painel em h4</h4>
            <h5>Título do Painel em h5</h5>
            <h6>Título do Painel em h6</h6>

            <br>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Esse <i>parágrafo</i> possui um <u>elemento</u> <mark>mark</mark> com formatação padrão do CSS para <b>destaque!</b></p>

            <ul>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
                <li>Item de lista não ordenada</li>
            </ul>

            <ol>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
                <li>Item de lista ordenada</li>
            </ol>

        </div>

        <div class="panel_footer">
            <h2>Título do Rodapé em h2</h2>
            <h3>Título do Rodapé em h3</h3>
            <h4>Título do Rodapé em h4</h4>
            <h5>Título do Rodapé em h5</h5>
            <h6>Título do Rodapé em h6</h6>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Esse <i>parágrafo</i> possui um <u>elemento</u> <mark>mark</mark> com formatação padrão do CSS para <b>destaque!</b></p>
        </div>

        <div class="panel_footer_external">
            <h2>Título do Rodapé em h2</h2>
            <h3>Título do Rodapé em h3</h3>
            <h4>Título do Rodapé em h4</h4>
            <h5>Título do Rodapé em h5</h5>
            <h6>Título do Rodapé em h6</h6>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque at enim at volutpat. 
                Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc maximus magna et venenatis pellentesque. 
                Maecenas sollicitudin sit amet ligula et pulvinar.</p>
            <p>Esse <i>parágrafo</i> possui um <u>elemento</u> <mark>mark</mark> com formatação padrão do CSS para <b>destaque!</b></p>
        </div>
    </div>
</div>

<!-- Finaliza o STAGE, o palco de onde será desenvolvida a aplicação -->


<!--
##########################################################################
   ##########  UTILIZADO PARA O CLIPBOARD - NÃO ALTERAR   #############
O CONTEÚDO DOS TEXT ÁREA É O QUE SERÁ COPIADO PARA A ÁREA DE TRANSFERÊNCIA
##########################################################################
-->
<textarea id="panel_header_icon_action_subtitle" style="position: absolute; left: -10000px;">
<div class="panel_header">
    <span>
        <a href="javascript:void(0)" class="btn btn_blue icon-notext icon-link"></a>
    </span>
    <h2><span class="icon-command">Título do Painel</span></h2>
    <p class="subtitle">Subtítulo do Painel</p>
</div>
</textarea>

<textarea id="panel_header_action_subtitle" style="position: absolute; left: -10000px;">
<div class="panel_header">
    <span>
        <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
    </span>
    <h2>Título do Painel</h2>
    <p>Subtítulo do Painel</p>
</div>
</textarea>

<textarea id="panel_header_subtitle" style="position: absolute; left: -10000px;">
<div class="panel_header">
    <h2 class="no_border">Título do Painel</h2>
    <p>Subtítulo do Painel</p>
</div>
</textarea>

<textarea id="panel_header_icon_subtitle" style="position: absolute; left: -10000px;">
<div class="panel_header">
    <h2 class="icon-command">Título do Painel</h2>
    <p class="subtitle">Subtítulo do Painel</p>
</div>
</textarea>

<textarea id="panel_header_icon" style="position: absolute; left: -10000px;">
<div class="panel_header">
    <h2 class="icon-command">Título do Painel</h2>
</div>
</textarea>

<textarea id="panel_header_success" style="position: absolute; left: -10000px;">
<div class="panel_header success">
    <span>
        <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
    </span>
    <h2 class="icon-arrow-up">Sucesso</h2>
</div>
</textarea>

<textarea id="panel_header_alert" style="position: absolute; left: -10000px;">
<div class="panel_header alert">
    <span>
        <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
    </span>
    <h2 class="icon-arrow-up">Alerta</h2>
</div>
</textarea>

<textarea id="panel_header_info" style="position: absolute; left: -10000px;">
<div class="panel_header info">
    <span>
        <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
    </span>
    <h2 class="icon-arrow-up">Informação</h2>
</div>
</textarea>

<textarea id="panel_header_warning" style="position: absolute; left: -10000px;">
<div class="panel_header warning">
    <span>
        <a href="javascript:void(0)" class="btn icon-notext icon-link"></a>
    </span>
    <h2 class="icon-arrow-up">Cuidado</h2>
</div>
</textarea>

<textarea id="panel_footer" style="position: absolute; left: -10000px;">
<div class="panel_footer">
    <p>&reg; Conteúdo do rodapé interno</p>
</div>
</textarea>

<textarea id="panel_footer_external" style="position: absolute; left: -10000px;">
<div class="panel_footer_external">
    <p>&reg; Conteúdo do rodapé externo</p>
</div>
</textarea>