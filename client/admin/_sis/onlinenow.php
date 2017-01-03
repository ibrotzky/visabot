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
        <h1 class="icon-earth">Usuários Online Agora</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Online Agora
        </p>
    </div>
</header>

<div class="dashboard_content">
    
    <div class="box box100">
        
        <div class="panel_header success">
            <span>
                <a href="javascript:void(0)" class="btn btn_green icon-loop icon-notext" id="loopOnlineNow"></a>
            </span>
            <?php $Read->ExeRead(DB_VIEWS_ONLINE, "WHERE online_endview >= NOW() ORDER BY online_endview DESC"); ?>
            <h2 class="icon-earth jwc_onlinenow">ONLINE AGORA: <?= str_pad($Read->getRowCount(), 4, 0, 0); ?></h2>
        </div>
        
        <div class="panel wc_onlinenow dashboard_online">
            <?php
            if (!$Read->getResult()):
                echo Erro('<span class="icon-earth al_center">Não existem usuários online neste momento!</span>', E_USER_NOTICE);
                echo '<div class="clear"></div>';
            else:
                $i = 0;
                foreach ($Read->getResult() as $Online):
                    $i++;
                    $Name = ($Online['online_name'] ? "<a href='dashboard.php?wc=" . (APP_EAD ? 'teach/students_gerent' : 'users/create') . "&id={$Online['online_user']}' title='Ver Cliente'>{$Online['online_name']}</a>" : 'guest user');
                    $Date = date('d/m/Y H\hi', strtotime($Online['online_startview']));

                    echo "<div class='single_onlinenow'>
                    <p>" . str_pad($i, 4, 0, STR_PAD_LEFT) . "</p>
                    <p>{$Name}</p>
                    <p>{$Date}</p>
                    <p>{$Online['online_ip']}</p>
                    <p><a target='_blank' href='" . BASE . "/{$Online['online_url']}' title='Ver Destino'>" . ($Online['online_url'] ? $Online['online_url'] : 'home') . "</a></p>
                    </div>";
                endforeach;
            endif;
            ?>
        </div>
    </div>
</div>

<script>
    //ICON REFRESH IN DASHBOARD
    $('#loopOnlineNow').click(function(){
        OnlineNow();
    });
    
    //DASHBOARD REALTIME
    setInterval(function () {
        OnlineNow();
    }, 3000);
</script>