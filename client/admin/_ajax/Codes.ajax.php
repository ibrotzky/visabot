<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_CONFIG_CODES;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Codes';
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
        //STATS
        case 'workcodes':
            if (empty($PostData['code_name']) || empty($PostData['code_script'])):
                $jSON['trigger'] = AjaxErro("<b>ERRO:</b> Para cadastrar um WC CODE é preciso informar pelo menos o título e o script. Favor tente novamente!", E_USER_ERROR);
            else:
                if (empty($PostData['code_id'])):
                    $PostData['code_created'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_WC_CODE, $PostData);
                    $jSON['trigger'] = AjaxErro("<b>CADASTRO COM SUCESSO:</b> O seu WC CODE foi cadastrado com sucesso e você já pode ver a alteração em seu site!");
                else:
                    $CodeId = $PostData['code_id'];
                    unset($PostData['code_id']);
                    $Update->ExeUpdate(DB_WC_CODE, $PostData, "WHERE code_id = :id", "id={$CodeId}");
                    $jSON['trigger'] = AjaxErro("<b>ATUALIZADO COM SUCESSO:</b> O seu WC CODE foi atualizado com sucesso e você já pode ver a alteração em seu site!", E_USER_NOTICE);
                endif;
            endif;
            break;

        case 'edit':
            $CodeId = $PostData['code_id'];
            $Read->ExeRead(DB_WC_CODE, "WHERE code_id = :id", "id={$CodeId}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("<b>ERRO AO OBTER WORK CONTROL CODE:</b> Você tentou editar um código que não existe ou foi removido!", E_USER_ERROR);
            else:
                $jSON['data'] = $Read->getResult()[0];
            endif;
            break;

        case 'delete':
            $CodeDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_WC_CODE, "WHERE code_id = :id", "id={$CodeDel}");
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
