$(function () {
    var base = $('link[rel="base"]').attr('href') + "/_cdn/widgets/account/account";

    $('.account_form_callback_fixed').mouseover(function () {
        $(this).fadeOut(400, function () {
            $(this).html('');
        });
    });

    //BLOCK FORM
    $("form[name='account_form']").submit(function () {
        var Form = $(this);

        Form.ajaxSubmit({
            url: base + '.ajax.php',
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function () {
                Form.find('img').fadeIn();
            },
            uploadProgress: function (evento, posicao, total, completo) {
            },
            success: function (data) {
                Form.find('img').fadeOut();

                if (data.trigger) {
                    Form.find(".account_form_callback").fadeOut(400, function () {
                        $(this).html(data.trigger).fadeIn();
                    });
                }

                if (data.redirect) {
                    setTimeout(function () {
                        window.location.href = data.redirect;
                    }, 1000);
                }

                if (data.clear) {
                    Form.clearForm();
                }

                Form.find('input[type="file"]').val('');
            }
        });
        return false;
    });

    //CAPA VIEW
    $('.wc_loadimage').change(function () {
        var input = $(this);
        var target = $('.' + input.attr('id'));
        var fileDefault = target.attr('default');

        if (!input.val()) {
            target.fadeOut('fast', function () {
                $(this).attr('src', fileDefault).fadeIn('slow');
            });
            return false;
        }

        if (this.files && this.files[0].type.match('image.*')) {
            var reader = new FileReader();
            reader.onload = function (e) {
                target.fadeOut('fast', function () {
                    $(this).attr('src', e.target.result).fadeIn('fast');
                });
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            $("form[name='account_form'] .account_form_callback").fadeOut(400, function () {
                $(this).html('<div class="trigger trigger_alert trigger_ajax"><b class="icon-warning">OPPSSS:</b> O arquivo selecionado não é válido! Selecione uma <b>imagem JPG ou PNG</b> para enviar!</div>').fadeIn();
            });

            target.fadeOut('fast', function () {
                $(this).attr('src', fileDefault).fadeIn('slow');
            });
            input.val('');
            return false;
        }
    });
});