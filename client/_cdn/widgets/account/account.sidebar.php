<?php

echo "<aside class='workcontrol_account_sidebar'>";

echo "<header>";
$Avatar = (!empty($user_thumb) ? "uploads/{$user_thumb}" : "admin/_img/no_avatar.jpg");
echo "<img class='account_user_avatar' src='" . BASE . "/tim.php?src={$Avatar}&w=" . AVATAR_W . "&h=" . AVATAR_H . "' default='" . BASE . "/tim.php?src={$Avatar}&w=" . AVATAR_W . "&h=" . AVATAR_H . "' title='{$user_name}' alt='{$user_name}'/>";
echo "<h1>{$user_name} {$user_lastname}</h1>";
echo "<p>{$user_email}</p>";
echo "</header>";

echo "<nav class='workcontrol_account_sidebar_nav'>";
echo "<ul class='workcontrol_account_sidebar_nav'>";
echo "<li><a " . ($AccountAction == 'home' ? 'class="active"' : '') . " href='{$AccountBaseUI}/home#acc' title='Minha Conta'>Minha Conta</a></li>";

if (APP_PRODUCTS):
    echo "<li><a " . ($AccountAction == 'pedidos' || $AccountAction == 'pedido' ? 'class="active"' : '') . " href='{$AccountBaseUI}/pedidos#acc' title='Meus Pedidos'>Meus Pedidos</a></li>";
//echo "<li><a " . ($AccountAction == 'enderecos' ? 'class="active"' : '') . " href='{$AccountBaseUI}/enderecos#acc' title='Meus Endereços'>Meus Endereços</a></li>";
endif;

//echo "<li><a " . ($AccountAction == 'contato' ? 'class="active"' : '') . " href='{$AccountBaseUI}/contato#acc' title='Fale Conosco'>Fale Conosco</a></li>";
echo "<li><a " . ($AccountAction == 'dados' ? 'class="active"' : '') . " href='{$AccountBaseUI}/dados#acc' title='Atualizar Dados'>Atualizar Dados</a></li>";
echo "<li><a class='logoff' href='{$AccountBaseUI}/sair' title='Desconectar'>Desconectar!</a></li>";
echo "</ul>";
echo "</nav>";
echo "</aside>";

