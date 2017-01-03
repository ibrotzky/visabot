<?php

/**
 * Sitemap.class [ HELPER ]
 * Classe responável por gerar Sitemaps e RSS feeds para o site e o sistema!
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Sitemap {

    //SITEMAP
    private $Sitemap;
    private $SitemapXml;
    private $SitemapGz;
    private $SitemapPing;
    //RSS
    private $RSS;
    private $RSSXml;

    public function exeSitemap($Ping = true) {
        $this->SitemapUpdate();
        if ($Ping != false):
            $this->SitemapPing();
        endif;
    }

    private function SitemapUpdate() {
        $Read = new Read;

        $this->Sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n";
        $this->Sitemap .= '<?xml-stylesheet type="text/xsl" href="sitemap.xsl"?>' . "\r\n";
        $this->Sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\r\n";

        //HOME
        $this->Sitemap .= '<url>' . "\r\n";
        $this->Sitemap .= '<loc>' . BASE . '</loc>' . "\r\n";
        $this->Sitemap .= '<lastmod>' . date('Y-m-d\TH:i:sP') . '</lastmod>' . "\r\n";
        $this->Sitemap .= '<changefreq>daily</changefreq>' . "\r\n";
        $this->Sitemap .= '<priority>1.0</priority >' . "\r\n";
        $this->Sitemap .= '</url>' . "\r\n";

        if (APP_PAGES):
            //PAGES        
            $Read->FullRead("SELECT page_name, page_date FROM " . DB_PAGES . " WHERE page_status = 1 ORDER BY page_title ASC");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $ReadPages):
                    $this->Sitemap .= '<url>' . "\r\n";
                    $this->Sitemap .= '<loc>' . BASE . '/' . $ReadPages['page_name'] . '</loc>' . "\r\n";
                    $this->Sitemap .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($ReadPages['page_date'])) . '</lastmod>' . "\r\n";
                    $this->Sitemap .= '<changefreq>monthly</changefreq>' . "\r\n";
                    $this->Sitemap .= '<priority>0.5</priority >' . "\r\n";
                    $this->Sitemap .= '</url>' . "\r\n";
                endforeach;
            endif;
        endif;

        if (APP_POSTS):
            //CATEGORIES        
            $Read->FullRead("SELECT category_date, category_name FROM " . DB_CATEGORIES . " ORDER BY category_title ASC");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $ReadPages):
                    $this->Sitemap .= '<url>' . "\r\n";
                    $this->Sitemap .= '<loc>' . BASE . '/artigos/' . $ReadPages['category_name'] . '</loc>' . "\r\n";
                    $this->Sitemap .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($ReadPages['category_date'])) . '</lastmod>' . "\r\n";
                    $this->Sitemap .= '<changefreq>monthly</changefreq>' . "\r\n";
                    $this->Sitemap .= '<priority>0.7</priority >' . "\r\n";
                    $this->Sitemap .= '</url>' . "\r\n";
                endforeach;
            endif;

            //POSTS        
            $Read->FullRead("SELECT post_name, post_date FROM " . DB_POSTS . " WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_date DESC");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $ReadPages):
                    $this->Sitemap .= '<url>' . "\r\n";
                    $this->Sitemap .= '<loc>' . BASE . '/artigo/' . $ReadPages['post_name'] . '</loc>' . "\r\n";
                    $this->Sitemap .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($ReadPages['post_date'])) . '</lastmod>' . "\r\n";
                    $this->Sitemap .= '<changefreq>weekly</changefreq>' . "\r\n";
                    $this->Sitemap .= '<priority>0.9</priority >' . "\r\n";
                    $this->Sitemap .= '</url>' . "\r\n";
                endforeach;
            endif;
        endif;

        if (APP_PRODUCTS):
            //PRODUCTS CATEGORIES        
            $Read->FullRead("SELECT cat_name, cat_created FROM " . DB_PDT_CATS . " ORDER BY cat_title ASC");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $ReadPages):
                    $this->Sitemap .= '<url>' . "\r\n";
                    $this->Sitemap .= '<loc>' . BASE . '/produtos/' . $ReadPages['cat_name'] . '</loc>' . "\r\n";
                    $this->Sitemap .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($ReadPages['cat_created'])) . '</lastmod>' . "\r\n";
                    $this->Sitemap .= '<changefreq>weekly</changefreq>' . "\r\n";
                    $this->Sitemap .= '<priority>0.9</priority >' . "\r\n";
                    $this->Sitemap .= '</url>' . "\r\n";
                endforeach;
            endif;

            //PRODUTCTS        
            $Read->FullRead("SELECT pdt_name, pdt_created FROM " . DB_PDT . " ORDER BY pdt_created DESC");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $ReadPages):
                    $this->Sitemap .= '<url>' . "\r\n";
                    $this->Sitemap .= '<loc>' . BASE . '/produto/' . $ReadPages['pdt_name'] . '</loc>' . "\r\n";
                    $this->Sitemap .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($ReadPages['pdt_created'])) . '</lastmod>' . "\r\n";
                    $this->Sitemap .= '<changefreq>weekly</changefreq>' . "\r\n";
                    $this->Sitemap .= '<priority>0.9</priority >' . "\r\n";
                    $this->Sitemap .= '</url>' . "\r\n";
                endforeach;
            endif;

            //PRODUCTS BRANDS        
            $Read->FullRead("SELECT brand_name, brand_created FROM " . DB_PDT_BRANDS . " ORDER BY brand_title ASC");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $ReadPages):
                    $this->Sitemap .= '<url>' . "\r\n";
                    $this->Sitemap .= '<loc>' . BASE . '/marca/' . $ReadPages['brand_name'] . '</loc>' . "\r\n";
                    $this->Sitemap .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($ReadPages['brand_created'])) . '</lastmod>' . "\r\n";
                    $this->Sitemap .= '<changefreq>weekly</changefreq>' . "\r\n";
                    $this->Sitemap .= '<priority>0.9</priority >' . "\r\n";
                    $this->Sitemap .= '</url>' . "\r\n";
                endforeach;
            endif;
        endif;

        //CLOSE
        $this->Sitemap .= '</urlset>';

        //CRIA O XML
        $this->SitemapXml = fopen("../sitemap.xml", "w+");
        fwrite($this->SitemapXml, $this->Sitemap);
        fclose($this->SitemapXml);

        //CRIA O GZ
        $this->SitemapGz = gzopen("../sitemap.xml.gz", 'w9');
        gzwrite($this->SitemapGz, $this->Sitemap);
        gzclose($this->SitemapGz);
    }

    private function SitemapPing() {
        $this->SitemapPing = array();
        $this->SitemapPing['g'] = 'https://www.google.com/webmasters/tools/ping?sitemap=' . urlencode(BASE . '/sitemap.xml');
        $this->SitemapPing['b'] = 'https://www.bing.com/webmaster/ping.aspx?siteMap=' . urlencode(BASE . '/sitemap.xml');

        foreach ($this->SitemapPing as $url):
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
        endforeach;
    }

}
