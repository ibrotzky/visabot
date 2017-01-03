<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_SLIDES;

if (!APP_SLIDE || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Slides';
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
        //GERENCIA
        case 'manager':
            $SlideId = $PostData['slide_id'];
            $SlideEnd = (!empty($PostData['slide_end']) ? $PostData['slide_end'] : null);
            $Image = (!empty($_FILES['slide_image']) ? $_FILES['slide_image'] : null);

            unset($PostData['slide_id'], $PostData['slide_end'], $PostData['slide_image']);
            $Read->FullRead("SELECT slide_image FROM " . DB_SLIDES . " WHERE slide_id = :id", "id={$SlideId}");

            if (empty($Image) && (!$Read->getResult() || !$Read->getResult()[0]['slide_image'])):
                $jSON['trigger'] = AjaxErro('<b class="icon-warning">ERRO AO CADASTRAR:</b> Favor envie uma imagem de destaque nas medidas de ' . SLIDE_W . 'x' . SLIDE_H . 'px!', E_USER_ERROR);
            elseif (in_array('', $PostData)):
                $jSON['trigger'] = AjaxErro('<b class="icon-warning">ERRO AO CADASTRAR:</b> Para atualizar o destaque, favor preencha todos os campos!', E_USER_ERROR);
                $jSON['error'] = true;
            else:
                $PostData['slide_date'] = date('Y-m-d H:i:s');
                $PostData['slide_start'] = Check::Data($PostData['slide_start']);
                $PostData['slide_end'] = (!empty($SlideEnd) ? Check::Data($SlideEnd) : null);
                $PostData['slide_status'] = (!empty($PostData['slide_status']) ? $PostData['slide_status'] : '0');

                if (!empty($Image)):
                    if ($Read->getResult() && !empty($Read->getResult()[0]['slide_image']) && file_exists("../../uploads/{$Read->getResult()[0]['slide_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['slide_image']}")):
                        unlink("../../uploads/{$Read->getResult()[0]['slide_image']}");
                    endif;
                    $Upload = new Upload('../../uploads/');
                    $Upload->Image($Image, Check::Name($PostData['slide_title']), SLIDE_W, 'slides');
                    $PostData['slide_image'] = $Upload->getResult();
                endif;

                $Update->ExeUpdate(DB_SLIDES, $PostData, "WHERE slide_id = :id", "id={$SlideId}");
                $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>Tudo certo {$_SESSION['userLogin']['user_name']}</b>: O conteúdo em destaque foi atualizado com sucesso. E sera exibido nas datas cadastradas!");
            endif;
            break;

        //DELETA
        case 'delete':
            $SlideId = $PostData['del_id'];
            $Read->FullRead("SELECT slide_image FROM " . DB_SLIDES . " WHERE slide_id = :id", "id={$SlideId}");
            if ($Read->getResult()):
                $SlideImage = (!empty($Read->getResult()[0]['slide_image']) ? $Read->getResult()[0]['slide_image'] : null);
                if ($SlideImage && file_exists("../../uploads/{$SlideImage}") && !is_dir("../../uploads/{$SlideImage}")):
                    unlink("../../uploads/{$SlideImage}");
                endif;
            endif;

            $Delete->ExeDelete(DB_SLIDES, "WHERE slide_id = :id", "id={$SlideId}");
            $jSON['success'] = true;
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
