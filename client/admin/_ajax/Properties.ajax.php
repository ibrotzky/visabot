<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_IMOBI;

if (!APP_IMOBI || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Properties';
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
        //GERENCIA IMÓVEL
        case 'manager':
            $RealtyId = $PostData['realty_id'];
            $RealTyCover = (!empty($_FILES['realty_cover']) ? $_FILES['realty_cover'] : null);
            $Image = (!empty($_FILES['image']) ? $_FILES['image'] : null);

            unset($PostData['realty_id'], $PostData['realty_cover'], $PostData['image']);
            $Read->FullRead("SELECT realty_cover FROM " . DB_IMOBI . " WHERE realty_id = :id", "id={$RealtyId}");
            $CoverRead = $Read->getResult();

            $PostData['realty_price'] = ($PostData['realty_price'] ? str_replace(array('.', ','), array('', '.'), $PostData['realty_price']) : null);
            $PostData['realty_date'] = Check::Data($PostData['realty_date']);
            $PostData['realty_status'] = (!empty($PostData['realty_status']) ? '1' : '0');

            $PostData['realty_state'] = mb_strtoupper($PostData['realty_state']);
            $PostData['realty_city'] = ucwords(mb_strtolower($PostData['realty_city']));
            $PostData['realty_district'] = ucwords(mb_strtolower($PostData['realty_district']));

            $RealTyPostName = (!empty($PostData['realty_name']) ? $PostData['realty_name'] : $PostData['realty_title']);
            $PostData['realty_name'] = Check::Name($RealTyPostName);
            $Read->FullRead("SELECT realty_name FROM " . DB_IMOBI . " WHERE realty_name = :nm AND realty_id != :id", "nm={$PostData['realty_name']}&id={$RealtyId}");
            if ($Read->getResult()):
                $PostData['realty_name'] = "{$PostData['realty_name']}-{$RealtyId}";
            endif;
            $jSON['name'] = $PostData['realty_name'];

            if (!empty($RealTyCover)):
                if ($CoverRead && !empty($CoverRead[0]['realty_cover']) && file_exists("../../uploads/{$CoverRead[0]['realty_cover']}") && !is_dir("../../uploads/{$CoverRead[0]['realty_cover']}")):
                    unlink("../../uploads/{$CoverRead[0]['realty_cover']}");
                endif;
                $Upload = new Upload('../../uploads/');
                $Upload->Image($RealTyCover, $PostData['realty_name'], IMAGE_W, 'properties');
                $PostData['realty_cover'] = $Upload->getResult();
            endif;

            if (!empty($Image)):
                if (empty($Upload)):
                    $Upload = new Upload('../../uploads/');
                endif;

                $File = $Image;
                $gbFile = array();
                $gbCount = count($File['type']);
                $gbKeys = array_keys($File);
                $gbLoop = 0;

                for ($gb = 0; $gb < $gbCount; $gb++):
                    foreach ($gbKeys as $Keys):
                        $gbFiles[$gb][$Keys] = $File[$Keys][$gb];
                    endforeach;
                endfor;

                $jSON['gallery'] = null;
                foreach ($gbFiles as $UploadFile):
                    $gbLoop ++;
                    $Upload->Image($UploadFile, "{$PostData['realty_name']}-{$RealtyId}-{$gbLoop}-" . time(), IMAGE_W, 'properties');
                    if ($Upload->getResult()):
                        $gbCreate = ['realty_id' => $RealtyId, "image" => $Upload->getResult()];
                        $Create->ExeCreate(DB_IMOBI_GALLERY, $gbCreate);
                        $jSON['gallery'] .= "<img rel='Properties' id='{$Create->getResult()}' alt='Imagem em {$PostData['realty_title']}' title='Imagem em {$PostData['realty_title']}' src='../uploads/{$Upload->getResult()}'/>";
                    endif;
                endforeach;
            endif;

            $Update->ExeUpdate(DB_IMOBI, $PostData, "WHERE realty_id = :id", "id={$RealtyId}");
            $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>ATUALIZADO COM SUCESSO:</b> O imóvel {$PostData['realty_title']} foi atualizado com sucesso no sistema!");
            $jSON['view'] = BASE . "/imovel/{$PostData['realty_name']}";
            break;

        //DELETA GALERIA
        case 'gbremove':
            $Read->FullRead("SELECT image FROM " . DB_IMOBI_GALLERY . " WHERE id = :id", "id={$PostData['img']}");
            if ($Read->getResult()):
                $ImageRemove = "../../uploads/{$Read->getResult()[0]['image']}";
                if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                    unlink($ImageRemove);
                endif;
                $Delete->ExeDelete(DB_IMOBI_GALLERY, "WHERE id = :id", "id={$PostData['img']}");
                $jSON['success'] = true;
            endif;
            break;

        //DELETA IMÓVEL
        case 'delete':
            $RealtyId = $PostData['del_id'];

            $Read->FullRead("SELECT realty_cover FROM " . DB_IMOBI . " WHERE realty_id = :id", "id={$RealtyId}");
            if ($Read->getResult() && !empty($Read->getResult()[0]['realty_cover']) && file_exists("../../uploads/{$Read->getResult()[0]['realty_cover']}") && !is_dir("../../uploads/{$Read->getResult()[0]['realty_cover']}")):
                unlink("../../uploads/{$Read->getResult()[0]['realty_cover']}");
            endif;

            $Read->ExeRead(DB_IMOBI_GALLERY, "WHERE realty_id = :id", "id={$RealtyId}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $ImobiGB):
                    if (file_exists("../../uploads/{$ImobiGB['image']}") && !is_dir("../../uploads/{$ImobiGB['image']}")):
                        unlink("../../uploads/{$ImobiGB['image']}");
                    endif;
                endforeach;
            endif;

            $Delete->ExeDelete(DB_IMOBI, "WHERE realty_id = :id", "id={$RealtyId}");
            $Delete->ExeDelete(DB_IMOBI_GALLERY, "WHERE realty_id = :id", "id={$RealtyId}");
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