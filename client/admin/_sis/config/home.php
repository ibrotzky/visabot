<?php
$AdminLevel = LEVEL_WC_CONFIG_MASTER;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_CATEGORIES, "WHERE category_title IS NULL AND category_content IS NULL AND category_id >= :st", "st=1");
endif;

?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-cogs">Configurações</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php">Dashboard</a>
            <span class="crumb">/</span>
            Configurações
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Reiniciar Configurações!" href="dashboard.php?wc=config/home&wc_recet_config=true" class="btn btn_yellow icon-warning wc_resetconfig">Resetar Configurações!</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $Read->FullRead("SELECT conf_type FROM " . DB_CONF . " GROUP BY conf_type ASC");
    if ($Read->getResult()):
        echo "<div class='box box70' style='padding: 0'>";
        $iForm = 0;
        foreach ($Read->getResult() as $Config):
            $Active = ($iForm == 0 ? 'wc_active' : null);
            $iForm++;
            echo "<article class='box box100 box_conf conf wc_tab_target {$Active}' id='{$Config['conf_type']}'>";
            
            // Trigger Message
            echo "<div class='trigger trigger_error icon-warning al_center' style='font-size: 0.8em;'><b>ATENÇÃO:</b> Todas as configurações a seguir interferem diretamente no funcionamento do projeto!</div>";

            echo "<div class='panel_header default'><h2 class='icon-cog'>{$Config['conf_type']}</h2></div>";
            echo "<div class='panel'>";

            $ConfMenu[] = $Config['conf_type'];

            $Read->ExeRead(DB_CONF, "WHERE conf_type = :type ORDER BY conf_key ASC", "type={$Config['conf_type']}");
            if (!$Read->getResult()):
                Erro("Não existem configurações do tipo {$Config['conf_type']}.", E_USER_WARNING);
            else:
                foreach ($Read->getResult() as $ConfType):
                    extract($ConfType);
                    echo "<form class='auto_save' name='workcontrol_conf' action='' method='post' enctype='multipart/form-data'>";
                    echo "<input type='hidden' name='callback' value='Config'/>";
                    echo "<input type='hidden' name='callback_action' value='WorkControl'/>";
                    echo "<input type='hidden' name='conf_id' value='{$conf_id}'/>";
                    echo "<label class='label'>";
                    echo "<span class='legend'>{$conf_key}</span>";
                    echo "<input name='conf_value' value='" . ($conf_value ? htmlspecialchars($conf_value, ENT_QUOTES) : 0) . "' type='text'/>";
                    echo "</label>";
                    echo '</form>';
                endforeach;
            endif;
            echo "<div class='clear'></div>";
            echo "</div>";
            echo "</article>";
        endforeach;
        echo "</div>";

        echo "<article class='box box30 box_conf_menu'>"
        . "<div class='panel'>";
        $iMenu = 0;
        foreach ($ConfMenu as $MenuConf):
            $Active = ($iMenu == 0 ? 'wc_active' : null);
            $iMenu++;
            echo "<a class='conf_menu wc_tab {$Active}' href='#{$MenuConf}'>{$MenuConf}</a>";
        endforeach;
        echo "</div>"
        . "</article>";
    else:
        $StartConfig = true;
    endif;

    $getResetConfig = filter_input(INPUT_GET, 'wc_recet_config', FILTER_VALIDATE_BOOLEAN);
    if ($getResetConfig):
        $Delete = new Delete;
        $Delete->ExeDelete(DB_CONF, "WHERE conf_id >= :conf", "conf=1");
        header("Location: dashboard.php?wc=config/home");
    endif;

    $CreateConfig = (!empty($StartConfig) ? true : false);
    if ($CreateConfig):
        foreach (get_defined_constants(true)['user'] as $Key => $Value):
            $AppType = substr($Key, 0, strpos($Key, '_'));
            $ArrCreateConf = ['conf_key' => $Key, "conf_value" => $Value, 'conf_type' => $AppType];
            $Create = new Create;
            $Create->ExeCreate(DB_CONF, $ArrCreateConf);
        endforeach;

        $Delete = new Delete;
        $Delete->ExeDelete(DB_CONF, "WHERE conf_type = :type1 OR conf_type = :type2 OR conf_type = :type3 OR conf_type = :type4 OR conf_type = :type5", "type1=DB&type2=SIS&type3=REQUIRE&type4=INCLUDE&type5=WORKCONTROL_CONFIG");

        $Update = new Update;
        $UpdateNull = ['conf_type' => 'ADMIN'];
        $Update->ExeUpdate(DB_CONF, $UpdateNull, "WHERE conf_type = :null", "null=");

        $UpdateE = ['conf_type' => 'ECOMMERCE'];
        $Update->ExeUpdate(DB_CONF, $UpdateE, "WHERE conf_type = :e", "e=E");

        $UpdateAD = ['conf_type' => 'SITE_ADDR'];
        $Update->ExeUpdate(DB_CONF, $UpdateAD, "WHERE conf_key LIKE '%SITE_ADDR%'");

        $UpdateS = ['conf_type' => 'SOCIAL'];
        $Update->ExeUpdate(DB_CONF, $UpdateS, "WHERE conf_key LIKE '%SITE_SOCIAL%'");

        $UpdateAcc = ['conf_type' => 'APP'];
        $Update->ExeUpdate(DB_CONF, $UpdateAcc, "WHERE conf_key LIKE '%ACC_%'");

        $UpdateImage = ['conf_type' => 'IMAGE'];
        $Update->ExeUpdate(DB_CONF, $UpdateImage, "WHERE conf_type = :t OR conf_type = :a OR conf_type = :s", "t=THUMB&a=AVATAR&s=SLIDE");

        header("Location: dashboard.php?wc=config/home");
    endif;
    ?>
</div>