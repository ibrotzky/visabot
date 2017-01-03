<?php
$AdminLevel = 6;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

?>
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-home">Dashboard</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
        </p>
    </div>
</header>

<div class="dashboard_content">
    
    <div class="box box25">
        <div class="panel_header success">
            <span>
                <a href="javascript:void(0)" class="btn btn_green icon-loop icon-notext" id="loopDashboard"></a>
            </span>
            <h2 class="icon-earth">ONLINE AGORA:</h2>
        </div>
        <div class="panel dashboard_onlinenow">
            <?php
            $Read->FullRead("SELECT count(online_id) AS total from " . DB_VIEWS_ONLINE . " WHERE online_endview >= NOW()");
            echo "<p class='icon-users wc_useronline'>" . str_pad($Read->getResult()[0]['total'], 4, 0, STR_PAD_LEFT) . "</p>";
            ?>
            <a class="icon-target" href="dashboard.php?wc=onlinenow" title="Ver Usuários Online">ACOMPANHAR USUÁRIOS</a>
            <div class="clear"></div>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header info">
            <h2 class="icon-stats-dots">VISITAS HOJE:</h2>
        </div>
        <div class="panel dashboard_stats">
            <?php
            $Read->ExeRead(DB_VIEWS_VIEWS, "WHERE views_date = date(NOW())");
            if (!$Read->getResult()):
                echo "<p class='wc_viewsusers'><b>0000</b><span>Usuários</span></p>";
                echo "<p class='wc_viewsviews'><b>0000</b><span>Visitas</span></p>";
                echo "<p class='wc_viewspages'><b>0000</b><span>Páginas</span></p>";
                echo "<h3 class='wc_viewsstats icon-shuffle'><b>0.00</b> Páginas por Visita</h3>";
            else:
                $Views = $Read->getResult()[0];
                $Stats = number_format($Views['views_pages'] / $Views['views_views'], 2, '.', '');
                echo "<p class='wc_viewsusers'><b>" . str_pad($Views['views_users'], 4, 0, STR_PAD_LEFT) . "</b><span>Usuários</span></p>";
                echo "<p class='wc_viewsviews'><b>" . str_pad($Views['views_views'], 4, 0, STR_PAD_LEFT) . "</b><span>Visitas</span></p>";
                echo "<p class='wc_viewspages'><b>" . str_pad($Views['views_pages'], 4, 0, STR_PAD_LEFT) . "</b><span>Páginas</span></p>";
                echo "<h3 class='wc_viewsstats icon-shuffle'><b>{$Stats}</b> Páginas por Visita</h3>";
            endif;
            ?>
            <div class="clear"></div>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header alert">
            <h2 class="icon-stats-dots">VISITAS NO MÊS:</h2>
        </div>
        <div class="panel dashboard_stats">
            <?php
            $Read->FullRead("SELECT sum(views_users) AS users, sum(views_views) AS views, sum(views_pages) AS pages FROM " . DB_VIEWS_VIEWS . " WHERE year(views_date) = year(NOW()) AND month(views_date) = month(NOW())");
            if (!$Read->getResult()):
                echo "<p>0000<span>Usuários</span></p>";
                echo "<p>0000<span>Visitas</span></p>";
                echo "<p>0000<span>Páginas</span></p>";
                echo "<h3 class='icon-shuffle'>0.00 Páginas por Visita</h3>";
            else:
                $mViews = $Read->getResult()[0];
                $Stats = (!empty($mViews['pages']) ? number_format($mViews['pages'] / $mViews['views'], 2, '.', '') : '0.00');
                echo "<p>" . str_pad($mViews['users'], 4, 0, STR_PAD_LEFT) . "<span>Usuários</span></p>";
                echo "<p>" . str_pad($mViews['views'], 4, 0, STR_PAD_LEFT) . "<span>Visitas</span></p>";
                echo "<p>" . str_pad($mViews['pages'], 4, 0, STR_PAD_LEFT) . "<span>Páginas</span></p>";
                echo "<h3 class='icon-shuffle'>{$Stats} Páginas por Visita</h3>";
            endif;
            ?>
            <div class="clear"></div>
        </div>
    </div>

    <div class="box box25">
        <div class="panel_header warning">
            <h2 class="icon-stats-dots">VENDAS NO MÊS:</h2>
        </div>
        <div class="panel dashboard_stats">
            <?php
            $Read->FullRead("SELECT count(order_id) AS total FROM " . DB_ORDERS . " WHERE year(order_date) = year(NOW()) AND month(order_date) = month(NOW())");
            $OrderAll = $Read->getResult()[0]['total'];

            $Read->FullRead("SELECT count(order_id) AS total FROM " . DB_ORDERS . " WHERE order_status = :st AND year(order_date) = year(NOW()) AND month(order_date) = month(NOW())", "st=1");
            $OrderApp = $Read->getResult()[0]['total'];

            $Read->setPlaces("st=2");
            $OrderRep = $Read->getResult()[0]['total'];

            $Read->FullRead("SELECT SUM(order_price) AS total FROM " . DB_ORDERS . " WHERE order_status = :st AND year(order_date) = year(NOW()) AND month(order_date) = month(NOW())", "st=1");
            $OrderTot = $Read->getResult()[0]['total'];

            echo "<p>" . str_pad($OrderAll, 4, 0, STR_PAD_LEFT) . "<span>Todos</span></p>";
            echo "<p>" . str_pad($OrderApp, 4, 0, STR_PAD_LEFT) . "<span>Aprovados</span></p>";
            echo "<p>" . str_pad($OrderRep, 4, 0, STR_PAD_LEFT) . "<span>Cancelados</span></p>";
            echo "<h3 class='icon-coin-dollar'>R$ " . number_format($OrderTot, '2', ',', '.') . " em vendas</h3>";
            ?>
            <div class="clear"></div>
        </div>
    </div>

    <?php if (APP_PRODUCTS): ?>
        <div class="box box50">
            <div class="panel_header success">
                <h2 class="icon-bubbles4">ÚLTIMOS PEDIDOS:</h2>
            </div>
            <div class="panel dashboard_orders">
                <?php
                $Read->ExeRead(DB_ORDERS, "ORDER BY date(order_date) DESC, order_status DESC LIMIT 10");
                if (!$Read->getResult()):
                    echo Erro("<span class='icon-info al_center'>Ainda Não Existem Pedidos em Seu Site!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Order):
                        extract($Order);
                        echo "<p class='order'><span><a title='Detalhes do pedido' href='dashboard.php?wc=orders/order&id={$order_id}'>#" . str_pad($order_id, 7, 0, 0) . "</a></span><span>" . date("d/m/Y", strtotime($order_date)) . "</span><span>R$ " . number_format($order_price, '2', ',', '.') . "</span><span>" . getOrderStatus($order_status) . "</span></p>";
                    endforeach;
                endif;
                ?>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    endif;
    if (APP_COMMENTS):
        ?>
        <div class="box box50">
            <div class="panel_header info">
                <h2 class="icon-eye-plus">COMENTÁRIOS/AVALIAÇÕES:</h2>
            </div>
            <div class="panel dashboard_comments">
                <?php
                $Read->ExeRead(DB_COMMENTS, "WHERE alias_id IS NULL ORDER BY status DESC, interact DESC LIMIT 5");
                if (!$Read->getResult()):
                    echo Erro("<span class='icon-info al_center'>Ainda Não Existem Comentários em Seu Site!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Comm):
                        $Read->FullRead("SELECT user_id, user_name, user_lastname, user_thumb FROM " . DB_USERS . " WHERE user_id = :id", "id={$Comm['user_id']}");
                        $UserId = $Read->getResult()[0]['user_id'];
                        $User = "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}";
                        $Photo = ($Read->getResult()[0]['user_thumb'] && file_exists("../uploads/{$Read->getResult()[0]['user_thumb']}") ? "../tim.php?src=uploads/{$Read->getResult()[0]['user_thumb']}&w=100&h=100" : '../tim.php?src=admin/_img/no_avatar.jpg&w=100&h=100');

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
                        <article class='{$Status}'>
                            <div class='thumb'>
                                <img alt='{$User}' title='{$User}' src='{$Photo}'/>
                            </div>
                            <div class='comment'>
                                <h1><a title='Perfil do Usuário' href='dashboard.php?wc=users/create&id={$UserId}'>{$User}</a> - {$Created}</h1>
                                <p>em <a target='_blank' title='Ver Comentário' href='" . BASE . "/{$Link}#comment{$Comm['id']}'>{$Title}</a> - {$Stars}</p>
                            </div>
                        </article>
                    ";
                    endforeach;
                endif;
                ?>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    elseif (APP_POSTS):
        ?>
        <div class="box box50">
            <div class="panel_header info">
                <h2 class="icon-eye-plus">ÚLTIMOS POSTS/BLOG:</h2>
            </div>
            <div class="panel dashboard_mostviews">
                <?php
                $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 ORDER BY post_date DESC LIMIT 5");
                if (!$Read->getResult()):
                    echo Erro("<span class='icon-info al_center'>Ainda não existem posts cadastrados!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Post):
                        echo "
                        <article>
                            <img src='" . BASE . "/tim.php?src=uploads/{$Post['post_cover']}&w=" . IMAGE_W / 6 . "&h=" . IMAGE_H / 6 . "' title='{$Post['post_title']}' alt='{$Post['post_title']}'/>
                            <div class='info'>
                                <span>{$Post['post_views']} visitas</span>
                                <h1><a href='dashboard.php?wc=posts/create&id={$Post['post_id']}' title='Ver Post'>{$Post['post_title']}</a></h1>
                            </div>
                         </article>
                    ";
                    endforeach;
                endif;
                ?>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    endif;
    if (APP_SEARCH):
        ?>
        <div class="box box100">
            <div class="panel_header alert">
                <h2 class="icon-search">ÚLTIMAS PESQUISAS (30 DIAS):</h2>
            </div>
            <div class="panel dashboard_search">
                <?php
                $Read->ExeRead(DB_SEARCH, "WHERE search_commit >= date(NOW() - INTERVAL 30 DAY) ORDER BY search_commit DESC, search_count DESC LIMIT 5");
                if (!$Read->getResult()):
                    echo Erro("<span class='icon-info al_center'>Seus usuários ainda não pesquisaram em seu site. Assim que isso acontecer você poderá receber dicas de conteúdo pelas pesquisas realizadas!</span>", E_USER_NOTICE);
                    echo "<div class='clear'></div>";
                else:
                    foreach ($Read->getResult() as $Search):
                        extract($Search);
                        $Read->FullRead("SELECT post_id FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW() AND (post_title LIKE '%' :s '%' OR post_subtitle LIKE '%' :s '%')", "s={$search_key}");
                        $ResultPosts = $Read->getRowCount();

                        $Read->FullRead("SELECT pdt_id FROM " . DB_PDT . " WHERE pdt_status = 1 AND (pdt_title LIKE '%' :s '%' OR pdt_subtitle LIKE '%' :s '%')", "s={$search_key}");
                        $ResultPdts = $Read->getRowCount();
                        echo "
                            <article>
                               <h1 class='icon-search'><a href='dashboard.php?wc=posts/search&s=" . urlencode($search_key) . "' title='Ver resultados'>{$search_key}</a></h1>
                               <p>DIA " . date('d/m/Y H\hi', strtotime($search_date)) . "</p>
                               <p>" . str_pad($search_count, 4, 0, STR_PAD_LEFT) . " VEZES</p>
                               <p>" . str_pad($ResultPosts + $ResultPdts, 4, 0, STR_PAD_LEFT) . " RESULTADOS</p>
                            </article>
                        ";
                    endforeach;
                endif;
                ?>
                <a class="dashboard_searchnowlink" href="dashboard.php?wc=searchnow" title="Ver Mais">MAIS PESQUISAS!</a>
                <div class="clear"></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    //ICON REFRESH IN DASHBOARD
    $('#loopDashboard').click(function(){
        Dashboard();
    });
    
    //DASHBOARD REALTIME
    setInterval(function () {
        Dashboard();
    }, 10000);
</script>