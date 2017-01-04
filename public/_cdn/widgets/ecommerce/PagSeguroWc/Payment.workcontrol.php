<?php
require '_cdn/widgets/ecommerce/PagSeguro/PagSeguroLibrary.php';

$sessionId = null;
$Yhash = substr(date("Y"), 0, 2);

try {
    $credentials = PagSeguroConfig::getAccountCredentials();
    $sessionId = PagSeguroSessionService::getSession($credentials);
    $_SESSION['wc_pagseguro'] = $sessionId;
} catch (PagSeguroServiceException $e) {
    Erro("Erro ao Processar Pagamento: Informe <b>{$e->getMessage()}</b> via " . AGENCY_EMAIL, E_USER_ERROR);
    $sessionId = null;
}

if (PAGSEGURO_ENV == 'sandbox'):
    echo '<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>';
else:
    echo '<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>';
endif;

require 'Checkout.workcontrol.php';
?>
<div class="workcontrol_load"><p class="load_message">Aguarde enquanto processamos o pagamento!</p><div class="workcontrol_load_content"><div class="workcontrol_load_ajax"></div><span class="workcontrol_load_close">X Fechar</span></div></div>

<?php if (ECOMMERCE_PAY_SPLIT): ?>
    <ul class="workcontrol_pay_tabs">
        <li class="active" id="card">Cartão de Credito:</li><li id="billet">Boleto Bancário:</li>
    </ul>

    <form id="card" autocomplete="off" name="workcontrol_pagseguro" class="workcontrol_pagseguro" action="" method="post">
        <div class="labelline">
            <label class="label70">
                <span>Número do Cartão:</span>
                <input required type="text" onkeypress="return wcIsNumericHit(event)" class="workcontrol_cardnumber" maxlength="22" name="cardNumber" id="cartao" placeholder="Número do cartão:"/>
            </label><div class="label30 labelDate">
                <span class="span">Data de Validade:</span>
                <div class="labelline">
                    <div class="month"><input required onkeypress="return wcIsNumericHit(event)" maxlength="2" type="text" name="expirationMonth" id="validadeMes" placeholder="MM"/></div><div class="year"><input required onkeypress="return wcIsNumericHit(event)" maxlength="2" type="text" name="expirationYear" id="validadeAno" placeholder="YY"/></div>
                </div>
            </div>
        </div>
        <div class="labelline">
            <label class="label70">
                <span>Nome Impresso no Cartão:</span>
                <input required type="text" name="cardName" id="nome" placeholder="Nome impresso no cartão:"/>
            </label><div class="label30">
                <label>
                    <span>Código de Segurança:</span>
                    <input required onkeypress="return wcIsNumericHit(event)" id="cvv" maxlength="4" type="text" name="cardCVV" placeholder="CVV:"/>
                </label>
            </div>
        </div>

        <div class="workcontrol_carddata">
            <h3>Dados do Titular do Cartão:</h3>
            <label class="label50 first">
                <span>CPF:</span>
                <input required onkeypress="return wcIsNumericHit(event)" type="text" class="formCpf" name="cardCPF" placeholder="CPF do titular do cartão:"/>
            </label><div class="label50 last">
                <label>
                    <span>Data de Nascimento:</span>
                    <input required onkeypress="return wcIsNumericHit(event)" type="text" class="formDate" name="cardBirthDate" placeholder="Data de nascimento do titular do cartão:"/>
                </label>
            </div>
        </div>

        <div class="labelline labelactions">
            <label class="label50">
                <select required name="cardInstallmentQuantity" id="cardInstallmentQuantity">
                    <option value="" disabled selected>PARCELAMENTO:</option>
                </select>
            </label>
            <button class="btn btn_green wc_button_cart fl_right">Comprar Agora!</button>
        </div>
        <div class="clear"></div>
    </form>
<?php endif; ?>

<form id="billet" <?= (!ECOMMERCE_PAY_SPLIT ? 'style="display: block;"' : ''); ?> autocomplete="off" name="workcontrol_pagseguro" class="workcontrol_pagseguro workcontrol_pagseguro_billet" action="" method="post">
    <div>
        <h3>Detalhes de pagamento:</h3>
        <p>Fique atento(a) ao vencimento do boleto. Você pode pagar o boleto em qualquer banco ou casa lotérica até o dia do vencimento!</p>
        <p>As compras efetuadas com boleto levam até 3 dias úteis para serem compensadas. Este prazo deve ser estimado por você ao prazo de envio do produto!</p>
        <h4>Valor a pagar: <b>R$ <?= number_format($order_price, '2', ',', '.'); ?></b></h4>
    </div>
    <div class="labelline" style="margin-top: 20px;">
        <button class="btn btn_green wc_button_cart fl_right">Gerar Boleto!</button>
    </div>
    <div class="clear"></div>
</form>

<!--NÃO REMOVA A LOGO PAGSEGURO, É UMA REGRA DE UTILIZAÇÃO DO CHECKOUT!-->
<div class="workcontrol_pagseguro_logo"><img title="Pagamento processado pelo PagSeguro!" alt="Pagamento processado pelo PagSeguro!" src="<?= BASE; ?>/_cdn/widgets/ecommerce/PagSeguroWc/pagseguro.gif"></div>