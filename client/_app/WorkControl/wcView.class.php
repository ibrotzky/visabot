<?php

/**
 * View.class [ HELPER MVC ]
 * Reponsável por carregar o template, povoar e exibir a view, povoar e incluir arquivos PHP no sistem.
 * Arquitetura MVC!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class wcView {

    private $Path;
    private $Data;
    private $Keys;
    private $Values;
    private $Template;
    
    public function __construct($Path) {
        $this->Path = "{$Path}";
    }

    /**
     * <b>Carregar Template View:</b> Dentro da pasta do seu template, crie a pasta _tpl e armazene as
     * <b>template_views</b>.tpl.html. Depois basta informar APENAS O NOME do arquivo para carregar o mesmo!
     * @param STRING $Template = Nome_do_arquivo
     */
    public function wcLoad($Template) {
        $this->Template = file_get_contents("{$this->Path}/{$Template}.tpl.php");
        return $this->Template;
    }

    /**
     * <b>Exibir Template View:</b> Execute um foreach com um getResult() do seu model e informe o envelope
     * neste método para configurar a view. Não esqueça de carregar a view acima do foreach com o método Load.
     * @param array $Data = Array com dados obtidos
     * @param View $View = Template carregado pelo método Load()
     */
    public function wcShow(array $Data, $View) {
        $this->setKeys($Data);
        $this->setValues();
        $this->ShowView($View);
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Executa o tratamento dos campos para substituição de chaves na view.
    private function setKeys($Data) {
        $this->Data = $Data;
        $this->Keys = explode('&', '{' . implode("}&{", array_keys($this->Data)) . '}');
    }

    //Obtém os valores a serem inseridos nas chaves da view.
    private function setValues() {
        $this->Values = array_values($this->Data);
    }

    //Exibe o template view com echo!
    private function ShowView($View) {
        $this->Template = $View;
        $this->Template = str_replace(array_keys(get_defined_constants(true)['user']), array_values(get_defined_constants(true)['user']), $this->Template);
        echo str_replace($this->Keys, $this->Values, $this->Template);
    }

}
