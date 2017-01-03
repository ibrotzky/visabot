<form name="account_form" action="" method="post" enctype="multipart/form-data">
    <div class="account_form_fields">
        <div class="account_form_callback"><?= (!empty($AccountRecoverError) ? $AccountRecoverError : ''); ?></div>
        <label>
            <span>E-mail:</span>
            <input name="user_email" type="email" placeholder="Informe seu E-mail:" required/>
        </label>
    </div>

    <input type="hidden" name="action" value="wc_recover"/>

    <div class="account_form_actions">
        <button class="btn btn_blue">Recuperar Senha!</button>
        <img alt="Recuperando Senha!" title="Recuperando Senha!" src="<?= BASE; ?>/_cdn/widgets/account/load.gif"/>
        <div>&nbsp;</div>
        <a title="Voltar e Logar!" href="<?= $AccountBaseUI; ?>/login#acc">Voltar e logar-se!</a>
    </div>
</form>