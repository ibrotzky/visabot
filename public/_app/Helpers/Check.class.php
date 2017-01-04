<?php

/**
 * Check.class [ HELPER ]
 * Classe responável por manipular e validar dados do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Check {

    private static $Data;
    private static $Format;

    /**
     * <b>Verifica E-mail:</b> Executa validação de formato de e-mail. Se for um email válido retorna true, ou retorna false.
     * @param STRING $Email = Uma conta de e-mail
     * @return BOOL = True para um email válido, ou false
     */
    public static function Email($Email) {
        self::$Data = (string) $Email;
        self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';

        if (preg_match(self::$Format, self::$Data)):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * <b>Tranforma URL:</b> Tranforma uma string no formato de URL amigável e retorna a string convertida!
     * @param STRING $Name = Uma string qualquer
     * @return STRING = $Data = Uma URL amigável válida
     */
    public static function Name($Name) {
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '-', self::$Data);
        self::$Data = str_replace(array('-----', '----', '---', '--'), '-', self::$Data);

        return strtolower(utf8_encode(self::$Data));
    }

    /**
     * <b>Checa CPF:</b> Informe um CPF para checar sua validade via algoritmo!
     * @param STRING $CPF = CPF com ou sem pontuação
     * @return BOLEAM = True se for um CPF válido
     */
    public static function CPF($Cpf) {
        self::$Data = preg_replace('/[^0-9]/', '', $Cpf);

        $digitoA = 0;
        $digitoB = 0;

        for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
            $digitoA += self::$Data[$i] * $x;
        }

        for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
            if (str_repeat($i, 11) == self::$Data) {
                return false;
            }
            $digitoB += self::$Data[$i] * $x;
        }

        $somaA = (($digitoA % 11) < 2 ) ? 0 : 11 - ($digitoA % 11);
        $somaB = (($digitoB % 11) < 2 ) ? 0 : 11 - ($digitoB % 11);

        if ($somaA != self::$Data[9] || $somaB != self::$Data[10]) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * <b>Checa CNPJ:</b> Informe um CNPJ para checar sua validade via algoritmo!
     * @param STRING $CNPJ = CNPJ com ou sem pontuação
     * @return BOLEAM = True se for um CNJP válido
     */
    public static function CNPJ($Cnpj) {
        self::$Data = (string) $Cnpj;
        self::$Data = preg_replace('/[^0-9]/', '', self::$Data);

        $A = 0;
        $B = 0;

        for ($i = 0, $c = 5; $i <= 11; $i++, $c--):
            $c = ($c == 1 ? 9 : $c);
            $A += self::$Data[$i] * $c;
        endfor;

        for ($i = 0, $c = 6; $i <= 12; $i++, $c--):
            if (str_repeat($i, 14) == self::$Data):
                return false;
            endif;
            $c = ($c == 1 ? 9 : $c);
            $B += self::$Data[$i] * $c;
        endfor;

        $somaA = (($A % 11) < 2) ? 0 : 11 - ($A % 11);
        $somaB = (($B % 11) < 2) ? 0 : 11 - ($B % 11);

        if (strlen(self::$Data) != 14):
            return false;
        elseif ($somaA != self::$Data[12] || $somaB != self::$Data[13]):
            return false;
        else:
            return true;
        endif;
    }

    /**
     * <b>Tranforma Data:</b> Transforma uma data no formato DD/MM/YY em uma data no formato YYYY-MM-DD!
     * @param STRING $Name = Data em (d/m/Y)
     * @return STRING = $Data = Data no formato YYYY-MM-DD!
     */
    public static function Nascimento($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        if (checkdate(self::$Data[1], self::$Data[0], self::$Data[2])):
            self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0];
            return self::$Data;
        else:
            return false;
        endif;
    }

    /**
     * <b>Tranforma TimeStamp:</b> Transforma uma data no formato DD/MM/YY em uma data no formato TIMESTAMP!
     * @param STRING $Name = Data em (d/m/Y) ou (d/m/Y H:i:s)
     * @return STRING = $Data = Data no formato timestamp!
     */
    public static function Data($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        if (!checkdate(self::$Data[1], self::$Data[0], self::$Data[2])):
            return false;
        else:
            if (empty(self::$Format[1])):
                self::$Format[1] = date('H:i:s');
            endif;

            self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
            return self::$Data;
        endif;
    }

    /**
     * <b>Limita os Palavras:</b> Limita a quantidade de palavras a serem exibidas em uma string!
     * @param STRING $String = Uma string qualquer
     * @param INT $name Description INT = $Limite = String limitada pelo $Limite
     */
    public static function Words($String, $Limite, $Pointer = null) {
        self::$Data = strip_tags(trim($String));
        self::$Format = (int) $Limite;

        $ArrWords = explode(' ', self::$Data);
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$Format));

        $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer );
        $Result = ( self::$Format < $NumWords ? $NewWords . $Pointer : self::$Data );
        return $Result;
    }

    /**
     * <b>Limita os Caracteres:</b> Limita a quantidade de letras a serem exibidas em uma string!
     * @param STRING $String = Uma string qualquer
     * @param INT $name Description INT = $Limite = String limitada pelo $Limite
     */
    public static function Chars($String, $Limite) {
        self::$Data = strip_tags($String);
        self::$Format = $Limite;
        if (strlen(self::$Data) <= self::$Format) {
            return self::$Data;
        } else {
            $subStr = strrpos(substr(self::$Data, 0, self::$Format), ' ');
            return substr(self::$Data, 0, $subStr) . '...';
        }
    }

    /**
     * <b>Obter categoria:</b> Informe o name (url) de uma categoria para obter o ID da mesma.
     * @param STRING $category_name = URL da categoria
     * @return INT $category_id = id da categoria informada
     */
    public static function CatByName($CategoryName) {
        $read = new Read;
        $read->ExeRead(DB_CATEGORIES, "WHERE category_name = :name", "name={$CategoryName}");
        if ($read->getRowCount()):
            return $read->getResult()[0]['category_id'];
        else:
            return false;
            die;
        endif;
    }

    /**
     * <b>Usuários Online:</b> Ao executar este HELPER, ele automaticamente deleta os usuários expirados. Logo depois
     * executa um READ para obter quantos usuários estão realmente online no momento!
     * @return INT = Qtd de usuários online
     */
    public static function UserOnline() {
        $now = date('Y-m-d H:i:s');
        $deleteUserOnline = new Delete;
        $deleteUserOnline->ExeDelete(DB_VIEWS_ONLINE, "WHERE online_endview < :now", "now={$now}");

        $readUserOnline = new Read;
        $readUserOnline->ExeRead(DB_VIEWS_ONLINE);
        return $readUserOnline->getRowCount();
    }

    /**
     * <b>Imagem Upload:</b> Ao executar este HELPER, ele automaticamente verifica a existencia da imagem na pasta
     * uploads. Se existir retorna a imagem redimensionada!
     * @return HTML = imagem redimencionada!
     */
    public static function Image($ImageUrl, $ImageDesc, $ImageW = null, $ImageH = null) {

        self::$Data = $ImageUrl;

        if (file_exists(self::$Data) && !is_dir(self::$Data)):
            $patch = BASE;
            $imagem = self::$Data;
            return "<img src=\"{$patch}/tim.php?src={$patch}/{$imagem}&w={$ImageW}&h={$ImageH}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\"/>";
        else:
            return false;
        endif;
    }

    /**
     * PEGA NOME DO AGENT DE 
     * @return STRING Agent Name
     */
    public static function Agent() {
        self::$Data = $_SESSION['useronline']['online_agent'];
        if (empty(self::$Data)):
            return null;
        else:
            if (strpos(self::$Data, 'Chrome')):
                return 'Chrome';
            elseif (strpos(self::$Data, 'Firefox')):
                return 'Firefox';
            elseif (strpos(self::$Data, 'MSIE') || strpos(self::$Data, 'Trident/')):
                return 'IE';
            else:
                return 'Outros';
            endif;
        endif;
    }

    public static function NewPass($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';

        $caracteres .= $lmin;
        if ($maiusculas):
            $caracteres .= $lmai;
        endif;
        if ($numeros):
            $caracteres .= $num;
        endif;
        if ($simbolos):
            $caracteres .= $simb;
        endif;

        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }
        return $retorno;
    }
}
