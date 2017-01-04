<?php
if (!$Read):
    $Read = new Read;
endif;

$Email = new Email;

$Read->ExeRead(DB_PAGES, "WHERE page_name = :nm", "nm={$URL[0]}");
if (!$Read->getResult()):
    require REQUIRE_PATH . '/404.php';
    return;
else:
    extract($Read->getResult()[0]);
endif;
?>
<div class="container page_single">
    <div class="content">
        <div class="page_content">
            <header>
                <h1><?= $page_title; ?></h1>
                <p><?= $page_subtitle; ?></p>
            </header>
            <div class="htmlchars">
                <?php
                $Contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if ($Contato && $Contato['action'] == 'contact'):
                    unset($Contato['action']);

                    if (in_array('', $Contato)):
                        Erro("Para enviar seu contato, favor preencha todos os campos!", E_USER_WARNING);
                    elseif (!Check::Email($Contato['email']) || !filter_var($Contato['email'], FILTER_VALIDATE_EMAIL)):
                        Erro("Desculpe, mas o e-mail que você informou não tem um formato válido!", E_USER_ERROR);
                    else:
                        array_map('strip_tags', $Contato);

                        $MailContent = '
                        <table width="550" style="font-family: "Trebuchet MS", sans-serif;">
                         <tr><td>
                          <font face="Trebuchet MS" size="3">
                           <p>Novo contato de ' . $Contato['nome'] . '</p>
                           <p><b>MENSAGEM:</b> ' . $Contato['mensagem'] . ' </p>
                          </font>
                          <p style="font-size: 0.875em;">
                          <img src="' . BASE . '/admin/_img/mail.jpg" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /><br><br>
                           ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
                           <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a><br>' . SITE_ADDR_ADDR . '<br>'
                                . SITE_ADDR_CITY . '/' . SITE_ADDR_UF . ' - ' . SITE_ADDR_ZIP . '<br>' . SITE_ADDR_COUNTRY . '
                          </p>
                          </td></tr>
                        </table>
                        <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';

                        $Email = new Email;
                        $Email->EnviarMontando($Contato['assunto'], $MailContent, $Contato['nome'], $Contato['email'], SITE_ADDR_NAME, MAIL_USER);
                        if (!$Email->getError()):
                            $_SESSION['sucesso'] = "Sua mensagem foi enviada com sucesso!";
                            header('Location: ' . BASE . '/contato');
                        else:
                            Erro("Desculpe, não foi possível enviar sua mensagem. Entre em contato via " . SITE_ADDR_EMAIL . ". Obrigado!", E_USER_ERROR);
                        endif;
                    endif;
                endif;

                if (!empty($_SESSION['sucesso']) && empty($Contato)):
                    Erro($_SESSION['sucesso']);
                    unset($_SESSION['sucesso']);
                endif;
                ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="contact"/>
                    <label>
                        <span>Nome:</span>
                        <input type="text" placeholder="Seu Nome" name="nome" required/>
                    </label>
                    <label>
                        <span>E-mail:</span>
                        <input type="text" placeholder="E-mail" name="email" required/>
                    </label>
                    <label>
                        <span>Assunto:</span>
                        <input type="text" placeholder="Qual o assunto do contato?" name="assunto" required/>
                    </label>
                    <label>
                        <span>Mensagem:</span>
                        <textarea name="mensagem" rows="5" placeholder="Deixe sua mensagem:" required></textarea>
                    </label>

                    <button class="btn btn_blue">Enviar Contato!</button>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>