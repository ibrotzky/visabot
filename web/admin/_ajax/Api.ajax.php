<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_CONFIG_API;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Api';
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
        case 'create':
            if (!empty($PostData['api_key']) && mb_strlen($PostData['api_key']) > 8):
                $CreateAPP = [
                    'api_key' => $PostData['api_key'],
                    'api_token' => base64_encode(time() . "wc" . $PostData['api_key']),
                    'api_date' => date('Y-m-d H:i:s'),
                    'api_status' => 1,
                    'api_loads' => 0,
                    'api_lastload' => date('Y-m-d H:i:s')
                ];
                $Create->ExeCreate(DB_WC_API, $CreateAPP);
                if ($Create->getResult()):
                    $jSON['trigger'] = AjaxErro("<b>TUDO CERTO:</b> O APP <b>{$PostData['api_key']}</b> foi criado com sucesso e já pode consumir dados no " . ADMIN_NAME . "! <b>Aguarde...</b>");
                    $jSON['redirect'] = 'dashboard.php?wc=config/wcapi';
                endif;
            else:
                $jSON['trigger'] = AjaxErro("<b class='icon-warning'>ERRO AO CRIAR APP:</b> Desculpe, mas não é seguro criar uma key com menos de 8 caracteres!", E_USER_WARNING);
            endif;
            break;

        //ACTIVE ACESS
        case 'active':
            $Api = $PostData['id'];
            $UpdateApi = ['api_status' => '1'];
            $Update->ExeUpdate(DB_WC_API, $UpdateApi, "WHERE api_id = :id", "id={$Api}");
            $jSON['active'] = 1;
            break;

        //REMVOE ACESS
        case 'inactive':
            $Api = $PostData['id'];
            $UpdateApi = ['api_status' => '0'];
            $Update->ExeUpdate(DB_WC_API, $UpdateApi, "WHERE api_id = :id", "id={$Api}");
            $jSON['active'] = 0;
            break;

        //REMOVE APP
        case 'delete':
            $Api = $PostData['del_id'];
            $Delete->ExeDelete(DB_WC_API, "WHERE api_id = :id", "id={$Api}");
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
