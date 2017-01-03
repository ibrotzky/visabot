<?php
$AdminLevel = LEVEL_WC_POSTS;
if (!APP_POSTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_POSTS, "WHERE post_title IS NULL AND post_content IS NULL and post_status = :st", "st=0");

    //AUTO TRASH IMAGES
    $Read->FullRead("SELECT image FROM " . DB_POSTS_IMAGE . " WHERE post_id NOT IN(SELECT post_id FROM " . DB_POSTS . ")");
    if ($Read->getResult()):
        $Delete->ExeDelete(DB_POSTS_IMAGE, "WHERE id >= :id AND post_id NOT IN(SELECT post_id FROM " . DB_POSTS . ")", "id=1");
        foreach ($Read->getResult() as $ImageRemove):
            if (file_exists("../uploads/{$ImageRemove['image']}") && !is_dir("../uploads/{$ImageRemove['image']}")):
                unlink("../uploads/{$ImageRemove['image']}");
            endif;
        endforeach;
    endif;
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$Search = filter_input_array(INPUT_POST);
if ($Search && $Search['s']):
    $S = urlencode($Search['s']);
    header("Location: dashboard.php?wc=posts/search&s={$S}");
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-blog">Posts</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Posts
        </p>
    </div>

    <div class="dashboard_header_search">
        <form name="searchPosts" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" name="s" placeholder="Pesquisar Artigo:" required/>
            <button class="btn btn_green icon icon-search icon-notext"></button>
        </form>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager('dashboard.php?wc=posts/home&pg=', '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    $Read->ExeRead(DB_POSTS, "ORDER BY post_status ASC, post_date DESC LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Ainda não existem posts cadastrados {$Admin['user_name']}. Comece agora mesmo criando seu primeiro post!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $POST):
            extract($POST);

            $PostCover = (file_exists("../uploads/{$post_cover}") && !is_dir("../uploads/{$post_cover}") ? "uploads/{$post_cover}" : 'admin/_img/no_image.jpg');
            $PostStatus = ($post_status == 1 && strtotime($post_date) >= strtotime(date('Y-m-d H:i:s')) ? '<span class="btn btn_blue icon-clock icon-notext"></span>' : ($post_status == 1 ? '<span class="btn btn_green icon-checkmark icon-notext"></span>' : '<span class="btn btn_yellow icon-warning icon-notext"></span>'));
            $post_title = (!empty($post_title) ? $post_title : 'Edite esse rascunho para poder exibir como artigo em seu site!');

            $Category = null;
            if (!empty($post_category)):
                $Read->FullRead("SELECT category_title FROM " . DB_CATEGORIES . " WHERE category_id = :ct", "ct={$post_category}");
                if ($Read->getResult()):
                    $Category = "<span class='icon-price-tags'>{$Read->getResult()[0]['category_title']}</span> ";
                endif;
            endif;

            if (!empty($post_category_parent)):
                $Read->FullRead("SELECT category_title FROM " . DB_CATEGORIES . " WHERE category_id IN({$post_category_parent})");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $SubCat):
                        $Category .= "<span class='icon-price-tag'>{$SubCat['category_title']}</span> ";
                    endforeach;
                endif;
            endif;

            echo "<article class='box box25 post_single' id='{$post_id}'>           
                <div class='post_single_cover'>
                    <img alt='{$post_title}' title='{$post_title}' src='../tim.php?src={$PostCover}&w=" . IMAGE_W / 2 . "&h=" . IMAGE_H / 2 . "'/>
                    <div class='post_single_status'><span class='btn'>" . str_pad($post_views, 4, 0, STR_PAD_LEFT) . "</span>{$PostStatus}</div>
                    <div class='post_single_cat'>{$Category}</div>
                </div>
                <div class='box_content'>
                    <h1 class='title'>" . Check::Chars($post_title, 56) . "</h1>
                    <a title='Ver artigo no site' target='_blank' href='" . BASE . "/artigo/{$post_name}' class='icon-notext icon-eye btn btn_green'></a>
                    <a title='Editar Artigo' href='dashboard.php?wc=posts/create&id={$post_id}' class='post_single_center icon-notext icon-pencil btn btn_blue'></a>
                    <span rel='post_single' class='j_delete_action icon-notext icon-cancel-circle btn btn_red' id='{$post_id}'></span>
                    <span rel='post_single' callback='Posts' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$post_id}'>Deletar Post?</span>
                </div>
            </article>";
        endforeach;

        $Paginator->ExePaginator(DB_POSTS);
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>