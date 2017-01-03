$(function () {

    //############## FBLIKE RESPONSIVE CONTROL
    if ($('.fb-like').length) {
        $(window).load(function () {
            if ($('.fb-like').width() < 600 && $(window).width() < 600) {
                $('.fb-like').attr('data-width', $('.fb-like').width());
            }
        });
    }

    //############## IFRAME RESET
    function VideoResize() {
        $('.htmlchars iframe').each(function () {
            var url = $(this).attr("src");
            var char = "?";
            if (url.indexOf("?") != -1) {
                var char = "&";
            }

            var iw = $(this).width();
            var width = $('.htmlchars').outerWidth();
            var height = (iw * 9) / 16;
            $(this).attr({width: width, height: height}).css({width: width + "px", height: height + "px"});
        });
    }
    VideoResize();
    $(window).resize(function () {
        VideoResize();
    });

    //############## GOTO CORE
    $('.wc_goto').click(function () {
        var Goto = $($(this).attr("href"));
        if (Goto.length) {
            $('html, body').animate({scrollTop: Goto.offset().top}, 800);
        } else {
            $('html, body').animate({scrollTop: 0}, 800);
        }
        return false;
    });

    //############## IMAGE ERROR
    $('img').error(function () {
        var s, w, h, b;
        s = $(this).attr('src');
        w = 500;
        h = 500;
        b = $('link[rel="base"]').attr('href');
        $(this).attr('src', b + '/tim.php?src=admin/_img/no_image.jpg&w=' + w + "&h=" + h);
    });

    //############## GET CEP
    $('.wc_getCep').change(function () {
        var cep = $(this).val().replace('-', '').replace('.', '');
        if (cep.length === 8) {
            $.get("https://viacep.com.br/ws/" + cep + "/json", function (data) {
                if (!data.erro) {
                    $('.wc_bairro').val(data.bairro);
                    $('.wc_complemento').val(data.complemento);
                    $('.wc_localidade').val(data.localidade);
                    $('.wc_logradouro').val(data.logradouro);
                    $('.wc_uf').val(data.uf);
                }
            }, 'json');
        }
    });

    //############## MASK INPUT    
    $(".formDate").mask("99/99/9999");
    $(".formTime").mask("99/99/9999 99:99");
    $(".formCep").mask("99999-999");
    $(".formCpf").mask("999.999.999-99");

    $('.formPhone').focusout(function () {
        var phone, element;
        element = $(this);
        element.unmask();
        phone = element.val().replace(/\D/g, '');
        if (phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    }).trigger('focusout');


    //############## DATEPICKER
    $('.jwc_datepicker').datepicker({language: 'pt-BR', autoClose: true});


    //############## WC TAB
    $('.wc_tab').click(function () {
        if (!$(this).hasClass('wc_active')) {
            var WcTab = $(this).attr('href');

            $('.wc_tab').removeClass('wc_active');
            $(this).addClass('wc_active');

            $('.wc_tab_target.wc_active').fadeOut(200, function () {
                $(WcTab).fadeIn(200).addClass('wc_active');
            }).removeClass('wc_active');
        }

        if (!$(this).hasClass('wc_active_go')) {
            return false;
        }
    });

    //############## MODAL BOX
    Shadowbox.init();
});