<?php

$getContact = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($getContact) || empty($getContact['action'])):
    die('Acesso Negado!');
endif;

$strPost = array_map('strip_tags', $getContact);
$POST = array_map('trim', $getContact);

$Action = $POST['action'];
$jSON = null;
unset($POST['action']);

usleep(2000);

require '../../../_app/Config.inc.php';
$Email = new Email;

switch ($Action):
    //CART ADD ITEM ON CLICK ADD
    case 'wc_send_contact':
        if (in_array('', $POST)):
            $jSON['wc_contact_error'] = "<p class='wc_concact_error'>&#10008; Favor preencha todos os campos para enviar seu contato!</p>";
        elseif (!Check::Email($POST['email']) || !filter_var($POST['email'], FILTER_VALIDATE_EMAIL)):
            $jSON['wc_contact_error'] = "<p class='wc_concact_error'>&#10008; O e-mail informado não parece válido. Favor informe seu e-mail!</p>";
        else:
            require './contact.email.php';
            //SEND TO CLIENTE
            $ToCliente = "
                    <p style='font-size: 1.2em;'>Prezado(a) {$POST['nome']},</p>
                    <p><b>Obrigado por entrar em contato conosco.</b></p>
                    <p>Este e-mail é para informar que recebemos sua mensagem, e que estaremos respondendo o mais breve possível.</p>
                    <p><em>Atenciosamente " . SITE_NAME . ".</em></p>
            ";
            $MailMensage = str_replace("#mail_body#", $ToCliente, $MailContent);
            $Email->EnviarMontando("Recebemos sua mensagem", $MailMensage, SITE_ADDR_NAME, SITE_ADDR_EMAIL, $POST['nome'], $POST['email']);

            //SEND TO ADMIN
            $ToAdmin = "
                    <p>" . nl2br($POST['message']) . "</p>
                    <p style='font-size: 0.9em;'>
                        Enviada por: {$POST['nome']}<br>
                        E-mail: {$POST['email']}<br>
                        Telefone: {$POST['phone']}<br>
                        Dia: " . date('d/m/Y H\hi') . "
                    </p>
            ";
            $Email = new Email;
            $CopyMensage = str_replace("#mail_body#", $ToAdmin, $MailContent);
            $Email->EnviarMontando("Nova mensagem do site", $CopyMensage, $POST['nome'], $POST['email'], SITE_ADDR_NAME, SITE_ADDR_EMAIL);

            $jSON['wc_send_mail'] = $POST['nome'];
        endif;
        break;
endswitch;

echo json_encode($jSON);
