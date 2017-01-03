<?php

require_once __DIR__ . '/../Library/PHPMailer/class.phpmailer.php';
require_once __DIR__ . '/../Library/PHPMailer/class.smtp.php';

/**
 * Email [ MODEL ]
 * Modelo responsável por configurar a PHPMailer, validar os dados e disparar e-mails do sistema!
 * 
 * @copyright (c) year, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Email {

    /** @var PHPMailer */
    private $Mail;

    /** EMAIL DATA */
    private $Data;

    /** CORPO DO E-MAIL */
    private $Assunto;
    private $Mensagem;

    /** REMETENTE */
    private $RemetenteNome;
    private $RemetenteEmail;

    /** DESTINO */
    private $DestinoNome;
    private $DestinoEmail;

    /** CONSTROLE */
    private $Error;
    private $Result;

    function __construct() {
        $this->Mail = new PHPMailer;
        $this->Mail->Host = MAIL_HOST;
        $this->Mail->Port = MAIL_PORT;
        $this->Mail->Username = MAIL_USER;
        $this->Mail->Password = MAIL_PASS;
        $this->Mail->SMTPAuth = true;
//        $this->Mail->SMTPSecure = 'ssl';
    }

    /**
     * <b>Enviar E-mail SMTP:</b> Envelope os dados do e-mail em um array atribuitivo para povoar o método.
     * Com isso execute este para ter toda a validação de envio do e-mail feita automaticamente.
     * 
     * <b>REQUER DADOS ESPECÍFICOS:</b> Para enviar o e-mail você deve montar um array atribuitivo com os
     * seguintes índices corretamente povoados:<br><br>
     * <i>
     * &raquo; Assunto<br>
     * &raquo; Mensagem<br>
     * &raquo; RemetenteNome<br>
     * &raquo; RemetenteEmail<br>
     * &raquo; DestinoNome<br>
     * &raquo; DestinoEmail
     * </i>
     */
    public function Enviar(array $Data) {
        $this->Data = $Data;
        $this->Clear();

        if (in_array('', $this->Data)):
            $this->Error = "<b>ERRO AO ENVIAR E-MAIL:</b> Dados informados são insuficientes para disparo de mensagem!";
            $this->Result = false;
        elseif (!Check::Email($this->Data['RemetenteEmail'])):
            $this->Error = "<b>ERRO AO ENVIAR E-MAIL:</b> O endereço de e-mail informado para o remetente não tem um formato válido!";
            $this->Result = false;
        else:
            $Data['RemetenteNome'] = ($Data['RemetenteNome'] != 'null' ? $Data['RemetenteNome'] : null);
            $this->setMail();
            $this->Config();
            $this->sendMail();
        endif;
    }

    /**
     * <b>Montar e Enviar:</b> Execute este método para facilitar o envio. Informando os parâmetros solicitados para montar a data! 
     */
    public function EnviarMontando($Assunto, $Mensagem, $RemetenteNome, $RemetenteEmail, $DestinoNome, $DestinoEmail) {
        $Data['Assunto'] = $Assunto;
        $Data['Mensagem'] = $Mensagem;
        $Data['RemetenteNome'] = $RemetenteNome;
        $Data['RemetenteEmail'] = $RemetenteEmail;
        $Data['DestinoNome'] = $DestinoNome;
        $Data['DestinoEmail'] = $DestinoEmail;
        $this->Enviar($Data);
    }

    /**
     * <b>Enviar Anexo:</b> Efetue o Upload da imagem com a classe de upload. Com o getResult() deste envio, basta anexar ao e-mail! 
     */
    public function addFile($File) {
        $this->File = $this->Mail->addAttachment($File);
    }

    /**
     * <b>Verificar Envio:</b> Executando um getResult é possível verificar se foi ou não efetuado 
     * o envio do e-mail. Para mensagens execute o getError();
     * @return BOOL $Result = TRUE or FALSE
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com o erro e o tipo de erro.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Limpa código e espaços!
    private function Clear() {
        array_map('strip_tags', $this->Data);
        array_map('trim', $this->Data);
    }

    //Recupera e separa os atributos pelo Array Data.
    private function setMail() {
        $this->Assunto = $this->Data['Assunto'];
        $this->Mensagem = $this->Data['Mensagem'];
        $this->RemetenteNome = $this->Data['RemetenteNome'];
        $this->RemetenteEmail = $this->Data['RemetenteEmail'];
        $this->DestinoNome = $this->Data['DestinoNome'];
        $this->DestinoEmail = $this->Data['DestinoEmail'];
        $this->Data = null;
    }

    //Configura o PHPMailer e valida o e-mail!
    private function Config() {
        //SMTP AUTH
        $this->Mail->CharSet = 'utf-8';
        $this->Mail->setLanguage('pt');
        $this->Mail->IsSMTP();
        $this->Mail->IsHTML(true);


        //REMETENTE E RETORNO
        $this->Mail->From = MAIL_USER;
        $this->Mail->FromName = $this->RemetenteNome;
        $this->Mail->AddReplyTo($this->RemetenteEmail, $this->RemetenteNome);

        //ASSUNTO, MENSAGEM E DESTINO
        $this->Mail->Subject = $this->Assunto;
        $this->Mail->msgHTML($this->Mensagem);
        $this->Mail->AddAddress($this->DestinoEmail, $this->DestinoNome);
    }

    //Envia o e-mail!
    private function sendMail() {
        if ($this->Mail->Send()):
            $this->Error = null;
            $this->Result = true;

            $this->Mail->clearAddresses();
        else:
            $this->Error = '<b>ERRO AO ENVIAR E-MAIL:</b> ' . $this->Mail->ErrorInfo;
            $this->Result = false;
        endif;
    }

}
