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
            <span>
                <a href="javacript:void(0)" class="btn btn_blue icon-embed2 icon-notext wc_clip" rel="form"></a>
            </span>
            <h2 class="icon-embed2">Exemplo de Formulário</h2>
        </div>

        <div class="panel">
            <form>
                <label class="label">
                    <span class="legend">Campo 100%:</span>
                    <input style="font-size: 1.4em;" type="text" name="titulo" placeholder="Digite um título" required/>
                </label>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Campo 1 50%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo1" placeholder="Digite um título" required/>
                    </label>
                    <label class="label">
                        <span class="legend">Campo 2 50%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo2" placeholder="Digite um título" required/>
                    </label>
                    <div class="clear"></div>
                </div>

                <div class="label_33">
                    <label class="label">
                        <span class="legend">Campo 1 30%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo1" placeholder="Digite um título" required/>
                    </label>
                    <label class="label">
                        <span class="legend">Campo 2 30%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo2" placeholder="Digite um título" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Campo 3 30%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo3" placeholder="Digite um título" required/>
                    </label>
                </div>

                <label class="label">
                    <input type="submit" name="titulo" value="Enviar" class="btn btn_green fl_right"/>
                </label>
            </form>
            <div class="clear"></div>
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
<textarea id="form" style="position: absolute; left: -10000px;">
<form>
                <label class="label">
                    <span class="legend">Campo 100%:</span>
                    <input style="font-size: 1.4em;" type="text" name="titulo" placeholder="Digite um título" required/>
                </label>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Campo 1 50%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo1" placeholder="Digite um título" required/>
                    </label>
                    <label class="label">
                        <span class="legend">Campo 2 50%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo2" placeholder="Digite um título" required/>
                    </label>
                    <div class="clear"></div>
                </div>

                <div class="label_33">
                    <label class="label">
                        <span class="legend">Campo 1 30%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo1" placeholder="Digite um título" required/>
                    </label>
                    <label class="label">
                        <span class="legend">Campo 2 30%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo2" placeholder="Digite um título" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Campo 3 30%:</span>
                        <input style="font-size: 1.4em;" type="text" name="campo3" placeholder="Digite um título" required/>
                    </label>
                </div>

                <label class="label">
                    <input type="submit" name="titulo" value="Enviar" class="btn btn_green fl_right"/>
                </label>
            </form>
            <div class="clear"></div>
</textarea>
