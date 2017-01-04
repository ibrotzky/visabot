<?php

if (!$WorkControlDefineConf):
    /*
     * URL DO SISTEMA
     */
    define('BASE', 'https://localhost/visabot'); //Url raiz do site
    define('THEME', 'visabot'); //template do site
endif;

//DINAMYC THEME
if (!empty($_SESSION['WC_THEME'])):
    define('THEME', $_SESSION['WC_THEME']); //template do site
endif;

/*
 * PATCH CONFIG
 */
define('INCLUDE_PATH', BASE . '/themes/' . THEME); //Geral de inclusão (Não alterar)
define('REQUIRE_PATH', 'themes/' . THEME); //Geral de inclusão (Não alterar)

if (!$WorkControlDefineConf):
    /*
     * ADMIN CONFIG
     */
    define('ADMIN_NAME', 'Work Control');  //Nome do painel de controle (Work Control)
    define('ADMIN_DESC', 'O Work Control é um sistema de gestão de conteúdo profissional gerido pela turma de alunos Work Series da UpInside Treinamentos!'); //Descrição do painel de controle (Work Control)
    define('ADMIN_MODE', 1); //1 = website / 2 = e-commerce / 3 = Imobi / 4 = EAD
    define('ADMIN_WC_CUSTOM', 1); //Habilita menu e telas customizadas
    define('ADMIN_MAINTENANCE', 0); //Manutenção
    define('ADMIN_VERSION', '3.0.0');

    /*
     * E-MAIL SERVER
     * Consulte estes dados com o serviço de hospedagem
     */
    define('MAIL_HOST', ''); //Servidor de e-mail
    define('MAIL_PORT', ''); //Porta de envio
    define('MAIL_USER', ''); //E-mail de envio
    define('MAIL_PASS', ''); //Senha do e-mail de envio
    define('MAIL_SENDER', ''); //Nome do remetente de e-mail
    define('MAIL_TESTER', ''); //E-mail de testes (DEV)

    /*
     * MEDIA CONFIG
     */
    define('IMAGE_W', 1600); //Tamanho da imagem (WIDTH)
    define('IMAGE_H', 800); //Tamanho da imagem (HEIGHT)
    define('THUMB_W', 800); //Tamanho da miniatura (WIDTH) PDTS
    define('THUMB_H', 1000); //Tamanho da minuatura (HEIGHT) PDTS
    define('AVATAR_W', 500); //Tamanho da miniatura (WIDTH) USERS
    define('AVATAR_H', 500); //Tamanho da minuatura (HEIGHT) USERS
    define('SLIDE_W', 1920); //Tamanho da miniatura (WIDTH) SLIDE
    define('SLIDE_H', 600); //Tamanho da minuatura (HEIGHT) SLIDE

    /*
     * APP CONFIG
     * Habilitar ou desabilitar modos do sistema
     */
    define('APP_POSTS', 1); //Posts
    define('APP_EAD', 1); //Plataforma EAD
    define('APP_SEARCH', 1); //Relatório de Pesquisas
    define('APP_PAGES', 1); //Páginas
    define('APP_COMMENTS', 1); //Comentários
    define('APP_PRODUCTS', 1); //Produtos
    define('APP_ORDERS', 1); //Pedidos
    define('APP_IMOBI', 1); //Imóveis
    define('APP_SLIDE', 1); //Slide Em Destaque
    define('APP_USERS', 1); //Usuários

    /*
     * LEVEL CONFIG
     * Configura permissões do painel de controle!
     */
    define('LEVEL_WC_POSTS', 6);
    define('LEVEL_WC_COMMENTS', 6);
    define('LEVEL_WC_PAGES', 6);
    define('LEVEL_WC_SLIDES', 6);
    define('LEVEL_WC_IMOBI', 6);
    define('LEVEL_WC_PRODUCTS', 6);
    define('LEVEL_WC_PRODUCTS_ORDERS', 6);
    define('LEVEL_WC_EAD_COURSES', 6);
    define('LEVEL_WC_EAD_STUDENTS', 6);
    define('LEVEL_WC_EAD_SUPPORT', 6);
    define('LEVEL_WC_EAD_ORDERS', 6);
    define('LEVEL_WC_REPORTS', 6);
    define('LEVEL_WC_USERS', 6);
    define('LEVEL_WC_CONFIG_MASTER', 10);
    define('LEVEL_WC_CONFIG_API', 10);
    define('LEVEL_WC_CONFIG_CODES', 10);

    /*
     * FB SEGMENT
     * Configura ultra segmentação de público no facebook
     * !!!! IMPORTANTE :: Para utilizar ultra segmentação de produtos e imóveis
     * é precisso antes configurar os catálogos de produtos respectivamente!
     */
    define('SEGMENT_FB_PIXEL_ID', 0); //Id do pixel de rastreamento
    define('SEGMENT_WC_USER', 0); //Enviar dados do login de usuário?
    define('SEGMENT_WC_BLOG', 0); //Ultra segmentar páginas do BLOG?
    define('SEGMENT_WC_ECOMMERCE', 0); //Ultra segmentar páginas do E-COMMERCE?
    define('SEGMENT_WC_IMOBI', 0); //Ultra segmentar páginas do imobi?
    define('SEGMENT_WC_EAD', 0); //Ultra segmentar páginas do EAD?

    /*
     * APP LINKS
     * Habilitar ou desabilitar campos de links alternativos
     */
    define('APP_LINK_POSTS', 0); //Posts
    define('APP_LINK_PAGES', 0); //Páginas
    define('APP_LINK_PRODUCTS', 0); //Produtos
    define('APP_LINK_PROPERTIES', 0); //Imóveis

    /*
     * ACCOUNT CONFIG
     */
    define('ACC_MANAGER', 1); //Conta de usuários (UI)
    define('ACC_TAG', 'Minha Conta!'); //null para OLÁ {NAME} ou texto (Minha Conta, Meu Cadastro, etc)

    /*
     * COMMENT CONFIG
     */
    define('COMMENT_MODERATE', 0); //Todos os NOVOS comentários ficam ocultos até serem aprovados
    define('COMMENT_ON_POSTS', 1); //Aplica comentários aos posts
    define('COMMENT_ON_PAGES', 0); //Aplica comentários as páginas
    define('COMMENT_ON_PRODUCTS', 1); //Aplica comentários aos produtos
    define('COMMENT_SEND_EMAIL', 1); //Envia e-mails transicionais para usuários sobre comentários
    define('COMMENT_ORDER', 'DESC'); //Ordem de exibição dos comentários (ASC ou DESC)
    define('COMMENT_RESPONSE_ORDER', 'ASC'); //Ordem de exibição das respostas (ASC ou DESC)

    /*
     * ECOMMERCE CONFIG
     * IMPORTANTE EM E_ORDER_PAYDATE: Um tempo muito grande para pagamento pode implicar
     * em extender descontos expirados. Uma oferta pode acabar e o usuário ainda consegue
     * pagar neste prazo de dias!
     */
    define('E_PDT_LIMIT', 0); //Limite de produtos cadastrados. NULL = sem limite
    define('E_PDT_SIZE', 'default'); //Tamanho padrão para produtos!
    define('E_ORDER_DAYS', 1); //Dias para cancelar pedidos não pagos (Novo Pedido)
    define('ECOMMERCE_TAG', 'Minhas Compras'); //Meu Carrinho, Minha Cesta, Minhas Compras, Etc;
    define('ECOMMERCE_STOCK', 1); //true para controlar o estoque e false para não! (Ainda será nessesário alimentar o estoque para o carrinho)
    define('ECOMMERCE_BUTTON_TAG', 'Comprar Agora'); //Meu Carrinho, Minha Cesta, Minhas Compras, Etc;
    /*
     * Parcelamento
     */
    define('ECOMMERCE_PAY_SPLIT', 1); //Aceita pagamento parcelado?
    define('ECOMMERCE_PAY_SPLIT_MIN', 5); //Qual valor mínimo da parcela? (consultar método de pagamento)
    define('ECOMMERCE_PAY_SPLIT_NUM', 12); //Qual o número máximo de parcelas? (consultar método de pagamento)
    define('ECOMMERCE_PAY_SPLIT_ACM', 2.99); //Juros aplicados ao mês! (consultar método de pagamento)
    define('ECOMMERCE_PAY_SPLIT_ACN', 1); //Parcelas sem Juros (consultar método de pagamento)

    /*
     * SHIPMENT CONFIG
     * 1. Frete gratuito a partir do valor X
     */
    define('ECOMMERCE_SHIPMENT_FREE', 0); //Opção de frete grátis a partir do valor X (Informe o valor ou false)
    define('ECOMMERCE_SHIPMENT_FREE_DAYS', 20); //Máximo de dias úteis para a entrega no frete gratuito!
    /*
     * Valor de frete fixo!
     */
    define('ECOMMERCE_SHIPMENT_FIXED', 0); //Oferecer frete com valor fixo?
    define('ECOMMERCE_SHIPMENT_FIXED_PRICE', 15.00); //Valor do frete fixo
    define('ECOMMERCE_SHIPMENT_FIXED_DAYS', 15); //Máximo de dias úteis para a entrega! 
    /*
     * Frete fixo por localidade!
     */
    define('ECOMMERCE_SHIPMENT_LOCAL', 0); //Entrega padrão para a Cidade (Ex: São Paulo, Florianópolis, false)
    define('ECOMMERCE_SHIPMENT_LOCAL_IN_PLACE', 1); //Permitir retirar na Loja?
    define('ECOMMERCE_SHIPMENT_LOCAL_PRICE', 5.00); //Taxa de entrega local! 
    define('ECOMMERCE_SHIPMENT_LOCAL_DAYS', 1); //Máximo de dias úteis para a entrega!
    /*
     * Frete por correios!
     */
    define('ECOMMERCE_SHIPMENT_CDEMPRESA', 0); //Usuário da empresa se tiver contrato com correios!
    define('ECOMMERCE_SHIPMENT_CDSENHA', 0); //Senha da empresa se tiver contrato com correios!
    define('ECOMMERCE_SHIPMENT_SERVICE', '40010,41106'); //Tipos de serviços a serem consultados! (Consultar em Config.inc.php Função getShipmentTag())
    define('ECOMMERCE_SHIPMENT_DELAY', 3); //Soma X dias ao prazo máximo de entrega dos correios!
    define('ECOMMERCE_SHIPMENT_FORMAT', 1); //1 Caixa/Pacote, 2 Rolo/Bobina ou 3 Envelope?
    define('ECOMMERCE_SHIPMENT_DECLARE', 1); //Declarar valor da compra para seguro?
    define('ECOMMERCE_SHIPMENT_OWN_HAND', 's'); //Postagem por mão própria? (s, n)
    define('ECOMMERCE_SHIPMENT_BY_WEIGHT', 1); //Cálculo deduzido apenas por peso?
    define('ECOMMERCE_SHIPMENT_ALERT', 0); //Aviso de recebimento?
    /*
     * Frete por transportadora
     */
    define('ECOMMERCE_SHIPMENT_COMPANY', 0); //Oferecer Transportadora?
    define('ECOMMERCE_SHIPMENT_COMPANY_VAL', 5); //Valor do frete por porcentagem do valor do pedido! (4% do valor do pedido)
    define('ECOMMERCE_SHIPMENT_COMPANY_PRICE', 30); //Valor mínimo para envio via transportadora. 100 = R$ 100
    define('ECOMMERCE_SHIPMENT_COMPANY_DAYS', 15); //Máximo de dias úteis para a entrega!
    define('ECOMMERCE_SHIPMENT_COMPANY_LINK', 'http://www.dhl.com.br/pt/express/rastreamento.html?AWB='); //Link para rastreamento (EX: http://www.dhl.com.br/pt/express/rastreamento.html?AWB=)

    /*
     * CONFIGURAÇÕES DE PAGAMENTO
     * É aconselhado criar um e-mail padrão para recebimento de pagamentos
     * como por exemplo pagamentos@site.com. E assim configurar todos os
     * meios de pagamentos nele. Para que o gestor da loja tenha acesso
     * as notificações de e-mail!
     * 
     * ATENÇÃO: Para utilizar o checkout transparente é preciso habilitar a
     * conta junto ao PagSeguro. Para isso:
     * 
     * Acesse: https://pagseguro.uol.com.br/receba-pagamentos.jhtml#checkout-transparent
     * Clique em Regras de uso - Uma modal abre!
     * Clique em entre em contato conosco. E informe os dados solicitados!
     * 
     * PAGSEGURO
     */
    define('PAGSEGURO_ENV', 'sandbox'); //sandbox para teste e production para vender!
    define('PAGSEGURO_EMAIL', ''); //E-mail do vendedor na pagseguro!
    define('PAGSEGURO_NOTIFICATION_EMAIL', ''); //E-mail para receber notificações e gerenciar pedidos!

    /*
     * SANDBOX (AMBIENTE DE TESTE)
     */
    define('PAGSEGURO_TOKEN_SANDBOX', ''); //Token Sandbox (https://sandbox.pagseguro.uol.com.br/vendedor/configuracoes.html)
    define('PAGSEGURO_APP_ID_SANDBOX', ''); //Id do APP Sandbox (https://sandbox.pagseguro.uol.com.br/aplicacao/configuracoes.html)
    define('PAGSEGURO_APP_KEY_SANDBOX', ''); //Chave do AP Sandbox

    /*
     * PRODUCTION (AMBIENTE REAL)
     */
    define('PAGSEGURO_TOKEN_PRODUCTION', ''); //Token de produção (https://pagseguro.uol.com.br/preferencias/integracoes.jhtml)
    define('PAGSEGURO_APP_ID_PRODUCTION', ''); //Id do APP de integração (https://pagseguro.uol.com.br/aplicacao/listagem.jhtml)
    define('PAGSEGURO_APP_KEY_PRODUCTION', ''); //Chave do APP de integração!

    /*
     * CONFIGURAÇÕES DO EAD
     */
    define('EAD_REGISTER', 0); //Permitir cadastro na plataforma?
    define('EAD_HOTMART_EMAIL', 0); //Email de produtor hotmart!
    define('EAD_HOTMART_TOKEN', 0); //Token da API do hotmart!
    define('EAD_HOTMART_NEGATIVATE', 0); //Id de produtos na hotmart que NÃO serão entregues!
    define('EAD_HOTMART_LOG', 0); //Gerar Log de vendas?
    define('EAD_TASK_SUPPORT_DEFAULT', 1); //Por padrão habilitar suporte em todas as aulas?
    define('EAD_TASK_SUPPORT_EMAIL', "suporte@seusite.com.br"); //Enviar alertas de novos tickets para?
    define('EAD_TASK_SUPPORT_MODERATE', 0); //Tickets devem ser aprovados por um admin?
    define('EAD_TASK_SUPPORT_STUDENT_RESPONSE', 0); //Alunos podem responder o suporte?
    define('EAD_STUDENT_CERTIFICATION', 0); //Você pretende emitir certificados?
    define('EAD_STUDENT_MULTIPLE_LOGIN', 1); //Permitir login multiplo?
    define('EAD_STUDENT_MULTIPLE_LOGIN_BLOCK', 0); //Minutos de bloqueio quando login multiplo!
    define('EAD_STUDENT_CLASS_PERCENT', 100); //Assitir EAD_CLASS_PERCENT% para concluir!
    define('EAD_STUDENT_CLASS_AUTO_CHECK', 0); //Marcar tarefas como concluídas automaticamente?
endif;