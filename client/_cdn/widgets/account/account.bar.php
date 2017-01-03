<?php

if (!empty($URL[0]) && !empty($URL[1]) && $URL[0] == 'conta' && $URL[1] == 'sair'):
    unset($_SESSION['userLogin']);
endif;

if (!empty($_SESSION['userLogin'])):
    $AccSaudation = (!ACC_TAG ? "Olá {$_SESSION['userLogin']['user_name']}!" : ACC_TAG);
    echo "<a title='Minha Conta' href='" . BASE . "/conta/home'>{$AccSaudation}</a>";
else:
    echo "<a title='Minha Conta' href='" . BASE . "/conta/login'>Logar-se!</a>";
endif;
