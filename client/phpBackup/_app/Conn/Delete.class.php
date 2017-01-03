<?php

/**
 * <b>Delete.class:</b>
 * Classe responsável por deletar genéricamente no banco de dados!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Delete {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;

    /** @var PDOStatement */
    private $Delete;

    /** @var PDO */
    private $Conn;
    
    /* Obtém conexão do banco de dados Singleton */
    public function __construct() {
        $this->Conn = Conn::getConn();
    }

    public function ExeDelete($Tabela, $Termos, $ParseString) {
        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;

        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    public function getResult() {
        return $this->Result;
    }

    public function getRowCount() {
        return $this->Delete->rowCount();
    }

    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    //Obtém o PDO e Prepara a query
    private function Connect() {
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    //Cria a sintaxe da query para Prepared Statements
    private function getSyntax() {
        $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
    }

    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Connect();
        try {
            $this->Delete->execute($this->Places);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            Erro("<b>Erro ao Deletar:</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
