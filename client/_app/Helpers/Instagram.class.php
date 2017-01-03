<?php

/**
 * Instagram [ HELPER ]
 * Simples classe para obter imagens do instagram
 * @copyright (c) year, Robson V. Leite - UPINSIDE TECNOLOGIA
 */
class Instagram {

    private $UserId;
    private $Token;
    private $Api;
    private $Result;

    /**
     * <b>APP:</b> Para obter o token acesse (https://www.instagram.com/developer/) para criar sua APP. Em secure desmarque a opção
     * Disable implicit OAuth e salve sua APP. <b>Anote o CLIENTE_ID e o REDIRECT URL</b><br><br>
     * <b>AUTH:</b> Para autenticar acesse https://www.instagram.com/oauth/authorize/?client_id=CLINETE_ID&redirect_uri=REDIRECT_URL&response_type=token&scope=<b>public_content|basic</b>
     * Você será solicitado para permissão, depois disso basta copiar o parâmetro TOKEM. Até o primeiro ponto(.) você tem o userId. A chave completa é o AcessToken
     * @param type $userId = Id obtido pelo oAuth
     * @param type $AcessToken = Tokem optido pelo oAuth
     */
    public function __construct($userId, $AcessToken) {
        $this->UserId = $userId;
        $this->Token = $AcessToken;
    }

    /**
     * @return type = Obtém últimos 20 posts do instagram!
     */
    public function getRecent() {
        $this->Api = "https://api.instagram.com/v1/users/{$this->UserId}/media/recent/?access_token={$this->Token}";
        return $this->Instagram();
    }

    public function getTags($Tag) {
        $this->Api = "https://api.instagram.com/v1/tags/{$Tag}/media/recent?access_token={$this->Token}";
        return $this->Instagram();
    }

    private function Instagram() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->Api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $this->Result = curl_exec($ch);
        curl_close($ch);
        return json_decode($this->Result);
    }

}
