<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_POSTS;

if (!APP_POSTS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Posts';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;

    // AUTO INSTANCE OBJECT CREATE
    if (empty($Create)):
        $Create = new Create;
    endif;

    // AUTO INSTANCE OBJECT UPDATE
    if (empty($Update)):
        $Update = new Update;
    endif;
    
    // AUTO INSTANCE OBJECT DELETE
    if (empty($Delete)):
        $Delete = new Delete;
    endif;

    //SELECIONA AÇÃO
    switch ($Case):
        //DELETE
        case 'delete':
            $PostData['post_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT post_cover FROM " . DB_POSTS . " WHERE post_id = :ps", "ps={$PostData['post_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['post_cover']}") && !is_dir("../../uploads/{$Read->getResult()[0]['post_cover']}")):
                unlink("../../uploads/{$Read->getResult()[0]['post_cover']}");
            endif;

            $Read->FullRead("SELECT image FROM " . DB_POSTS_IMAGE . " WHERE post_id = :ps", "ps={$PostData['post_id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $PostImage):
                    $ImageRemove = "../../uploads/{$PostImage['image']}";
                    if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                        unlink($ImageRemove);
                    endif;
                endforeach;
            endif;

            $Delete->ExeDelete(DB_POSTS, "WHERE post_id = :id", "id={$PostData['post_id']}");
            $Delete->ExeDelete(DB_POSTS_IMAGE, "WHERE post_id = :id", "id={$PostData['post_id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE post_id = :id", "id={$PostData['post_id']}");
            $jSON['success'] = true;
            break;

        case 'manager':
            $PostId = $PostData['post_id'];
            unset($PostData['post_id']);

            $Read->ExeRead(DB_POSTS, "WHERE post_id = :id", "id={$PostId}");
            $ThisPost = $Read->getResult()[0];

            $PostData['post_name'] = (!empty($PostData['post_name']) ? Check::Name($PostData['post_name']) : Check::Name($PostData['post_title']));
            $Read->ExeRead(DB_POSTS, "WHERE post_id != :id AND post_name = :name", "id={$PostId}&name={$PostData['post_name']}");
            if ($Read->getResult()):
                $PostData['post_name'] = "{$PostData['post_name']}-{$PostId}";
            endif;
            $jSON['name'] = $PostData['post_name'];

            if (!empty($_FILES['post_cover'])):
                $File = $_FILES['post_cover'];

                if ($ThisPost['post_cover'] && file_exists("../../uploads/{$ThisPost['post_cover']}") && !is_dir("../../uploads/{$ThisPost['post_cover']}")):
                    unlink("../../uploads/{$ThisPost['post_cover']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['post_name'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()):
                    $PostData['post_cover'] = $Upload->getResult();
                else:
                    $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR CAPA:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para enviar como capa!", E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['post_cover']);
            endif;

            $PostData['post_status'] = (!empty($PostData['post_status']) ? '1' : '0');
            $PostData['post_date'] = (!empty($PostData['post_date']) ? Check::Data($PostData['post_date']) : date('Y-m-d H:i:s'));
            $PostData['post_category_parent'] = (!empty($PostData['post_category_parent']) ? implode(',', $PostData['post_category_parent']) : null);

            $Update->ExeUpdate(DB_POSTS, $PostData, "WHERE post_id = :id", "id={$PostId}");
            $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>TUDO CERTO: </b> O post <b>{$PostData['post_title']}</b> foi atualizado com sucesso!");
            $jSON['view'] = BASE . "/artigo/{$PostData['post_name']}";
            break;

        case 'sendimage':
            $NewImage = $_FILES['image'];
            $Read->FullRead("SELECT post_title, post_name FROM " . DB_POSTS . " WHERE post_id = :id", "id={$PostData['post_id']}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Desculpe {$_SESSION['userLogin']['user_name']}, mas não foi possível identificar o post vinculado!", E_USER_WARNING);
            else:
                $Upload = new Upload('../../uploads/');
                $Upload->Image($NewImage, $PostData['post_id'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()):
                    $PostData['image'] = $Upload->getResult();
                    $Create->ExeCreate(DB_POSTS_IMAGE, $PostData);
                    $jSON['tinyMCE'] = "<img title='{$Read->getResult()[0]['post_title']}' alt='{$Read->getResult()[0]['post_title']}' src='../uploads/{$PostData['image']}'/>";
                else:
                    $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para inserir no post!", E_USER_WARNING);
                endif;
            endif;
            break;

        case 'category_add':
            $PostData = array_map('strip_tags', $PostData);
            $CatId = $PostData['category_id'];
            unset($PostData['category_id']);

            $PostData['category_name'] = Check::Name($PostData['category_title']);
            $PostData['category_parent'] = ($PostData['category_parent'] ? $PostData['category_parent'] : null);

            $Read->FullRead("SELECT category_id FROM " . DB_CATEGORIES . " WHERE category_name = :cn AND category_id != :ci", "cn={$PostData['category_name']}&ci={$CatId}");
            if ($Read->getResult()):
                $PostData['category_name'] = $PostData['category_name'] . '-' . $CatId;
            endif;

            $Read->FullRead("SELECT category_id FROM " . DB_CATEGORIES . " WHERE category_parent = :ci", "ci={$CatId}");
            if ($Read->getResult() && !empty($PostData['category_parent'])):
                $jSON['trigger'] = AjaxErro("<b class='icon-warning'>OPPSSS: </b> {$_SESSION['userLogin']['user_name']}, uma categoria PAI (que possui subcategorias) não pode ser atribuida como subcategoria", E_USER_WARNING);
            else:
                $Update->ExeUpdate(DB_CATEGORIES, $PostData, "WHERE category_id = :id", "id={$CatId}");
                $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>TUDO CERTO: </b> A categoria <b>{$PostData['category_title']}</b> foi atualizada com sucesso!");
            endif;
            break;

        case 'category_remove':
            $PostData['category_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT category_title, category_id FROM " . DB_CATEGORIES . " WHERE category_parent = :cat", "cat={$PostData['category_id']}");

            if ($Read->getResult()):
                $jSON['trigger'] = AjaxErro("<b class='icon-notification'>OPPSSS: </b> Olá {$_SESSION['userLogin']['user_name']}, para deletar uma categoria certifique-se que ela não tem subcategoria cadastradas!", E_USER_WARNING);
            else:
                $Read->FullRead("SELECT post_id FROM " . DB_POSTS . " WHERE post_category = :cat OR FIND_IN_SET(:cat, post_category_parent)", "cat={$PostData['category_id']}");
                if ($Read->getResult()):
                    $jSON['trigger'] = AjaxErro("<b class='icon-warning'>{$Read->getRowCount()} POST(S): </b> Olá {$_SESSION['userLogin']['user_name']}, não é possível remover categorias quando existem posts cadastrados na mesma!", E_USER_WARNING);
                else:
                    $Delete->ExeDelete(DB_CATEGORIES, "WHERE category_id = :cat", "cat={$PostData['category_id']}");
                    $jSON['success'] = true;
                endif;
            endif;
            break;
    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
