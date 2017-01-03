<article class='comment_login_box'>
    <div class='comment_login_content'>
        <header>
            <h1><?= SITE_NAME; ?> Login:</h1>
            <p>Informe seu e-mail abaixo para continuar!</p>
            <div class="comment_login_error"></div>
            <span class="comment_login_close">X</span>
        </header>

        <form name='comment_login' class="ajax_off wc_comment_login" action="" method="post" enctype="multipart/form-data">
            <input class="comment_login_action" type="hidden" value="" name="action"/>
            <div class="comment_login_fields">
                <label class="comment_label">
                    <span>Qual seu E-mail?</span>
                    <input class="wc_login_email" type="email" name="user_email" placeholder="E-mail:" required/>
                </label>

                <label class="comment_label comment_login_create">
                    <span>Nome:</span>
                    <input type="text" name="user_name" placeholder="Primeiro Nome:"/>
                </label>

                <label class="comment_label comment_login_create">
                    <span>Sobrenome:</span>
                    <input type="text" name="user_lastname" placeholder="Último Nome:"/>
                </label>

                <label class="comment_label comment_login_join">
                    <span>Senha:</span>
                    <input type="password" name="user_password" placeholder="informe sua senha:"/>
                </label>

                <label class="comment_recover_label">
                    <span>ENVIAMOS SEU CÓDIGO PARA: <b></b></span>
                    <input type="text" name="user_code" placeholder="Código de Acesso:"/>
                    <span style="text-align: center">
                        <span class='comment_recover_password'>Não recebi o código!</span>
                        <span class='comment_recover_back'>Logar!</span>
                    </span>
                </label>

                <div class="clear"></div>
            </div>
            <div class="comment_login_actions">
                <button class="btn btn_green">Enviar Comentário!</button>
                <img class="load" alt="Enviando Comentário" title="Enviando Comentário" src="<?= BASE; ?>/_cdn/widgets/comments/load_g.gif">
            </div>
        </form>
    </div>
</article>