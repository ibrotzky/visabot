<?php

/**
 * Session.class [ HELPER ]
 * Responsável pelas estatísticas, sessões e atualizações de tráfego do sistema!
 * 
 * @copyright (c) 2016, Robson V. Leite UPINSIDE TECNOLOGIA
 * @author Robson V. Leite <robson@upinside.com.br>
 * @collaboration Whallysson Avelino <whallyssonallain@gmail.com>
 */
class Session {

    private $Cache;
    private $Session;
    private $Agent;
    private $Bots;
    private $Url;

    public function __construct($Cache = null) {
        $this->Cache = ($Cache ? $Cache : 20);
        //USER SESSION START
        if ($this->isValidUser()):
            $this->setSession();
        endif;

        //REMOVE EXPIRED SESSIONS
        $this->sessionClear();
    }

    //Controla a classe para iniciar a sessão ou atualizar, gerencia o tráfego do site!
    private function setSession() {
        $this->viewsStart();

        if (empty($_SESSION['userOnline']) || is_array($_SESSION['userOnline'])):
            $this->sessionStart();
        else:
            $this->sessionUpdate();
        endif;
    }

    //Inicia a sessão do usuário quando ela não existir!
    private function sessionStart() {
        $this->Session = array();
        $this->Session['online_startview'] = date('Y-m-d H:i:s');
        $this->Session['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
        $this->Session['online_ip'] = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $this->Session['online_url'] = trim(strip_tags(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
        $this->Session['online_agent'] = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_DEFAULT);

        if (!empty($_SESSION['userLogin'])):
            $this->Session['online_user'] = (!empty($_SESSION['userLogin']['user_id']) ? $_SESSION['userLogin']['user_id'] : null);
            $this->Session['online_name'] = (!empty($_SESSION['userLogin']['user_name']) && !empty($_SESSION['userLogin']['user_lastname']) ? "{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}" : null);
        endif;

        $Create = new Create;
        $Create->ExeCreate(DB_VIEWS_ONLINE, $this->Session);
        $_SESSION['userOnline'] = $Create->getResult();
    }

    //Atualiza a sessão do usuário de acordo com sua navegação!Ï
    private function sessionUpdate() {

        $Read = new Read;
        $Read->ExeRead(DB_VIEWS_ONLINE, "WHERE online_id = :ses", "ses={$_SESSION['userOnline']}");
        if (!$Read->getResult()):
            $this->sessionStart();
        else:
            $this->Session = $Read->getResult()[0];
            $this->Session['online_url'] = trim(strip_tags(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
            $this->Session['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));

            if (!empty($_SESSION['userLogin'])):
                $this->Session['online_user'] = (!empty($_SESSION['userLogin']['user_id']) ? $_SESSION['userLogin']['user_id'] : null);
                $this->Session['online_name'] = (!empty($_SESSION['userLogin']['user_name']) && !empty($_SESSION['userLogin']['user_lastname']) ? "{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}" : null);
            else:
                $this->Session['online_user'] = null;
                $this->Session['online_name'] = null;
            endif;

            $Update = new Update;
            $Update->ExeUpdate(DB_VIEWS_ONLINE, $this->Session, "WHERE online_id = :id", "id={$_SESSION['userOnline']}");
        endif;
    }

    //Limpa sessões expiradas
    private function sessionClear() {
        $Delete = new Delete;
        $Delete->ExeDelete(DB_VIEWS_ONLINE, "WHERE (online_endview < NOW() OR online_startview IS NULL) AND online_id >= :id", "id=1");
    }

    /*
     * CONTROLA O TRÁFEGO DO SITE
     * Ao primeiro acesso do dia, armazena os dados de tráfego.
     * Atualiza o views_pages a cada load de página
     * Atualiza o views_views a cada nova sessão do site
     * Atualiza o views_users a cada visita única de um dispositivo
     */

    private function viewsStart() {
        $Read = new Read;
        $Read->ExeRead(DB_VIEWS_VIEWS, "WHERE views_date = date(NOW())");
        if ($Read->getResult()):
            $UserCookie = filter_input(INPUT_COOKIE, 'userView');
            $View = $Read->getResult()[0];

            $UpdateView = array();
            $UpdateView['views_pages'] = $View['views_pages'] + 1;
            $UpdateView['views_views'] = (empty($_SESSION['userOnline']) ? $View['views_views'] + 1 : $View['views_views']);
            $UpdateView['views_users'] = (empty($UserCookie) ? $View['views_users'] + 1 : $View['views_users']);

            $Update = new Update;
            $Update->ExeUpdate(DB_VIEWS_VIEWS, $UpdateView, "WHERE views_date = date(NOW()) AND views_id >= :id", "id=1");

            //24 HORS TO NEW USER
            setcookie('userView', Check::Name(SITE_NAME), time() + 86400, '/');
        else:
            $CreateView = ['views_date' => date('Y-m-d'), 'views_users' => 1, 'views_views' => 1, 'views_pages' => 1];
            $Create = new Create;
            $Create->ExeCreate(DB_VIEWS_VIEWS, $CreateView);
        endif;
    }

    //Identifica usuário ou bot
    private function isValidUser() {
        $this->Url = trim(strip_tags(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
        $Array = array('favicon', '.png', '.jpg', '.ico', '.gif', '.css', '.map');
        foreach ($Array as $Sai):
            if (stripos($this->Url, $Sai)):
                return false;
            endif;
        endforeach;

        $this->Agent = (string) mb_strtolower($_SERVER['HTTP_USER_AGENT']);
        $this->Bots = array('008/', 'accoona', 'aghaven', 'altavista', 'arachmo', 'aspseek', 'b-l-i-t-z-b-o-t', 'backtype', 'baiduspider', 'boitho.com-dc', 'bot', 'cerberian drtrs', 'charlotte', 'converacrawler', 'cosmos', 'covario', 'crawler', 'croccrawler', 'dataparksearch', 'embed.ly', 'envolk[its]spider', 'estyle', 'facebookexternalhit', 'fairshare', 'fast enterprise crawler', 'fast-webcrawler', 'favicon', 'fdse', 'findlinks', 'fyberspider', 'g2crawler', 'gnip', 'google', 'hl_ftien_spider', 'holmes', 'htdig', 'ia_archiver', 'iaskspider', 'iccrawler', 'ichiro', 'igdespyder', 'issuecrawler', 'jaxified', 'l.webis', 'larbin', 'ldspider', 'linguee', 'linkwalker', 'lmspider', 'lwp-trivial', 'lycos', 'mabontland', 'magpie-crawler', 'mediapartners-google', 'megite', 'metauri', 'mnogosearch', 'mogimogi', 'morning paper', 'mvaclient', 'netresearchserver', 'netseer crawler', 'netvibes', 'newsgator', 'ng-search', 'nusearch spider', 'nutchcvs', 'nymesis', 'oegp', 'orbiter', 'owlin', 'peew', 'pompos', 'postpost', 'postrank', 'pycurl', 'qseero', 'radian6', 'rambler', 'sandcrawler', 'sbider', 'scooter', 'scoutjet', 'scrubby', 'searchsight', 'semanticdiscovery', 'sensis web crawler', 'seochat', 'shim-crawler', 'shopwiki', 'shoula', 'silk', 'sitesell', 'skygrid', 'snappy', 'sogou spider', 'sosospider', 'soup', 'speedy spider', 'spider', 'sqworm', 'ssppiiddeerr', 'stackrambler', 'summify', 'teoma', 'thumbnail.cz', 'tineye', 'topix', 'truwogps', 'tumblr', 'tweetbeagle', 'tweetedtimes', 'twitturls', 'unwindfetchor', 'updated', 'urlchecker', 'vagabondo', 'vortex', 'voyager', 'vyu2', 'webcollage', 'websquash.com', 'wf84', 'wofindeich', 'womlpefactory', 'xaldon_webspider', 'yacy', 'yahoo', 'yahooseeker', 'yandeximages', 'yeti', 'yooglifetchagent', 'zao', 'zemanta', 'zspider', 'zyborg');

        $IsBot = false;
        foreach ($this->Bots as $Bot):
            if (stripos($this->Agent, $Bot) !== false):
                $IsBot = true;
            endif;
        endforeach;

        if ($IsBot):
            return false;
        else:
            return true;
        endif;
    }
}
