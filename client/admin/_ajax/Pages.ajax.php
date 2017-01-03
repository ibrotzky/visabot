<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_PAGES;

if (!APP_PAGES || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Pages';
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
            $Read->FullRead("SELECT image FROM " . DB_PAGES_IMAGE . " WHERE page_id = :ps", "ps={$PostData['del_id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $PageImage):
                    $ImageRemove = "../../uploads/{$PageImage['image']}";
                    if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                        unlink($ImageRemove);
                    endif;
                endforeach;
            endif;

            $Delete->ExeDelete(DB_PAGES, "WHERE page_id = :id", "id={$PostData['del_id']}");
            $Delete->ExeDelete(DB_PAGES_IMAGE, "WHERE page_id = :id", "id={$PostData['del_id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE page_id = :id", "id={$PostData['del_id']}");
            $jSON['success'] = true;
            break;

        //MANAGER
        case 'manage':
            $PageId = $PostData['page_id'];
            unset($PostData['page_id']);

            $PostData['page_status'] = (!empty($PostData['page_status']) ? '1' : '0');
            $PostData['page_order'] = (!empty($PostData['page_order']) ? $PostData['page_order'] : null);
            $PostData['page_name'] = (!empty($PostData['page_name']) ? Check::Name($PostData['page_name']) : Check::Name($PostData['page_title']));
            $Read->FullRead("SELECT page_name FROM " . DB_PAGES . " WHERE page_name = :nm AND page_id != :id", "nm={$PostData['page_name']}&id={$PageId}");
            if ($Read->getResult()):
                $PostData['page_name'] = "{$PostData['page_name']}-{$PageId}";
            endif;

            $jSON['name'] = $PostData['page_name'];
            $jSON['view'] = BASE . "/{$PostData['page_name']}";

            $Update->ExeUpdate(DB_PAGES, $PostData, "WHERE page_id = :id", "id={$PageId}");
            $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>Página atualizada com sucesso!</b>");
            break;

        //PAGE IMAGE
        case 'sendimage':
            $NewImage = $_FILES['image'];
            $Read->FullRead("SELECT page_title, page_name FROM " . DB_PAGES . " WHERE page_id = :id", "id={$PostData['page_id']}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Desculpe {$_SESSION['userLogin']['user_name']}, mas não foi possível identificara página vinculado!", E_USER_WARNING);
            else:
                $Upload = new Upload('../../uploads/');
                $Upload->Image($NewImage, $PostData['page_id'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()):
                    $PostData['image'] = $Upload->getResult();
                    $Create->ExeCreate(DB_PAGES_IMAGE, $PostData);
                    $jSON['tinyMCE'] = "<img title='{$Read->getResult()[0]['page_title']}' alt='{$Read->getResult()[0]['page_title']}' src='../uploads/{$PostData['image']}'/>";
                else:
                    $jSON['trigger'] = AjaxErro("<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para inserir na página!", E_USER_WARNING);
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
