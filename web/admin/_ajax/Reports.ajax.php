<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_REPORTS;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Reports';
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

    switch ($Case):
        //EAD :: STUDENTS
        case 'get_report';
            $ReportStart = date("Y-m-d", strtotime(($PostData['start_date'] ? Check::Data($PostData['start_date']) : date("Y-m-01 H:i:s"))));
            $ReportEnd = date("Y-m-d", strtotime(($PostData['end_date'] ? Check::Data($PostData['end_date']) : date("Y-m-d H:i:s"))));

            $_SESSION['wc_report_date'] = [$ReportStart, $ReportEnd];

            $jSON['redirect'] = "dashboard.php?wc={$PostData['report_back']}";
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
