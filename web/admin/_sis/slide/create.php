<?php
$AdminLevel = LEVEL_WC_SLIDES;
if (!APP_SLIDE || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

$SlideId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($SlideId):
    $Read->ExeRead(DB_SLIDES, "WHERE slide_id = :id", "id={$SlideId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um slide que não existe ou que foi removido recentemente!";
        header('Location: dashboard.php?wc=slide/home');
    endif;
else:
    $SlideCreate = ['slide_date' => date('Y-m-d H:i:s'), 'slide_start' => date('Y-m-d H:i:s')];
    $Create->ExeCreate(DB_SLIDES, $SlideCreate);
    header('Location: dashboard.php?wc=slide/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-camera"><?= $slide_title ? $slide_title : 'Novo Slide'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=slide/home">Slides</a>
            <span class="crumb">/</span>
            Gerenciar Destaque
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Slides!" href="dashboard.php?wc=slide/home" class="btn btn_blue icon-eye">Ver Destaques!</a>
        <a title="Novo Slide!" href="dashboard.php?wc=slide/create" class="btn btn_green icon-plus">Adicionar Destaque!</a>
    </div>
</header>

<div class="dashboard_content">
    <form name="post_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Slides"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="slide_id" value="<?= $SlideId; ?>"/>

        <article class="box box100">
            <div class="panel">
                <div class="slide_create_cover">
                    <div class="upload_progress none">0%</div>
                    <?php
                    $SlideImage = (!empty($slide_image) && file_exists("../uploads/{$slide_image}") && !is_dir("../uploads/{$slide_image}") ? "uploads/{$slide_image}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="slide_image post_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $SlideImage; ?>&w=<?= SLIDE_W; ?>&h=<?= SLIDE_H; ?>" default="../tim.php?src=<?= $SlideImage; ?>&w=<?= SLIDE_W; ?>&h=<?= SLIDE_H; ?>"/>
                </div>

                <label class="label m_top">
                    <span class="legend">Capa: (JPG <?= SLIDE_W; ?>x<?= SLIDE_H; ?>px)</span>
                    <input type="file" class="wc_loadimage" name="slide_image"/>
                </label>

                <label class="label">
                    <span class="legend">Título:</span>
                    <input style="font-size: 1.5em;" type="text" name="slide_title" value="<?= $slide_title; ?>" required/>
                </label>

                <label class="label">
                    <span class="legend">Descrição:</span>
                    <textarea style="font-size: 1.2em;" name="slide_desc" rows="3" required><?= $slide_desc; ?></textarea>
                </label>

                <label class="label">
                    <span class="legend">Link: (<?= BASE; ?>/<b>destino</b>)</span>
                    <input style="font-size: 1.2em;" type="text" name="slide_link" value="<?= $slide_link; ?>" required/>
                </label>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">A partir de:</span>
                        <input style="font-size: 1.2em;" type="text" class="formTime" name="slide_start" value="<?= (!empty($slide_start) ? date('d/m/Y H:i:s', strtotime($slide_start)) : date('d/m/Y H:i:s')); ?>" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Até dia: (opcional)</span>
                        <input style="font-size: 1.2em;" type="text" class="formTime" name="slide_end" value="<?= (!empty($slide_end) ? date('d/m/Y H:i:s', strtotime($slide_end)) : date('d/m/Y H:i:s', strtotime("+1month"))); ?>"/>
                    </label>
                </div>

                <div class="wc_actions" style="text-align: right">
                    <label class="label_check label_publish <?= ($slide_status == 1 ? 'active' : ''); ?>"><input style="margin-top: -1px;" type="checkbox" value="1" name="slide_status" <?= ($slide_status == 1 ? 'checked' : ''); ?>> Publicar Agora!</label>
                    <button name="public" value="1" class="btn btn_green icon-share" style="margin-left: 5px;">Atualizar Destaque!</button>
                    <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>

                </div>
                <div class="clear"></div>
            </div>
        </article>
    </form>
</div>