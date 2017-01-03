<?php

/**
 * ActiveCampaign.class [ API ]
 * Classe criada para gerar CRM integrado ao ActiveCampaign. Gestão, manipulação e tagueamento!
 * @copyright (c) 2015, Robson V. Leite - UPINSIDE TECNOLOGIA (https://www.upinside.com.br)
 * @link Crie sua conta AC (http://www.activecampaign.com/?_r=4VQ7Y48B)
 */
class ActiveCampaign {
    private $acUrl;
    private $acKey;
    private $acApi;
    private $UserId;
    private $UserEmail;
    private $UserName;
    private $UserLastName;
    private $UserTags;
    private $UserLists;

    /**
     * Acesse <b>Configurações -> Developer</b> para pegar seu URL e seu KEY e iniciar a classe!
     * @param STR $URL O URL da sua API ActiveCampaign
     * @param STR $KEY O KEY da sua API ActiveCampaign
     * @return STR Armazena $this->acUrl com link de disparo de API!
     */
    public function __construct($URL, $KEY) {
        $this->acUrl = $URL;
        $this->acKey = $KEY;
        $this->acApi = $this->acUrl . "/admin/api.php?api_key=" . $this->acKey . "&api_output=json&api_action=";
    }

    /**
     * Informe o E-MAIL do usuário para obter os dados do mesmo no ActiveCapaign. Retorna o UserId para getão e armazena Nome, Sobrenome, E-mail, Listas e Tags para consulta no objeto!
     * @param STR $Email E-mail válido do usuário!
     * @return INT Id do usuário no ActiveCampaign
     */
    public function contactGet($Email) {
        $this->UserEmail = $Email;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_view_email&email={$this->UserEmail}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            $this->UserId = $UserData->id;
            $this->UserName = $UserData->first_name;
            $this->UserLastName = $UserData->last_name;
            $this->UserTags = $UserData->tags;

            $this->UserLists = array();
            $ListsUser = (array) $UserData->lists;
            foreach ($ListsUser as $List => $Values):
                $ListData = ['list' => $Values->listname, 'status' => $Values->status];
                $this->UserLists[$List] = $ListData;
            endforeach;

            return $this->UserId;
        else:
            return false;
        endif;
    }

    /**
     * Cadastra o usuário no AC via API. Podendo definir e-mail, nome, sobrenome, listas e tags do mesmo! Seguindo os seguites formatos!
     * @param STR $Email E-mail válido do usuário!
     * @param ARRAY $Lists ID`s das listas de cadastro ['1', '2', '3']
     * @param ARRAY $Tags Tags que deseja adicionar ['Aluno UpInside', 'Prospecto Cadastro']
     * @param STR $FirstName Primeiro Nome do Usuário!
     * @param STR $LastName Sobrenome do Usuário!
     * @return INT Id do usuário no ActiveCampaign!
     */
    public function contactAdd($Email, array $Lists, array $Tags = null, $FirstName = null, $LastName = null) {
        $Add = ['email' => $Email, 'first_name' => $FirstName, 'last_name' => $LastName];

        if ($Lists):
            foreach ($Lists as $ListId):
                $Add["p[{$ListId}]"] = $ListId;
            endforeach;
        endif;

        if ($Tags):
            foreach ($Tags as $TagId => $Tag):
                $Add["tags[{$TagId}]"] = $Tag;
            endforeach;
        endif;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_add");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Add);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            $this->UserId = $UserData->subscriber_id;
            return $this->UserId;
        else:
            return false;
        endif;
    }

    /**
     * Edita os dados como Email, Nome, e sobrenome do usuário pelo Acive Id do mesmo!, Defina como <b>null</b> qualquer dado para não alterar!
     * @param INT $UserActiveId Id do usuário no ActiveCampaign! <b>$UserActiveId = $this->userGet($Email)</b>!
     * @param STR $Email E-mail do usuário!
     * @param STR $FirstName Primeiro Nome do Usuário!
     * @param STR $LastName Sobrenome do Usuário!
     * @return BOOL True ou False
     */
    public function contactEdit($UserActiveId, $Email, $FirstName, $LastName) {
        $this->UserId = $UserActiveId;

        $EditArr = [
            'id' => $this->UserId,
            'email' => $Email,
            'first_name' => $FirstName,
            'last_name' => $LastName
        ];

        $Edit = array_filter($EditArr);

        foreach ($this->UserLists as $ListId => $ListValues):
            $Edit["p[{$ListId}]"] = $ListId;
            $Edit["status[{$ListId}]"] = $ListValues['status'];
        endforeach;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_edit");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Edit);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * Deleta o usuário do seu ActiveCampaign por completo!
     * @param INT $UserActiveId Id do usuário no ActiveCampaign! <b>$UserActiveId = $this->userGet($Email)</b>!
     * @return BOOL True ou False
     */
    public function contactDelete($UserActiveId) {
        $this->UserId = $UserActiveId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_delete&id={$this->UserId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * Adiciona o usuário de uma ou de várias LISTAS no ActiveCampaign!
     * @param INT $Lists pode ser uma string com uma lista ($Lists = '22') ou um array com várias listas ($Lists = ['22', '23'])!
     * @return BOOL True ou False
     */
    public function contactListAdd($UserActiveId, $Lists) {
        $this->UserId = $UserActiveId;

        foreach ($this->UserLists as $ListId => $ListValues):
            $Edit["p[{$ListId}]"] = $ListId;
            $Edit["status[{$ListId}]"] = $ListValues['status'];
        endforeach;

        if (is_array($Lists)):
            foreach ($Lists as $AddList):
                $Edit["p[{$AddList}]"] = $AddList;
                $Edit["status[{$AddList}]"] = 1;
            endforeach;
        else:
            $Edit["p[{$Lists}]"] = $Lists;
            $Edit["status[{$Lists}]"] = 1;
        endif;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_edit");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Edit);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * Remove o usuário de uma ou de várias LISTAS no ActiveCampaign! *Não para automações!
     * @param INT $Lists pode ser uma string com uma lista ($Lists = '22') ou um array com várias listas ($Lists = ['22', '23'])!
     * @return BOOL True ou False
     */
    public function contactListRemove($UserActiveId, $Lists) {
         $this->UserId = $UserActiveId;
         $Edit['id'] = $this->UserId;

        foreach ($this->UserLists as $ListId => $ListValues):
            $Edit["p[{$ListId}]"] = $ListId;
            $Edit["status[{$ListId}]"] = $ListValues['status'];
        endforeach;

        if (is_array($Lists)):
            foreach ($Lists as $AddList):
                $Edit["p[{$AddList}]"] = null;
                $Edit["status[{$AddList}]"] = null;
            endforeach;
        else:
            $Edit["p[{$Lists}]"] = null;
            $Edit["status[{$Lists}]"] = null;
        endif;
        
        $Edit = array_filter($Edit);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_edit");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Edit);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * Adiciona uma ou várias TAGS ao usuário no ActiveCampaign!
     * @param STR $Tags pode ser uma string com uma tag ($Tags = 'Aluno UpInside') ou um array com várias tags ($Tags = ['Tag 1', 'Tag 2'])!
     * @return BOOL True ou False
     */
    public function contactTagAdd($UserActiveId, $Tags) {
        $this->UserId = $UserActiveId;
        $Add['id'] = $this->UserId;

        if (is_array($Tags)):
            foreach ($Tags as $TagId => $Tag):
                $Add["tags[{$TagId}]"] = $Tag;
            endforeach;
        else:
            $Add["tags[0]"] = $Tags;
        endif;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_tag_add");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Add);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * Remove uma ou várias TAGS ao usuário no ActiveCampaign!
     * @param STR $Tags pode ser uma string com uma tag ($Tags = 'Aluno UpInside') ou um array com várias tags ($Tags = ['Tag 1', 'Tag 2'])!
     * @return BOOL True ou False
     */
    public function contactTagRemove($UserActiveId, $Tags) {
        $this->UserId = $UserActiveId;
        $Add['id'] = $this->UserId;

        if (is_array($Tags)):
            foreach ($Tags as $TagId => $Tag):
                $Add["tags[{$TagId}]"] = $Tag;
            endforeach;
        else:
            $Add["tags[0]"] = $Tags;
        endif;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->acApi . "contact_tag_remove");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Add);
        $Return = curl_exec($ch);
        $UserData = json_decode($Return);
        curl_close($ch);

        if ($UserData->result_code == 1):
            return true;
        else:
            return false;
        endif;
    }
}
