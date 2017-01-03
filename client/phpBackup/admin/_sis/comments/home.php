<?php
$AdminLevel = LEVEL_WC_COMMENTS;
if (!APP_COMMENTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-bubbles2">Comentários</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Comentários
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Recarregar Comentários" href="dashboard.php?wc=comments/home" class="btn btn_green icon-loop">Recarregar Comentários!</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Pager = new Pager("dashboard.php?wc=comments/home&p=", "<<", ">>", 1);
    $Pager->ExePager($Page, 10);

    $Read->ExeRead(DB_COMMENTS, "WHERE alias_id IS NULL ORDER BY status DESC, created DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if (!$Read->getResult()):
        $Pager->ReturnPage();
        echo Erro("<span class='icon-info al_center'>Ainda não existem comentários {$_SESSION['userLogin']['user_name']}. Mas isso não deve demorar!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $Comm):
            $Read->FullRead("SELECT user_id, user_name, user_lastname, user_thumb FROM " . DB_USERS . " WHERE user_id = :id", "id={$Comm['user_id']}");
            $UserId = $Read->getResult()[0]['user_id'];
            $User = "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}";
            $Photo = ($Read->getResult()[0]['user_thumb'] && file_exists("../uploads/{$Read->getResult()[0]['user_thumb']}") ? "../tim.php?src=uploads/{$Read->getResult()[0]['user_thumb']}&w=150&h=150" : '../tim.php?src=admin/_img/no_avatar.jpg&w=150&h=150');

            if ($Comm['post_id']):
                $Read->FullRead("SELECT post_name, post_title FROM " . DB_POSTS . " WHERE post_id = :id", "id={$Comm['post_id']}");
                $Link = "artigo/{$Read->getResult()[0]['post_name']}";
                $Title = $Read->getResult()[0]['post_title'];
            elseif ($Comm['pdt_id']):
                $Read->FullRead("SELECT pdt_name, pdt_title FROM " . DB_PDT . " WHERE pdt_id = :id", "id={$Comm['pdt_id']}");
                $Link = "produto/{$Read->getResult()[0]['pdt_name']}";
                $Title = $Read->getResult()[0]['pdt_title'];
            elseif ($Comm['page_id']):
                $Read->FullRead("SELECT page_name, page_title FROM " . DB_PAGES . " WHERE page_id = :id", "id={$Comm['page_id']}");
                $Link = "{$Read->getResult()[0]['page_name']}";
                $Title = $Read->getResult()[0]['page_title'];
            endif;

            $Created = date('d/m/y H\hi', strtotime($Comm['created']));
            $Stars = str_repeat("<span class='icon-star-full icon-notext'></span>", $Comm['rank']);
            $Status = ($Comm['status'] >= 2 ? 'pending' : null);

            echo "
                <article class='single_comment {$Status}' id='{$Comm['id']}'>
                <div class='comm_thumb'>
                    <img alt='{$User}' title='{$User}' src='{$Photo}'/>
                </div>
                <div class='comm_content'>
                    <header>
                        <h1><a title='Perfil do Usuário' href='dashboard.php?wc=users/create&id={$UserId}'>{$User}</a></h1>
                        <p>Em <a target='_blank' title='Ver Comentário' href='" . BASE . "/{$Link}#comment{$Comm['id']}'>{$Title}  - {$Stars}</a></p>
                    </header>
                    <p class='htmlchars'>" . nl2br($Comm['comment']) . "</p>
                    <div class='comm_actions' id='{$Comm['id']}'>
                        {$Created} - 
            ";

            $Read->FullRead("SELECT id FROM " . DB_COMMENTS_LIKES . " WHERE user_id = :user AND comm_id = :comm", "user={$_SESSION['userLogin']['user_id']}&comm={$Comm['id']}");
            if (!$Read->getResult()):
                echo "<a href='#{$Comm['id']}' class='wc_comment_action font_blue' rel='{$Comm['id']}' action='like' href='Gostei do Comentário' title='Gostei do Comentário'>GOSTEI :)</a>";
            endif;

            if ($Comm['status'] >= 2):
                echo "<span class='font_green aprove'><a href='#{$Comm['id']}' class='wc_comment_action font_green' rel='{$Comm['id']}' action='aprove' href='Aprovar Comentário' title='Aprovar Comentário'>APROVAR</a></span>";
            else:
                echo "<span class='font_green aprove'><b class='icon-checkmark icon-notext'></b></span>";
            endif;

            echo "<a href='#{$Comm['id']}' class='wc_comment_open font_purple' rel='{$Comm['id']}' action='response' href='Responder Comentário' title='Responder Comentário'>RESPONDER</a>";
            echo "<a href='#{$Comm['id']}' class='wc_comment_action font_red' rel='{$Comm['id']}' action='remove' href='Remover Comentário' title='Remover Comentário'>DELETAR</a>";
            echo "</div>";

            $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_id IN(SELECT user_id FROM " . DB_COMMENTS_LIKES . " WHERE comm_id = :comm)", "comm={$Comm['id']}");
            if ($Read->getResult()):
                $getLikes = array();
                foreach ($Read->getResult() as $UserLike):
                    $getLikes[] = "<a target='_blank' title='Ver Usuário' href='dashboard.php?wc=users/create&id={$UserLike['user_id']}'>{$UserLike['user_name']} {$UserLike['user_lastname']}</a>";
                endforeach;
                $Likes = implode(', ', $getLikes);
            else:
                $Likes = '<span class="na">N/A</span>';
            endif;

            echo "<div class='comm_likes icon-heart' id='{$Comm['id']}'><span>{$Likes}</span></div>";
            echo "
                <form name='send_response' action='' method='post' class='form_{$Comm['id']} ajax_off'>
                    <input type='hidden' name='alias_id' value='{$Comm['id']}'/>
                    <input type='hidden' name='user_id' value='{$Comm['user_id']}'/>
                    <textarea rows='5' name='comment'></textarea>
                    
                    <img class='form_load none' style='margin-left: 10px;' alt='Enviando Requisição!' title='Enviando Requisição!' src='_img/load.gif'/>
                    <button class='btn btn_blue al_right'>Enviar Resposta!</button>
                    <span class='wc_comment_close icon-cancel-circle btn btn_red' id='{$Comm['id']}'>Fechar</span>
                </form>
            ";

            $Read->ExeRead(DB_COMMENTS, "WHERE alias_id = :id ORDER BY created ASC", "id={$Comm['id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $Response):
                    $Read->FullRead("SELECT user_id, user_name, user_lastname, user_thumb FROM " . DB_USERS . " WHERE user_id = :id", "id={$Response['user_id']}");
                    $UserId = $Read->getResult()[0]['user_id'];
                    $User = "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}";
                    $Photo = ($Read->getResult()[0]['user_thumb'] && file_exists("../uploads/{$Read->getResult()[0]['user_thumb']}") ? "../tim.php?src=uploads/{$Read->getResult()[0]['user_thumb']}&w=150&h=150" : '../tim.php?src=admin/_img/no_avatar.jpg&w=150&h=150');

                    if ($Response['post_id']):
                        $Read->FullRead("SELECT post_name, post_title FROM " . DB_POSTS . " WHERE post_id = :id", "id={$Response['post_id']}");
                        $Link = "artigo/{$Read->getResult()[0]['post_name']}";
                        $Title = $Read->getResult()[0]['post_title'];
                    elseif ($Response['pdt_id']):
                        $Read->FullRead("SELECT pdt_name, pdt_title FROM " . DB_PDT . " WHERE pdt_id = :id", "id={$Response['pdt_id']}");
                        $Link = "produto/{$Read->getResult()[0]['pdt_name']}";
                        $Title = $Read->getResult()[0]['pdt_title'];
                    elseif ($Response['page_id']):
                        $Read->FullRead("SELECT page_name, page_title FROM " . DB_PAGES . " WHERE page_id = :id", "id={$Response['page_id']}");
                        $Link = "{$Read->getResult()[0]['page_name']}";
                        $Title = $Read->getResult()[0]['page_title'];
                    endif;

                    $Created = date('d/m/y H\hi', strtotime($Response['created']));
                    $Stars = str_repeat("<span class='icon-star-full icon-notext'></span>", $Response['rank']);
                    $Status = ($Response['status'] >= 2 ? 'pending' : null);

                    echo "
                        <article class='single_comment single_response {$Status}' id='{$Response['id']}'>
                            <div class='comm_thumb'>
                                <img alt='{$User}' title='{$User}' src='{$Photo}'/>
                            </div>
                            <div class='comm_content'>
                                <header>
                                    <h1><a title='Perfil do Usuário' href='dashboard.php?wc=users/create&id={$UserId}'>{$User}</a></h1>
                                    <p>Em <a target='_blank' title='Ver Comentário' href='" . BASE . "/{$Link}#comment{$Response['id']}'>{$Title}  - {$Stars}</a></p>
                                </header>
                                <p class='htmlchars'>" . nl2br($Response['comment']) . "</p>
                                <div class='comm_actions' id='{$Response['id']}'>
                                    {$Created} - 
                    ";

                    $Read->FullRead("SELECT id FROM " . DB_COMMENTS_LIKES . " WHERE user_id = :user AND comm_id = :comm", "user={$_SESSION['userLogin']['user_id']}&comm={$Response['id']}");
                    if (!$Read->getResult()):
                        echo "<a href='#{$Response['id']}' class='wc_comment_action font_blue' rel='{$Response['id']}' action='like' href='Gostei do Comentário' title='Gostei do Comentário'>GOSTEI :)</a>";
                    endif;

                    if ($Response['status'] >= 2):
                        echo "<span class='font_green aprove'><a href='#{$Response['id']}' class='wc_comment_action font_green' rel='{$Response['id']}' action='aprove' href='Aprovar Comentário' title='Aprovar Comentário'>APROVAR</a></span>";
                    else:
                        echo "<span class='font_green aprove'><b class='icon-checkmark icon-notext'></b></span>";
                    endif;

                    echo "<a href='#{$Response['id']}' class='wc_comment_open font_purple' rel='{$Response['id']}' action='response' href='Curtir Comentário' title='Responder Comentário'>RESPONDER</a>";
                    echo "<a href='#{$Response['id']}' class='wc_comment_action font_red' rel='{$Response['id']}' action='remove' href='Remover Comentário' title='Remover Comentário'>DELETAR</a>";
                    echo "</div>";

                    $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_id IN(SELECT user_id FROM " . DB_COMMENTS_LIKES . " WHERE comm_id = :comm)", "comm={$Response['id']}");
                    if ($Read->getResult()):
                        $getLikes = array();
                        foreach ($Read->getResult() as $UserLike):
                            $getLikes[] = "<a target='_blank' title='Ver Usuário' href='dashboard.php?wc=users/create&id={$UserLike['user_id']}'>{$UserLike['user_name']} {$UserLike['user_lastname']}</a>";
                        endforeach;
                        $Likes = implode(', ', $getLikes);
                    else:
                        $Likes = '<span class="na">N/A</span>';
                    endif;
                    echo "<div class='comm_likes icon-heart' id='{$Response['id']}'><span>{$Likes}</span></div>";
                    echo "
                        <form name='send_response' action='' method='post' class='form_{$Response['id']} ajax_off'>
                            <input type='hidden' name='alias_id' value='{$Comm['id']}'/>
                            <input type='hidden' name='user_id' value='{$Response['user_id']}'/>
                            <textarea rows='5' name='comment' required></textarea>

                            <img class='form_load none' style='margin-left: 10px;' alt='Enviando Requisição!' title='Enviando Requisição!' src='_img/load.gif'/>
                            <button class='btn btn_blue al_right'>Enviar Resposta!</button>
                            <span class='wc_comment_close icon-cancel-circle btn btn_red' id='{$Response['id']}'>Fechar</span>
                        </form>
                    ";
                    echo "</div></article>";
                endforeach;
            endif;
            echo " <div class='response_list'></div></div></article>";
        endforeach;
        $Pager->ExePaginator(DB_COMMENTS, "WHERE alias_id IS NULL");
        echo $Pager->getPaginator();
    endif;
    ?>
</div>
<script src="_js/wccomments.js"></script>