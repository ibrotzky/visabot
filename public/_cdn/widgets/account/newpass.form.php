<form name="account_form" action="" method="post" enctype="multipart/form-data">
    <div class="account_form_fields">
        <div class="account_form_callback"></div>
        <label>
            <span>Nova Senha:</span>
            <input name="user_password" type="password" placeholder="Informe uma nova senha:" required/>
        </label>

        <label>
            <span>Confirmar Nova Senha:</span>
            <input name="user_password_r" type="password" placeholder="Repita sua nova senha:" required/>
        </label>
    </div>

    <input type="hidden" name="action" value="wc_newpass"/>

    <div class="account_form_actions">
        <button class="btn btn_blue">Alterar Senha!</button>
        <img alt="Alterando Senha!" title="Alterando Senha!" src="<?= BASE; ?>/_cdn/widgets/account/load.gif"/>
        <div>&nbsp;</div>
        <a title="Voltar e Logar!" href="<?= $AccountBaseUI; ?>/login#acc">Voltar e Logar!</a>
    </div>
</form>