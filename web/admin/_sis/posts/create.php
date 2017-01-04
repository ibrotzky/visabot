<?php
$AdminLevel = LEVEL_WC_POSTS;
if (!APP_POSTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$PostId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($PostId):
    $Read->ExeRead(DB_POSTS, "WHERE post_id = :id", "id={$PostId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um post que não existe ou que foi removido recentemente!";
        header('Location: dashboard.php?wc=posts/home');
    endif;
else:
    $PostCreate = ['post_date' => date('Y-m-d H:i:s'), 'post_type' => 'post', 'post_status' => 0, 'post_author' => $Admin['user_id']];
    $Create->ExeCreate(DB_POSTS, $PostCreate);
    header('Location: dashboard.php?wc=posts/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-new-tab"><?= $post_title ? $post_title : "Novo Post"; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=posts/home">Posts</a>
            <span class="crumb">/</span>
            Gerenciar Post
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Ver no site" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" class="wc_view btn btn_green icon-eye">Ver artigo no site!</a>
    </div>
</header>

<div class="workcontrol_imageupload none" id="post_control">
    <div class="workcontrol_imageupload_content">
        <form name="workcontrol_post_upload" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Posts"/>
            <input type="hidden" name="callback_action" value="sendimage"/>
            <input type="hidden" name="post_id" value="<?= $PostId; ?>"/>
            <div class="upload_progress none" style="padding: 5px; background: #00B594; color: #fff; width: 0%; text-align: center; max-width: 100%;">0%</div>
            <div style="overflow: auto; max-height: 300px;">
                <img class="image image_default" alt="Nova Imagem" title="Nova Imagem" src="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
            </div>
            <div class="workcontrol_imageupload_actions">
                <input class="wc_loadimage" type="file" name="image" required/>
                <span class="workcontrol_imageupload_close icon-cancel-circle btn btn_red" id="post_control" style="margin-right: 8px;">Fechar</span>
                <button class="btn btn_green icon-image">Enviar e Inserir!</button>
                <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>

<div class="dashboard_content">
    
    <form class="auto_save" name="post_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Posts"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="post_id" value="<?= $PostId; ?>"/>

        <div class="box box70">
            <div class="panel_header default">
                <h2 class="icon-blog">Dados sobre o Post</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Título:</span>
                    <input style="font-size: 1.4em;" type="text" name="post_title" value="<?= $post_title; ?>" required/>
                </label>

                <label class="label">
                    <span class="legend">Subtítulo:</span>
                    <textarea style="font-size: 1.2em;" name="post_subtitle" rows="3" required><?= $post_subtitle; ?></textarea>
                </label>

                <?php if (APP_LINK_POSTS): ?>
                    <label class="label">
                        <span class="legend">Link Alternativo (Opcional):</span>
                        <input type="text" name="post_name" value="<?= $post_name; ?>" placeholder="Link do Artigo:"/>
                    </label>
                <?php endif; ?>

                <label class="label">
                    <span class="legend">Capa: (JPG <?= IMAGE_W; ?>x<?= IMAGE_H; ?>px)</span>
                    <input type="file" class="wc_loadimage" name="post_cover"/>
                </label>

                <label class="label">
                    <span class="legend">Vídeo: (opcional)</span>
                    <input style="font-size: 1.2em;" type="text" name="post_video" value="<?= $post_video; ?>"/>
                </label>

                <label class="label">
                    <span class="legend">Post:</span>
                    <textarea class="work_mce" rows="10" name="post_content"><?= $post_content; ?></textarea>
                </label>
            </div>
        </div>

        <div class="box box30">
            
            <div class="post_create_cover">
                <div class="upload_progress none">0%</div>
                <?php
                $PostCover = (!empty($post_cover) && file_exists("../uploads/{$post_cover}") && !is_dir("../uploads/{$post_cover}") ? "uploads/{$post_cover}" : 'admin/_img/no_image.jpg');
                ?>
                <img class="post_thumb post_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $PostCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $PostCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
            </div>
            
            <div class="post_create_categories">
                <select name="post_category" required>
                    <option value="" disabled="disabled" selected="selected">Selecione uma seção:</option>
                    <?php
                    $Read->FullRead("SELECT category_id, category_title FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL");
                    if (!$Read->getResult()):
                        echo '<option value="" disabled="disabled">Não existem sessões cadastradas!</option>';
                    else:
                        foreach ($Read->getResult() as $CatPai):
                            echo "<option";
                            if ($post_category == $CatPai['category_id']):
                                echo " selected='selected'";
                            endif;
                            echo " value='{$CatPai['category_id']}'>{$CatPai['category_title']}</option>";
                        endforeach;
                    endif;
                    ?>
                </select>

                <?php
                $Read->FullRead("SELECT category_id, category_title FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL");
                if (!$Read->getResult()):
                    echo "<br><br>";
                    echo Erro('<span class="al_center icon-price-tags">Não existe categorias cadastradas!</span>', E_USER_WARNING);
                else:
                    foreach ($Read->getResult() as $Categories):
                        $Read->FullRead("SELECT category_id, category_title FROM " . DB_CATEGORIES . " WHERE category_parent = :parent", "parent={$Categories['category_id']}");
                        if ($Read->getResult()):
                            echo "<p class='post_create_ses'>{$Categories['category_title']}</p>";
                            foreach ($Read->getResult() as $SubCategories):
                                echo "<p class='post_create_cat'><label class='label_check'><input type='checkbox' name='post_category_parent[]' value='{$SubCategories['category_id']}'";
                                if (in_array($SubCategories['category_id'], explode(',', $post_category_parent))):
                                    echo " checked";
                                endif;
                                echo "> {$SubCategories['category_title']}</label></p>";
                            endforeach;
                        endif;
                    endforeach;
                endif;
                ?>
            </div>

            <div class="panel_header default">
                <h2>Publicar:</h2>
            </div>
            
            <div class="panel">
                
                <label class="label">
                    <span class="legend">DIA:</span>
                    <input type="text" class="formTime" name="post_date" value="<?= $post_date ? date('d/m/Y H:i', strtotime($post_date)) : date('d/m/Y H:i'); ?>" required/>
                </label>

                <label class="label">
                    <span class="legend">AUTOR:</span>
                    <select name="post_author" required>
                        <option value="<?= $Admin['user_id']; ?>"><?= $Admin['user_name']; ?> <?= $Admin['user_lastname']; ?></option>
                        <?php
                        $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_level >= :lv AND user_id != :uid", "lv=6&uid={$Admin['user_id']}");
                        if ($Read->getResult()):
                            foreach ($Read->getResult() as $PostAuthors):
                                echo "<option";
                                if ($PostAuthors['user_id'] == $post_author):
                                    echo " selected='selected'";
                                endif;
                                echo " value='{$PostAuthors['user_id']}'>{$PostAuthors['user_name']} {$PostAuthors['user_lastname']}</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </label>

                <div class="wc_actions" style="text-align: center">
                    <label class="label_check label_publish <?= ($post_status == 1 ? 'active' : ''); ?>"><input style="margin-top: -1px;" type="checkbox" value="1" name="post_status" <?= ($post_status == 1 ? 'checked' : ''); ?>> Publicar Agora!</label>
                    <button name="public" value="1" class="btn btn_green icon-share">ATUALIZAR</button>
                    <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                </div>
                <div class="clear"></div>
                
                <?php
                $URLSHARE = "/artigo/{$post_name}";
                require '_tpl/Share.wc.php';
                ?>
            </div>
        </div>
    </form>
</div>