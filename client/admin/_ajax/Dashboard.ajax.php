<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = 6;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Dashboard';
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
        case 'siteviews':
            $Read->FullRead("SELECT count(online_id) AS total from " . DB_VIEWS_ONLINE . " WHERE online_endview >= NOW()");
            $jSON['useron'] = str_pad($Read->getResult()[0]['total'], 4, 0, STR_PAD_LEFT);

            $Read->ExeRead(DB_VIEWS_VIEWS, "WHERE views_date = date(NOW())");
            if (!$Read->getResult()):
                $jSON['users'] = '0000';
                $jSON['views'] = '0000';
                $jSON['pages'] = '0000';
                $jSON['stats'] = '0.00';
            else:
                $Views = $Read->getResult()[0];
                $Stats = number_format($Views['views_pages'] / $Views['views_views'], 2, '.', '');
                $jSON['users'] = str_pad($Views['views_users'], 4, 0, STR_PAD_LEFT);
                $jSON['views'] = str_pad($Views['views_views'], 4, 0, STR_PAD_LEFT);
                $jSON['pages'] = str_pad($Views['views_pages'], 4, 0, STR_PAD_LEFT);
                $jSON['stats'] = $Stats;
            endif;

            $Read->FullRead("SELECT COUNT(online_id) AS TotalOnline FROM " . DB_VIEWS_ONLINE . " WHERE online_endview >= NOW() AND online_user IN(SELECT user_id FROM " . DB_EAD_ENROLLMENTS . ")");
            $jSON['students'] = str_pad($Read->getResult()[0]['TotalOnline'], 4, 0, 0);
            break;
            
        case 'onlinenow':
            $Read->ExeRead(DB_VIEWS_ONLINE, "WHERE online_endview >= NOW() ORDER BY online_endview DESC");
            if (!$Read->getResult()):
                $jSON['data'] = '<div class="trigger trigger_info"><span class="icon-earth al_center">Não existem usuárion online neste momento!</span></div>';
                $jSON['data'] .= '<div class="clear"></div>';
                $jSON['now'] = '0000';
            else:
                $i = 0;
                $jSON['data'] = null;
                $jSON['now'] = str_pad($Read->getRowCount(), 4, 0, 0);
                foreach ($Read->getResult() as $Online):
                    $i++;
                    $Name = ($Online['online_name'] ? "<a href='dashboard.php?wc=" . (APP_EAD ? 'teach/students_gerent' : 'users/create') . "&id={$Online['online_user']}' title='Ver Cliente'>{$Online['online_name']}</a>" : 'guest user');
                    $Date = date('d/m/Y H\hi', strtotime($Online['online_startview']));
                    $jSON['data'] .= "<div class='single_onlinenow'>
                    <p>" . str_pad($i, 4, 0, STR_PAD_LEFT) . "</p>
                    <p>{$Name}</p>
                    <p>{$Date}</p>
                    <p>{$Online['online_ip']}</p>
                    <p><a target='_blank' href='" . BASE . "/{$Online['online_url']}' title='Ver Destino'>" . ($Online['online_url'] ? $Online['online_url'] : 'home') . "</a></p>
                    </div>";
                endforeach;
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
