<script>
    $(function () {
        //TAB PAYMMENT
        $('.workcontrol_pay_tabs li').click(function () {
            var WorkControlPayTab = $(this).attr('id');
            var WorkControlTab = $(this);
            $('.workcontrol_pay_tabs li').removeClass('active');
            $("form[id!='" + WorkControlPayTab + "']").slideUp(200, function () {
                $("form#" + WorkControlPayTab).slideDown(200);
                WorkControlTab.addClass('active');
            });
        });

        var cartTag = null;
        PagSeguroDirectPayment.setSessionId('<?= $sessionId; ?>');

        //CARTÃO
        $('#cartao').keyup(function () {
            var Input = $(this);
            var nLEN = $(this).val().length;
            if (parseInt(nLEN) === 4 || parseInt(nLEN) === 10 || parseInt(nLEN) === 16) {
                Input.val(Input.val() + "  ");
            }
        }).focusin(function () {
            $('.wc_order_error').fadeOut(100, function () {
                $(this).remove();
            });
        }).change(function () {
            var Card = $(this).val().replace(/ /g, '');
            if (Card.length !== parseInt(16)) {
                $('#cardInstallmentQuantity').html('<option value="" disabled selected>PARCELAMENTO:</option>');
                $('#cartao').after("<p class='wc_order_error'>&#10008; Numero do cartão inválido. Informe o número do cartão!</p>");
                $('.wc_order_error').fadeIn();
            } else {
                PagSeguroDirectPayment.getBrand({
                    cardBin: Card,
                    success: function (data) {
                        cartTag = data.brand.name;
                        $('.workcontrol_cardnumber').css('background-image', 'url(https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/' + data.brand.name + '.png)');
                        wcGetInstallments();
                    },
                    error: function (data) {
                        $('#cardInstallmentQuantity').html('<option value="" disabled selected>PARCELAMENTO:</option>');
                        $('#cartao').after("<p class='wc_order_error'>&#10008; Cartão inválido. Informe o número do cartão!</p>");
                        $('.wc_order_error').fadeIn();
                    }
                });
            }
        });

        //CARDNAME
        $('#nome').keyup(function () {
            $(this).val(function (i, val) {
                return val.toUpperCase();
            });
        });

        //CREDIT CARD SUBMIT
        $("form#card").submit(function () {
            var Form = $(this);
            var Data = Form.serialize() + "&workcontrol=creditCardData";
            var senderHash = PagSeguroDirectPayment.getSenderHash();

            $.ajax({
                url: '<?= BASE; ?>/_cdn/widgets/ecommerce/PagSeguroWc/Ajax.workcontrol.php',
                data: Data,
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    $('.wc_order_error').fadeOut(1, function () {
                        $(this).remove();
                    });
                    $('.workcontrol_load').fadeIn(100);
                },
                success: function (data) {
                    if (data.error) {
                        $('.workcontrol_load').fadeOut(100);
                        if (data.field) {
                            Form.find("input[name='" + data.field + "']").after(data.error);
                        } else {
                            var Inputs = Form.find('input, select');
                            Inputs.each(function (index, elem) {
                                if (!elem.value) {
                                    $(this).after(data.error);
                                }
                            });
                        }
                        $('.wc_order_error').fadeIn();
                    } else if (data.triggerError) {
                        $('.workcontrol_load_ajax').html(data.triggerError);
                        $('.workcontrol_load_content').fadeIn(400);
                    } else if (data.success) {
                        //TOKEN DO CARTÃO
                        var creditCardToken = null;
                        var param = {
                            cardNumber: $("input#cartao").val().replace(/ /g, ''),
                            brand: cartTag,
                            cvv: $("input#cvv").val(),
                            expirationMonth: $("input#validadeMes").val(),
                            expirationYear: parseInt(<?= $Yhash; ?>) + $("input#validadeAno").val(),
                            success: function (data) {
                                var creditCardToken = data.card.token;
                                var Data = Form.serialize() + "&workcontrol=creditCard&senderHash=" + senderHash + "&creditCardToken=" + creditCardToken;
                                $.post('<?= BASE; ?>/_cdn/widgets/ecommerce/PagSeguroWc/Ajax.workcontrol.php', Data, function (data) {
                                    //MODAL ERRORS
                                    if (data.triggerError) {
                                        $('.workcontrol_load_ajax').html(data.triggerError);
                                        $('.workcontrol_load_content').fadeIn();
                                    } else if (data.resume) {
                                        window.location.href = data.resume;
                                    } else {
                                        $('.workcontrol_load').fadeOut(100, function () {
                                            $('.workcontrol_load_ajax').html('');
                                        });
                                    }
                                }, 'json');
                            },
                            error: function (data) {
                                if (data.errors['10000']) {
                                    $('.workcontrol_load').fadeOut(100);
                                    Form.find("input[name='cardNumber']").after("<p class='wc_order_error'>&#10008; Cartão inválido ou não aceito!</p>");
                                }
                                else if (data.errors['10001']) {
                                    $('.workcontrol_load').fadeOut(100);
                                    Form.find("input[name='cardNumber']").after("<p class='wc_order_error'>&#10008; Número do cartão não é válido!</p>");
                                }
                                else if (data.errors['10002']) {
                                    $('.workcontrol_load').fadeOut(100);
                                    Form.find("input[name='expirationMonth']").after("<p class='wc_order_error'>&#10008; Data inválida!</p>");
                                    Form.find("input[name='expirationYear']").after("<p class='wc_order_error'>&#10008; Data inválida!</p>");
                                }
                                else if (data.errors['10003']) {
                                    $('.workcontrol_load').fadeOut(100);
                                    Form.find("input[name='cardCVV']").after("<p class='wc_order_error'>&#10008; Código Inválido!</p>");
                                }
                                else if (data.errors['10004']) {
                                    $('.workcontrol_load').fadeOut(100);
                                    Form.find("input[name='cardCVV']").after("<p class='wc_order_error'>&#10008; Código Obrigatório!</p>");
                                }
                                else if (data.errors['10006']) {
                                    $('.workcontrol_load').fadeOut(100);
                                    Form.find("input[name='cardCVV']").after("<p class='wc_order_error'>&#10008; CVV inválido!</p>");
                                }
                                else if (data.errors['30400']) {
                                    $('.workcontrol_load_ajax').html("<p class='big'>&#10008; Erro ao processar pagamento!</p><p class='min'>Não foi possível processar o pagamento pois existem dados incorretos no cartão. Favor, <b>verifique os dados do cartão</b> e tente novamente!</p>");
                                    $('.workcontrol_load_content').fadeIn();
                                }
                                else {
                                    $('.workcontrol_load_ajax').html("<p class='big'>&#10008; Erro ao processar pagamento!</p><p class='min'>Existe um problema com os dados, autorização ou comunicação com o cartão. Para continuar, <b>atualize a página</b> e tente novamente!</p>");
                                    $('.workcontrol_load_content').fadeIn();
                                }
                                $('.wc_order_error').fadeIn(100);
                            }
                        };
                        PagSeguroDirectPayment.createCardToken(param);
                    } else {
                        $(".workcontrol_load").fadeIn(function () {
                            $('.workcontrol_load_ajax').html("<p class='big'>&#10008; Erro ao processar pagamento!</p><p class='min'>Você pode tentar novamente. Ou entre em contato pelo nosso telefone <?= SITE_ADDR_PHONE_A; ?> e informe que o número do pedido é <?= $order_id; ?>!</p>");
                            $('.workcontrol_load_content').fadeIn();
                        });
                    }
                }
            });
            return false;
        });

        //BILLET SUBMITE
        $("form#billet").submit(function () {
            var senderHash = PagSeguroDirectPayment.getSenderHash();
            var Data = "workcontrol=billet&senderHash=" + senderHash;

            $.ajax({
                url: '<?= BASE; ?>/_cdn/widgets/ecommerce/PagSeguroWc/Ajax.workcontrol.php',
                data: Data,
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    $('.wc_order_error').fadeOut(1, function () {
                        $(this).remove();
                    });
                    $('.workcontrol_load').fadeIn(100);
                },
                success: function (data) {
                    if (data.billet) {
                        window.open(data.billet, "popupWindow", "width=960,height=600,scrollbars=yes");
                    }
                    if (data.resume) {
                        window.location.href = data.resume;
                    }
                }
            });

            PagSeguroDirectPayment.getSenderHash();
            return false;
        });

        //CLOSE MODAL
        $('html').on('click', '.workcontrol_load_close', function () {
            $('.workcontrol_load').fadeOut(100, function () {
                $('.workcontrol_load_ajax').html('');
                $('.workcontrol_load_content').fadeOut();
            });
        });

        function wcGetInstallments() {
            PagSeguroDirectPayment.getInstallments({
                amount: <?= number_format($order_price, '2', '.', ''); ?>,
                brand: cartTag,
                maxInstallmentNoInterest: <?= (ECOMMERCE_PAY_SPLIT_ACN < 2 ? 0 : ECOMMERCE_PAY_SPLIT_ACN); ?>,
                success: function (data) {
                    var Installments = data.installments[cartTag];
                    $('#cardInstallmentQuantity').html('<option value="">Selecione as parcelas:</option>');
                    $.each(Installments, function (index, elem) {
                        $("#cardInstallmentQuantity option:last").after('<option value="' + elem.quantity + 'x' + elem.installmentAmount + '">' + elem.quantity + 'x R$ ' + number_format(elem.installmentAmount, '2', ',', '.') + ' - ' + (elem.interestFree === true ? "sem acréscimo" : 'R$ ' + number_format(elem.totalAmount, '2', ',', '.') + '*') + '</option>');
                    });
                }
            });
        }
    });

    //NUMBER HIT VALID
    function wcIsNumericHit(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    //NUMBER FORMAT - PHP SIMILAR
    function number_format(numero, decimal, decimal_separador, milhar_separador) {
        numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+numero) ? 0 : +numero,
                prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
                sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
                dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>