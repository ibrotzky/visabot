<form name="account_form" action="" method="post" enctype="multipart/form-data">
    <div class="account_form_fields">
        <div class="account_form_callback"></div>
        <label>
            <span>Nome:</span>
            <input name="user_name" type="text" placeholder="Nome:" required/>
        </label>
        <label>
            <span>Sobrenome:</span>
            <input name="user_lastname" type="text" placeholder="Sobrenome:" required/>
        </label>

        <label>
            <span>E-mail:</span>
            <input name="user_email" type="email" placeholder="E-mail:" required/>
        </label>

        <label>
            <span>Senha:</span>
            <input name="user_password" type="password" placeholder="Senha:" required/>
        </label>
    </div>

    <input type="hidden" name="action" value="wc_create"/>

    <div class="account_form_actions">
        <button class="btn btn_blue">Criar minha Conta!</button>
        <img alt="Criar minha Conta!" title="Criar minha Conta!" src="<?= BASE; ?>/_cdn/widgets/account/load.gif"/>
        <div>&nbsp;</div>
        <a title="Criar minha Conta!" href="<?= $AccountBaseUI; ?>/login#acc">Voltar e Logar!</a>
    </div>
</form>