<?php

/**
 * <b>Create.class:</b>
 * Classe responsável por cadastros genéricos no banco de dados!
 * 
 * @copyright (c) 2013, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Create {

    private $Tabela;
    private $Dados;
    private $Result;

    /** @var PDOStatement */
    private $Create;

    /** @var PDO */
    private $Conn;
    
    /* Obtém conexão do banco de dados Singleton */
    public function __construct() {
        $this->Conn = Conn::getConn();
    }

    /**
     * <b>ExeCreate:</b> Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com nome da coluna e valor!
     * 
     * @param STRING $Tabela = Informe o nome da tabela no banco!
     * @param ARRAY $Dados = Informe um array atribuitivo. ( Nome Da Coluna => Valor ).
     */
    public function ExeCreate($Tabela, array $Dados) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;

        $this->getSyntax();
        $this->Execute();
    }

    /**
     * <b>ExeCreateMulti:</b> Executa um cadastro múltiplo no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array multidimensional com nome da coluna e valores!
     * 
     * @param STRING $Tabela = Informe o nome da tabela no banco!
     * @param ARRAY $Dados = Informe um array multidimensional. ( [] = Key => Value ).
     */
    public function ExeCreateMulti($Tabela, array $Dados) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;

        $Fileds = implode(', ', array_keys($this->Dados[0]));
        $Places = null;
        $Inserts = null;
        $Links = count(array_keys($this->Dados[0]));

        foreach ($Dados as $ValueMult):
            $Places .= '(';
            $Places .= str_repeat('?,', $Links);
            $Places .= '),';
            
            foreach ($ValueMult as $ValueSingle):
                $Inserts[] = $ValueSingle;
            endforeach;      
        endforeach;
        
        $Places = str_replace(',)', ')', $Places);
        $Places = substr($Places, 0, -1);
        $this->Dados = $Inserts;
        
        $this->Create = "INSERT INTO {$this->Tabela} ({$Fileds}) VALUES {$Places}";
        $this->Execute();
    }

    /**
     * <b>Obter resultado:</b> Retorna o ID do registro inserido ou FALSE caso nenhum registro seja inserido! 
     * @return INT $Variavel = lastInsertId OR FALSE
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    //Obtém o PDO e Prepara a query
    private function Connect() {
        $this->Create = $this->Conn->prepare($this->Create);
    }

    //Cria a sintaxe da query para Prepared Statements
    private function getSyntax() {
        $Fileds = implode(', ', array_keys($this->Dados));
        $Places = ':' . implode(', :', array_keys($this->Dados));
        $this->Create = "INSERT INTO {$this->Tabela} ({$Fileds}) VALUES ({$Places})";
    }

    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute() {
        $this->Connect();
        try {
            $this->Create->execute($this->Dados);
            $this->Result = $this->Conn->lastInsertId();
        } catch (PDOException $e) {
            $this->Result = null;
            Erro("<b>Erro ao cadastrar:</b> {$e->getMessage()}", $e->getCode());
        }
    }

}
