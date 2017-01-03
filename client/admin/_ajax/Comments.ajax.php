<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_COMMENTS;

if (!APP_COMMENTS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Comments';
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

    //CLEAR LIKES
    $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE user_id NOT IN(SELECT user_id FROM " . DB_USERS . ") AND id >= :id", "id=1");
    $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE comm_id NOT IN(SELECT id FROM " . DB_COMMENTS . ") AND id >= :id", "id=1");

    //SELECIONA AÇÃO
    switch ($Case):
        //CURTIR
        case 'like':
            $Read->FullRead("SELECT id FROM " . DB_COMMENTS_LIKES . " WHERE user_id = :user AND comm_id = :comm", "user={$_SESSION['userLogin']['user_id']}&comm={$PostData['id']}");
            if (!$Read->getResult()):
                $LikeThis = ['user_id' => $_SESSION['userLogin']['user_id'], 'comm_id' => $PostData['id']];
                $Create->ExeCreate(DB_COMMENTS_LIKES, $LikeThis);
            endif;
            $UpdateData = ['status' => 1];
            $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$PostData['id']}");
            $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE alias_id = :id", "id={$PostData['id']}");
            $jSON['aprove'] = "<b class='icon-checkmark icon-notext'></b>";

            $jSON['like'] = true;
            $jSON['admin'] = "<a target='_blank' title='Ver Usuário' href='dashboard.php?wc=users/create&id={$_SESSION['userLogin']['user_id']}'>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}</a>";
            break;

        //APROVAR
        case 'aprove':
            $UpdateData = ['status' => 1];
            $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id OR alias_id = :id", "id={$PostData['id']}");

            //VERIFICA SE AINDA EXISTEM RESPOSTAS PENDENTES. SE NÃO, APROVA O COMENTÁRIO
            $Read->FullRead("SELECT alias_id FROM " . DB_COMMENTS . " WHERE id={$PostData['id']}");
            if ($Read->getResult()):
                $Alias = $Read->getResult()[0]['alias_id'];
                $Read->FullRead("SELECT id FROM " . DB_COMMENTS . " WHERE status > 1 AND alias_id = :id", "id={$Alias}");
                if (!$Read->getResult()):
                    $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$Alias}");
                    $jSON['alias'] = $Alias;
                endif;
            endif;
            $jSON['aprove'] = "<b class='icon-checkmark icon-notext'></b>";
            break;

        //RESPONDER
        case 'response':
            $Read->ExeRead(DB_COMMENTS, "WHERE id = :alias", "alias={$PostData['alias_id']}");
            if ($Read->getResult()):
                $Comm = $Read->getResult()[0];
                $ResponseTo = $PostData['user_id'];
                unset($PostData['user_id']);

                //CADASTRA COMENTÁRIO
                $PostData['user_id'] = $_SESSION['userLogin']['user_id'];
                $PostData['rank'] = 5;
                $PostData['created'] = date('Y-m-d H:i:s');
                $PostData['interact'] = date('Y-m-d H:i:s');
                $PostData['status'] = 1;
                $Create->ExeCreate(DB_COMMENTS, $PostData);

                //ATUALIZA RESPOSTAS
                $UpdateData = ['status' => 1];
                $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$PostData['alias_id']}");
                $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE alias_id = :id", "id={$PostData['alias_id']}");

                //OBTÉM LINK DO COMENTÁRIO
                if ($Comm['post_id']):
                    $Read->FullRead("SELECT post_name, post_title FROM " . DB_POSTS . " WHERE post_id = :id", "id={$Comm['post_id']}");
                    $Link = BASE . "/artigo/{$Read->getResult()[0]['post_name']}";
                    $Title = $Read->getResult()[0]['post_title'];
                elseif ($Comm['pdt_id']):
                    $Read->FullRead("SELECT pdt_name, pdt_title FROM " . DB_PDT . " WHERE pdt_id = :id", "id={$Comm['pdt_id']}");
                    $Link = BASE . "/produto/{$Read->getResult()[0]['pdt_name']}";
                    $Title = $Read->getResult()[0]['pdt_title'];
                elseif ($Comm['page_id']):
                    $Read->FullRead("SELECT page_name, page_title FROM " . DB_PAGES . " WHERE page_id = :id", "id={$Comm['page_id']}");
                    $Link = BASE . "/pagina/{$Read->getResult()[0]['page_name']}";
                    $Title = $Read->getResult()[0]['page_title'];
                endif;
                $Stars = str_repeat("<span class='icon-star-full icon-notext'></span>", $Comm['rank']);

                //AVISA AUTHOR SOBRE RESPOSTA
                $Email = new Email;
                require '../_tpl/Client.email.php';

                $Read->FullRead("SELECT user_name, user_lastname, user_email FROM " . DB_USERS . " WHERE user_id = :id", "id={$Comm['user_id']}");
                //EMAIL DE RESPOSTA A COMENTÁRIO
                $BodyMail = "
                    <p>Olá, você está recebendo esse e-mail pois recentemente deixou um comentário em <a title='{$Title}' href='{$Link}' target='_blank'>{$Title}</a>. Obrigado por seu comentário {$Read->getResult()[0]['user_name']}!</p>
                    <p>Meu nome é {$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}. Sou membro da equipe oficial " . SITE_NAME . ", e acabo de responder seu comentário :)</p>
                    <p>Acesse agora mesmo o link abaixo para ver minha resposta, e caso queira você pode continuar comentando...<p>
                    <p><a title='Ver Comentário' href='{$Link}#comment{$Create->getResult()}' target='_blank'>VER/RESPONDER COMENTÁRIO!</a></p>
                    <p>Qualquer dúvida que venha a ter, não deixe de responder este e-mail. E vamos atende-lo o mais breve possível.</p>
                    <p>Muito obrigado por sua interação conosco {$Read->getResult()[0]['user_name']}. Espero ter atendido suas espectativas em minha resposta...</p>
                    <p><i>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']} - " . SITE_NAME . "</i></p>
                    ";
                $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                $Email->EnviarMontando("{$Read->getResult()[0]['user_name']}, seu comentário foi respondido!", $Mensagem, SITE_NAME, MAIL_USER, "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}", $Read->getResult()[0]['user_email']);

                //AVISA SOBRE RESPOSTA NA RESPOSTA :P
                if ($ResponseTo != $Comm['user_id']):
                    $Read->FullRead("SELECT user_name, user_lastname, user_email FROM " . DB_USERS . " WHERE user_id = :id", "id={$ResponseTo}");
                    //EMAIL DE RESPOSTA A RESPOSTA
                    $BodyMail = "
                    <p>Olá, você está recebendo esse e-mail pois recentemente deixou um comentário em <a title='{$Title}' href='{$Link}' target='_blank'>{$Title}</a>. Obrigado por seu comentário {$Read->getResult()[0]['user_name']}!</p>
                    <p>Meu nome é {$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}. Sou membro da equipe oficial " . SITE_NAME . ", e deixei uma resposta para você :)</p>
                    <p>Acesse agora mesmo o link abaixo para ver minha resposta, e caso queira você pode continuar comentando...<p>
                    <p><a title='Ver Comentário' href='{$Link}#comment{$Create->getResult()}' target='_blank'>VER/RESPONDER COMENTÁRIO!</a></p>
                    <p>Qualquer dúvida que venha a ter, não deixe de responder este e-mail. E vamos atende-lo o mais breve possível.</p>
                    <p>Muito obrigado por sua interação conosco {$Read->getResult()[0]['user_name']}. Espero ter atendido suas espectativas em minha resposta...</p>
                    <p><i>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']} - " . SITE_NAME . "</i></p>
                    ";
                    $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                    $Email->EnviarMontando("{$Read->getResult()[0]['user_name']}, existe uma nova resposta em seu comentário!", $Mensagem, SITE_NAME, MAIL_USER, "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}", $Read->getResult()[0]['user_email']);
                endif;

                //RETORNA RESPOSTA
                $jSON['comment'] = "
                    <article class='single_comment single_response ajax_response' id='{$Create->getResult()}'>
                        <div class='comm_thumb'>
                            <img alt='{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}' title='{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}' src='../tim.php?src=uploads/{$_SESSION['userLogin']['user_thumb']}&w=150&h=150'>
                        </div>
                        <div class='comm_content'>
                            <header>
                                <h1><a title='Perfil do Usuário' href='dashboard.php?wc=users/create&id={$_SESSION['userLogin']['user_id']}'>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}</a></h1>
                                <p>Em <a target='_blank' title='Ver Comentário' href='{$Link}#comment{$Create->getResult()}'>{$Title}  - {$Stars}</a></p>
                            </header>
                            <p class='htmlchars'>" . nl2br($PostData['comment']) . "</p>
                            <div class='comm_actions' id='{$Create->getResult()}'>
                                " . date('d/m/y H\hi', strtotime($PostData['created'])) . " - 
                                <a href='#{$Create->getResult()}' class='wc_comment_action font_blue' rel='{$Create->getResult()}' action='like' title='Curtir Comentário'>CURTIR</a>
                                <span class='font_green aprove'><b class='icon-checkmark icon-notext'></b></span>
                                <a href='#{$Create->getResult()}' class='wc_comment_open font_purple' rel='{$Create->getResult()}' action='response' title='Responder Comentário'>RESPONDER</a>
                                <a href='#{$Create->getResult()}' class='wc_comment_action font_red' rel='{$Create->getResult()}' action='remove' title='Remover Comentário'>DELETAR</a></div>
                                <div class='comm_likes icon-heart' id='{$Create->getResult()}'><span>N/A</span></div>
                                <form name='send_response' action='' method='post' class='form_{$Create->getResult()} ajax_off'>
                                    <input type='hidden' name='alias_id' value='{$PostData['alias_id']}'>
                                    <input type='hidden' name='user_id' value='{$_SESSION['userLogin']['user_id']}'>
                                    <textarea rows='5' name='comment' required=''></textarea>

                                    <img class='form_load none' style='margin-left: 10px;' alt='Enviando Requisição!' title='Enviando Requisição!' src='_img/load.gif'>
                                    <button class='btn btn_blue al_right'>Enviar Resposta!</button>
                                    <span class='wc_comment_close icon-cancel-circle btn btn_red' id='{$Create->getResult()}'>Fechar</span>
                                </form>
                            </div>
                        </div>
                    </article>
                ";

                //AVISO DE RESPOSTA EFETUADA
                $jSON['trigger'] = AjaxErro("<b class='icon-checkmark'>Sua resposta foi enviada com sucesso!</span>");
            else:
                $jSON['trigger'] = AjaxErro("<span class='icon-warning'>Desculpe {$_SESSION['userLogin']['user_name']}, mas não foi possível recuperar o comentário que deseja responder!</span><p><b>Experimente atualizar a página :/</b></p>", E_USER_ERROR);
            endif;
            break;

        //DELETAR
        case 'remove':
            $Read->FullRead("SELECT alias_id FROM " . DB_COMMENTS . " WHERE id = :id", "id={$PostData['id']}");
            if ($Read->getResult()):
                $Comment = $Read->getResult()[0]['alias_id'];
                $Read->FullRead("SELECT id FROM " . DB_COMMENTS . " WHERE status > 1 AND alias_id = :id AND id != :this", "id={$Comment}&this={$PostData['id']}");
                if (!$Read->getResult()):
                    $UpdateData = ['status' => 1];
                    $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$Comment}");
                    $jSON['alias'] = $Comment;
                    $jSON['aprove'] = "<b class='icon-checkmark icon-notext'></b>";
                endif;
            endif;

            $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE comm_id = :id OR comm_id IN(SELECT id FROM " . DB_COMMENTS . " WHERE alias_id = :id)", "id={$PostData['id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE alias_id = :id", "id={$PostData['id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE id = :id", "id={$PostData['id']}");
            $jSON['remove'] = true;
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
