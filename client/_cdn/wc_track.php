<?php
if (!empty($_SESSION['userLogin'])):
    $wcFbUserSection = array_map("mb_strtolower", $_SESSION['userLogin']);
    $wcFbUserSection['user_lastname'] = substr($wcFbUserSection['user_lastname'], strrpos($wcFbUserSection['user_lastname'], " "));
    $wcFbUserSection['user_phone'] = (!empty($wcFbUserSection['user_cell']) ? $wcFbUserSection['user_cell'] : (!empty($wcFbUserSection['user_telephone']) ? $wcFbUserSection['user_telephone'] : null));

    if (!empty($wcFbUserSection['user_phone'])):
        $wcFbUserSection['user_phone'] = str_replace(['(', ')', ' ', '-', '.'], "", $wcFbUserSection['user_phone']);
    endif;

    $wcFbUserSection['user_datebirth'] = date("Ymd", strtotime($wcFbUserSection['user_datebirth']));

    $Read = new Read;
    $Read->FullRead("SELECT addr_city, addr_state, addr_zipcode FROM " . DB_USERS_ADDR . " WHERE user_id = :id", "id={$wcFbUserSection['user_id']}");
    if ($Read->getResult()):
        $wcFbUserAddr = array_map("mb_strtolower", $Read->getResult()[0]);
        $wcFbUserAddr['addr_zipcode'] = str_replace(['.', '-'], '', $wcFbUserAddr['addr_zipcode']);
    endif;
endif;
?>
<script>
    $(function () {
        FB_PIXEL = '<?= !empty(SEGMENT_FB_PIXEL_ID) ? SEGMENT_FB_PIXEL_ID : '"null"'; ?>';
        WC_USER = <?= (!empty($wcFbUserSection) ? json_encode($wcFbUserSection) : '"null"'); ?>;
        WC_ADDR = <?= (!empty($wcFbUserAddr) ? json_encode($wcFbUserAddr) : '"null"'); ?>;
        WC_LINK = window.location.href;

        //FACEBOOK PIXEL
        !function (f, b, e, v, n, t, s) {
            if (f.fbq)
                return;
            n = f.fbq = function () {
                n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq)
                f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
                document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        //WC TRACK 
        WC_SEGMENT_USER = <?= (!empty(SEGMENT_WC_USER) ? 1 : 0); ?>;
        if (WC_USER === 'null' || !WC_SEGMENT_USER) {
            fbq('init', FB_PIXEL);
        } else {
            fbq('init', FB_PIXEL, {
                em: WC_USER.user_email, //Email
                fn: WC_USER.user_name, //Nome
                ln: WC_USER.user_lastname, //Sobrenome
                ph: WC_USER.user_phone, //Telefone
                ge: WC_USER.user_genre, //Genero
                db: WC_USER.user_datebirth, //Data de nascimento YYYYMMDD
                ct: WC_ADDR.addr_city, //Cidade
                st: WC_ADDR.addr_state, //Estado
                zp: WC_ADDR.addr_zipcode //Cep
            });
        }

        //WC EVENTS
        fbq('track', 'PageView');

        <?php if(APP_POSTS == 1): ?>
        //SITE :: ARTIGO
        WC_SEGMENT_BLOG = <?= (!empty(SEGMENT_WC_BLOG) ? 1 : 0); ?>;
        if (WC_LINK.match('artigo/') && WC_SEGMENT_BLOG) {
            fbq('track', 'ViewContent', {
                content_name: '<?= (!empty($post_title) ? $post_title : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'blog_post',
                wc_post_id: '<?= (!empty($post_id) ? $post_id : null); ?>',
                wc_post_title: '<?= (!empty($post_title) ? $post_title : null); ?>',
                wc_post_category: '<?= (!empty($Category['category_title']) ? $Category['category_title'] : 'null'); ?>'
            });
        }

        //SITE :: ARTIGOS
        if (WC_LINK.match('artigos/') && WC_SEGMENT_BLOG) {
            fbq('track', 'ViewContent', {
                content_name: '<?= (!empty($category_title) ? $category_title : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'blog_category',
                wc_category_id: '<?= (!empty($category_id) ? $category_id : null); ?>',
                wc_category_title: '<?= (!empty($category_title) ? $category_title : null); ?>'
            });
        }
        <?php endif; ?>

        //SITE :: PESQUISA
        if (WC_LINK.match('pesquisa/') && WC_SEGMENT_BLOG) {
            fbq('track', 'Search', {
                search_string: '<?= (!empty($URL[1]) ? $URL[1] : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'blog_search',
                wc_search: '<?= (!empty($URL[1]) ? $URL[1] : null); ?>'
            });
        }

        //SITE :: CADASTRO
        if (WC_LINK.match('conta/home') && document.referrer.match('conta/cadastro') && WC_SEGMENT_BLOG) {
            fbq('track', 'CompleteRegistration', {
                content_name: 'cadasrtou-se no site',
                status: 'active',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'acc_register'
            });
        }

        <?php if(APP_PRODUCTS == 1): ?>
        //E-COMMERCE :: PRODUTO
        WC_SEGMENT_ECOMMERCE = <?= (!empty(SEGMENT_WC_ECOMMERCE) ? 1 : 0); ?>;
        if (WC_LINK.match('produto/') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'ViewContent', {
                content_name: '<?= (!empty($pdt_title) ? $pdt_title : null); ?>',
                content_ids: '<?= (!empty($pdt_id) ? "product_{$pdt_id}" : null); ?>',
                content_type: 'product',
                content_category: '<?= (!empty($Category['cat_title']) ? $Category['cat_title'] : 'null'); ?>',
                value: '<?= (!empty($pdt_price) ? $pdt_price : null); ?>',
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_product',
                wc_pdt_id: '<?= (!empty($pdt_id) ? $pdt_id : null); ?>',
                wc_pdt_code: '<?= (!empty($pdt_code) ? $pdt_code : null); ?>',
                wc_pdt_title: '<?= (!empty($pdt_title) ? $pdt_title : null); ?>',
                wc_pdt_price: '<?= (!empty($pdt_price) ? $pdt_price : null); ?>',
                wc_pdt_category: '<?= (!empty($Category['cat_title']) ? $Category['cat_title'] : 'null'); ?>'
            });
        }

        //E-COMMERCE :: PRODUTOS
        if (WC_LINK.match('produtos/') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'ViewContent', {
                content_name: '<?= (!empty($cat_title) ? $cat_title : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_products_categories',
                wc_cat_id: '<?= (!empty($cat_id) ? $cat_id : null); ?>',
                wc_cat_title: '<?= (!empty($cat_title) ? $cat_title : null); ?>'
            });
        }
        
        //E-COMMERCE :: MARCA
        if (WC_LINK.match('marca/') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'ViewContent', {
                content_name: '<?= (!empty($brand_title) ? $brand_title : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_products_brands',
                wc_brand_id: '<?= (!empty($brand_id) ? $brand_id : null); ?>',
                wc_brand_title: '<?= (!empty($brand_title) ? $brand_title : null); ?>'
            });
        }

        //E-COMMERCE :: CARRINHO
        var CartValue = $('.wc_cart_price span').text().replace('.', '').replace(',', '.');
        if (WC_LINK.match('pedido/home') && CartValue && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'AddToCart', {
                content_ids: [<?= (!empty($wcCartIds) ? "'product_" . implode("', 'product_", $wcCartIds) . "'" : null); ?>],
                content_type: 'product',
                value: CartValue,
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart'
            });
        }
        <?php endif; ?>

        <?php if(APP_ORDERS == 1): ?>
        //E-COMMERCE :: CHECKOUT
        if (WC_LINK.match('pedido/endereco') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'InitiateCheckout', {
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart_addr'
            });
        }

        //E-COMMERCE :: PAGAMENTO
        if (WC_LINK.match('pedido/pagamento') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'AddPaymentInfo', {
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart_payment'
            });
        }

        //E-COMMERCE :: COMPRA CONCLUÍDA
        if (WC_LINK.match('pedido/obrigado') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'Purchase', {
                content_ids: [<?= (!empty($wcCartIds) ? "'product_" . implode("', 'product_", $wcCartIds) . "'" : null); ?>],
                content_type: 'product',
                value: CartValue,
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart_purchase'
            });
        }
        <?php endif; ?>

        <?php if(APP_IMOBI == 1): ?>
        //IMOBI :: IMÓVEL
        WC_SEGMENT_IMOBI = <?= (!empty(SEGMENT_WC_IMOBI) ? 1 : 0); ?>;
        if (WC_LINK.match('imovel/') && WC_SEGMENT_IMOBI) {
            fbq('track', 'ViewContent', {
                content_name: '<?= (!empty($realty_title) ? $realty_title : null); ?>',
                content_category: '<?= (!empty($realty_type) ? getWcRealtyType($realty_type) : 'null'); ?>',
                content_transaction: '<?= (!empty($realty_transaction) ? getWcRealtyTransaction($realty_transaction) : 'null'); ?>',
                content_finality: '<?= (!empty($realty_finality) ? getWcRealtyFinality($realty_finality) : 'null'); ?>',
                content_ids: '<?= (!empty($realty_id) ? "realty_{$realty_id}" : null); ?>',
                content_type: 'product',
                value: '<?= (!empty($realty_price) ? $realty_price : null); ?>',
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'imobi_realty',
                wc_realty_id: '<?= (!empty($realty_id) ? $realty_id : null); ?>',
                wc_realty_ref: '<?= (!empty($realty_ref) ? $realty_ref : null); ?>',
                wc_realty_title: '<?= (!empty($realty_title) ? $realty_title : null); ?>',
                wc_realty_price: '<?= (!empty($realty_price) ? $realty_price : null); ?>',
                wc_realty_finality: '<?= (!empty($realty_finality) ? getWcRealtyFinality($realty_finality) : null); ?>',
                wc_realty_type: '<?= (!empty($realty_type) ? getWcRealtyType($realty_type) : null); ?>',
                wc_realty_transaction: '<?= (!empty($realty_transaction) ? getWcRealtyTransaction($realty_transaction) : null); ?>'
            });
        }

        //IMOBI :: IMÓVEIS
        if (WC_LINK.match('imoveis/') && WC_SEGMENT_IMOBI) {
            fbq('track', 'ViewContent', {
                content_name: '<?= (!empty($URL[1]) ? "{$URL[1]}" : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'imobi_realtys',
                wc_realty_type: '<?= (!empty($URL[2]) ? "{$URL[2]}" : null); ?>',
                wc_realty_transaction: '<?= (!empty($URL[1]) ? "{$URL[1]}" : null); ?>'
            });
        }
        <?php endif; ?>

        <?php if(APP_EAD == 1): ?>
        //EAD :: CURSO
        WC_SEGMENT_EAD = <?= (!empty(SEGMENT_WC_EAD) ? 1 : 0); ?>;
        if (WC_LINK.match('campus/curso/') && WC_SEGMENT_EAD) {
            fbq('track', 'ViewContent', {
                content_name: 'Curso <?= (!empty($course_title) ? $course_title : null); ?>',
                content_category: '<?= (!empty($CourseSegment['segment_title']) ? $CourseSegment['segment_title'] : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_course',
                wc_course_title: '<?= (!empty($course_title) ? $course_title : null); ?>',
                wc_course_segment: '<?= (!empty($CourseSegment['segment_title']) ? $CourseSegment['segment_title'] : null); ?>'
            });
        }

        //EAD :: TAREFA
        if (WC_LINK.match('campus/tarefa') && WC_SEGMENT_EAD) {
            fbq('track', 'ViewContent', {
                content_name: 'Aula <?= (!empty($class_title) ? $class_title : null); ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_class',
                wc_class_title: '<?= (!empty($class_title) ? $class_title : null); ?>',
                wc_class_time: '<?= (!empty($class_time) ? $class_time : null); ?>'
            });
        }

        //EAD :: LEAD
        if (WC_LINK.match('campus/ativar') && WC_SEGMENT_EAD) {
            fbq('track', 'Lead', {
                content_name: 'Cadastrou-se no EAD',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_register'
            });
        }

        //EAD :: CADASTRO
        if (WC_LINK.match('campus') && document.referrer.match('campus/ativar') && WC_SEGMENT_EAD) {
            fbq('track', 'CompleteRegistration', {
                content_name: 'Completou cadastro no EAD',
                status: 'active',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_complete_registration'
            });
        }
        <?php endif; ?>
    });
</script>
