<?php

class Googl {

    private $ApiUrl;
    private $ApiKey;
    private $LongUrl;
    private $DataUrl;

    /**
     * Método construtor 
     * 
     * Verifica se existem as funções curl_init() e json_decode() 
     *  utilizadas pela classe 
     */
    public function __construct($GoogleApiKey) {
        $this->ApiKey = $GoogleApiKey;
        $this->ApiUrl = 'https://www.googleapis.com/urlshortener/v1/url?fields=id%2ClongUrl&key=' . $this->ApiKey;
    }

    public function Short($Link) {
        $this->LongUrl = $Link;
        $this->DataUrl = array();
        $this->DataUrl['longUrl'] = $this->LongUrl;
        $this->DataUrl['key'] = $this->ApiKey;
        $this->DataUrl = json_encode($this->DataUrl);

        $Ch = curl_init();
        curl_setopt($Ch, CURLOPT_URL, $this->ApiUrl);
        curl_setopt($Ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($Ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($Ch, CURLOPT_HEADER, 0);
        curl_setopt($Ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($Ch, CURLOPT_POST, 1);
        curl_setopt($Ch, CURLOPT_POSTFIELDS, $this->DataUrl);
        $this->DataUrl = json_decode(curl_exec($Ch));
        curl_close($Ch);

        return $this->DataUrl->id;
    }

}