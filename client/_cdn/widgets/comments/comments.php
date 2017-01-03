<?php

if (empty($CommentKey) || empty($CommentType)):
    Erro('<b>&#9888; COMMENT ERROR:</b> Para iniciar os comentários é preciso definir as variáveis de identificação! Você pode criar comentários para qualquer APP do Work Control, bastando definir as seguintes variáveis:<p><b>$CommentType:</b> Id do post, página, produto ou curso que receberá os comentários!</p><p><b>$CommentKey:</b> post, page, product ou course (Destino do comentário!)</p>', E_USER_WARNING);
    return;
else:
    $_SESSION['comm'] = array();
endif;

if (empty($Read)):
    $Read = new Read;
endif;

echo '<link rel="stylesheet" href="' . BASE . '/_cdn/bootcss/reset.css"/>';
echo '<link rel="stylesheet" href="' . BASE . '/_cdn/widgets/comments/comments.css"/>';
echo '<script src="' . BASE . '/_cdn/widgets/comments/comments.js"></script>';

//USUÁRIO
if (!empty($_SESSION['userLogin'])):
    $UserName = " {$_SESSION['userLogin']['user_name']}";
else:
    $UserName = null;
endif;

//COMMENT TYPE
$CommentModerate = (COMMENT_MODERATE ? " AND (status = 1 OR status = 3)" : '');
if ($CommentType == 'post'):
    //TOTAL COMMENTS
    $Read->FullRead("SELECT count(id) AS total FROM " . DB_COMMENTS . " WHERE post_id = :post OR alias_id IN(SELECT id FROM " . DB_COMMENTS . " WHERE post_id = :post)", "post={$CommentKey}");
    $CommentCount = $Read->getResult()[0]['total'];

    //COMMENTS
    $Read->ExeRead(DB_COMMENTS, "WHERE post_id = :post{$CommentModerate} AND alias_id IS NULL ORDER BY created " . COMMENT_ORDER, "post={$CommentKey}");
    $CommentTitle = $post_title;
    $_SESSION['comm']['post_id'] = $CommentKey;

    echo "<section class='comments' id='comments'>";
    echo "<header><h1>Olá{$UserName}, deixe seu comentário para <span>{$CommentTitle}</span></h1></header>";
elseif ($CommentType == 'page'):
    //TOTAL COMMENTS
    $Read->FullRead("SELECT count(id) AS total FROM " . DB_COMMENTS . " WHERE page_id = :post OR alias_id IN(SELECT id FROM " . DB_COMMENTS . " WHERE page_id = :post)", "post={$CommentKey}");
    $CommentCount = $Read->getResult()[0]['total'];

    //COMMENTS
    $Read->ExeRead(DB_COMMENTS, "WHERE page_id = :post{$CommentModerate} AND alias_id IS NULL ORDER BY created " . COMMENT_ORDER, "post={$CommentKey}");
    $CommentTitle = $page_title;
    $_SESSION['comm']['page_id'] = $CommentKey;

    echo "<section class='comments' id='comments'>";
    echo "<header><h1>Avalie o conteúdo desta página!</h1></header>";
elseif ($CommentType == 'product'):
    //TOTAL COMMENTS
    $Read->FullRead("SELECT count(id) AS total FROM " . DB_COMMENTS . " WHERE pdt_id = :post OR alias_id IN(SELECT id FROM " . DB_COMMENTS . " WHERE pdt_id = :post)", "post={$CommentKey}");
    $CommentCount = $Read->getResult()[0]['total'];

    //COMMENTS
    $Read->ExeRead(DB_COMMENTS, "WHERE pdt_id = :post{$CommentModerate} AND alias_id IS NULL ORDER BY created " . COMMENT_ORDER, "post={$CommentKey}");
    $CommentTitle = $pdt_title;
    $_SESSION['comm']['pdt_id'] = $CommentKey;

    echo "<section class='comments' id='comments'>";
    echo "<header><h1>{$CommentTitle}<span>Confira opiniões e avaliações de clientes!</span></h1></header>";
endif;

if ($Read->getResult()):
    echo "<div class='comments_count'>Já temos {$CommentCount} comentário(s). DEIXE O SEU :)</div>";
    foreach ($Read->getResult() as $Comment):
        $Read->FullRead("SELECT user_id, user_thumb, user_name, user_lastname FROM " . DB_USERS . " WHERE user_id = :id", "id={$Comment['user_id']}");
        if (!$Read->getResult()):
            $Delete = new Delete;
            $Delete->ExeDelete(DB_COMMENTS, "WHERE id = :id OR alias_id = :id", "id={$Comment['id']}");
            header("Location: " . BASE . "/{$getURL}");
        else:
            $UserComment = $Read->getResult()[0];
            $UserAvatar = ($UserComment['user_thumb'] ? BASE . "/tim.php?src=uploads/{$UserComment['user_thumb']}&w=" . AVATAR_W . "&h=" . AVATAR_H : BASE . "/tim.php?src=admin/_img/no_avatar.jpg&w=" . AVATAR_W . "&h=" . AVATAR_H);
        endif;

        $CommentStars = str_repeat("&starf;", $Comment['rank']) . str_repeat("&star;", 5 - $Comment['rank']);

        echo "<article class='comments_single' id='comment{$Comment['id']}'>";
        echo "<div class='comments_single_avatar'><img alt='{$UserComment['user_name']} {$UserComment['user_lastname']}' title='{$UserComment['user_name']} {$UserComment['user_lastname']}' src='{$UserAvatar}'/></div>";
        echo "<div class='comments_single_content'>";
        echo "<header><h1>{$UserComment['user_name']} {$UserComment['user_lastname']}</h1></header>";
        echo "<div class='comments_single_comment'>" . nl2br($Comment['comment']) . "</div>";

        //LIKE COUNT
        $Read->FullRead("SELECT count(id) as total FROM " . DB_COMMENTS_LIKES . " WHERE comm_id = :comm", "comm={$Comment['id']}");
        $LikeCount = $Read->getResult()[0]['total'];

        //LIKES
        $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_id IN(SELECT user_id FROM " . DB_COMMENTS_LIKES . " WHERE comm_id = :comm)", "comm={$Comment['id']}");
        if ($Read->getResult()):
            $getLikes = array();
            foreach ($Read->getResult() as $UserLike):
                if (!empty($_SESSION['userLogin']) && $_SESSION['userLogin']['user_id'] == $UserLike['user_id']):
                    $getLikes[] = "<span><b>EU</b></span>";
                    $LikeThisPost = true;
                else:
                    $getLikes[] = "<span>{$UserLike['user_name']} {$UserLike['user_lastname']}</span>";
                endif;

            endforeach;
            $Likes = implode(', ', $getLikes);
        else:
            $Likes = '<span class="na">N/A</span>';
        endif;

        echo "<div class='comments_single_ui'>";
        echo "<span class='stars'>{$CommentStars}</span>";
        echo "<span class='date'>DIA " . date('d.m.y H\hi', strtotime($Comment['created'])) . "</span>";
        if (empty($LikeThisPost)):
            echo "<span class='like wc_like' id='{$Comment['id']}'><b>{$LikeCount}</b> GOSTEI :)</span>";
        else:
            $LikeThisPost = null;
            echo "<span class='liked'><b>{$LikeCount}</b> VOCÊ JÁ CURTIU ISSO!</span>";
        endif;
        echo "<span class='response wc_response' id='{$Comment['id']}' rel='{$UserComment['user_id']}'>RESPONDER</span>";
        echo "</div>"; //Ui
        echo "<div class='comments_single_likes' id='{$Comment['id']}'><span>{$Likes}</span></div>";

        //FORM RESPONSE
        require dirname(__DIR__) . '/comments/comment.form.php';


        //#############################
        //################# SUBCOMMENTS
        //#############################
        $Read->ExeRead(DB_COMMENTS, "WHERE alias_id = :id{$CommentModerate} ORDER BY created " . COMMENT_RESPONSE_ORDER, "id={$Comment['id']}");
        if ($Read->getResult()):
            foreach ($Read->getResult() as $Response):
                $Read->FullRead("SELECT user_id, user_thumb, user_name, user_lastname FROM " . DB_USERS . " WHERE user_id = :id", "id={$Response['user_id']}");
                if (!$Read->getResult()):
                    $Delete = new Delete;
                    $Delete->ExeDelete(DB_COMMENTS, "WHERE id = :id OR alias_id = :id", "id={$Response['id']}");
                    header("Location: " . BASE . "/{$getURL}");
                else:
                    $UserComment = $Read->getResult()[0];
                    $UserAvatar = ($UserComment['user_thumb'] ? BASE . "/tim.php?src=uploads/{$UserComment['user_thumb']}&w=" . AVATAR_W . "&h=" . AVATAR_H : BASE . "/tim.php?src=admin/_img/no_avatar.jpg&w=" . AVATAR_W . "&h=" . AVATAR_H);
                endif;

                $CommentStars = str_repeat("&starf;", $Response['rank']) . str_repeat("&star;", 5 - $Response['rank']);

                echo "<article class='comments_single comment_response' id='comment{$Response['id']}'>";
                echo "<div class='comments_single_avatar'><img alt='{$UserComment['user_name']} {$UserComment['user_lastname']}' title='{$UserComment['user_name']} {$UserComment['user_lastname']}' src='{$UserAvatar}'/></div>";
                echo "<div class='comments_single_content'>";
                echo "<header><h1>{$UserComment['user_name']} {$UserComment['user_lastname']}</h1></header>";
                echo "<div class='comments_single_comment'>" . nl2br($Response['comment']) . "</div>";

                //LIKE COUNT
                $Read->FullRead("SELECT count(id) as total FROM " . DB_COMMENTS_LIKES . " WHERE comm_id = :comm", "comm={$Response['id']}");
                $LikeCount = $Read->getResult()[0]['total'];

                //LIKES
                $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_id IN(SELECT user_id FROM " . DB_COMMENTS_LIKES . " WHERE comm_id = :comm)", "comm={$Response['id']}");
                if ($Read->getResult()):
                    $getLikes = array();
                    foreach ($Read->getResult() as $UserLike):
                        if (!empty($_SESSION['userLogin']) && $_SESSION['userLogin']['user_id'] == $UserLike['user_id']):
                            $getLikes[] = "<span><b>EU</b></span>";
                            $LikeThisPost = true;
                        else:
                            $getLikes[] = "<span>{$UserLike['user_name']} {$UserLike['user_lastname']}</span>";
                        endif;
                    endforeach;
                    $Likes = implode(', ', $getLikes);
                else:
                    $Likes = '<span class="na">N/A</span>';
                endif;

                echo "<div class='comments_single_ui'>";
                echo "<span class='stars'>{$CommentStars}</span>";
                echo "<span class='date'>DIA " . date('d.m.y H\hi', strtotime($Response['created'])) . "</span>";
                if (empty($LikeThisPost)):
                    echo "<span class='like wc_like' id='{$Response['id']}'><b>{$LikeCount}</b> GOSTEI :)</span>";
                else:
                    $LikeThisPost = null;
                    echo "<span class='liked'><b>{$LikeCount}</b> VOCÊ JÁ CURTIU ISSO!</span>";
                endif;
                echo "<span class='response wc_response' id='{$Response['id']}' rel='{$UserComment['user_id']}'>RESPONDER</span>";
                echo "</div>"; //Ui
                echo "<div class='comments_single_likes' id='{$Response['id']}'><span>{$Likes}</span></div>";

                //FORM RESPONSE
                require dirname(__DIR__) . '/comments/comment.form.php';

                echo "</div>"; //Content
                echo "</article>";
            endforeach;
        endif;
        //END SUBCOMMENTS

        echo "</div>"; //Content
        echo "</article>";
    endforeach;
endif;

require dirname(__DIR__) . '/comments/comment.form.php';
echo "</section>";

//FORM LOGIN
require dirname(__DIR__) . '/comments/comment.modal.php';
