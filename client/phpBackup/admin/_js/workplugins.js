$(function () {
    //############## TINYMCE
    if ($('.work_mce').length) {
        wc_tinyMCE();
    }

    //############## DATEPICKER
    $('.jwc_datepicker').datepicker({
        language: 'pt-BR',
        autoClose: true
    });

    if ($('.work_mce_basic').length) {
        wc_tinyMCE_basic();
    }

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

    //############## SOCIAL SHARE
    $('.workcontrol_socialshare_item a').click(function () {
        var url = $(this).attr('href');
        var Share;
        if ($(this).hasClass('wc_fb')) {
            Share = "https://www.facebook.com/sharer/sharer.php?u=" + url;
        } else if ($(this).hasClass('wc_gp')) {
            Share = "https://plus.google.com/share?url=" + url;
        } else if ($(this).hasClass('wc_tw')) {
            Share = "https://twitter.com/intent/tweet?url=" + url + "&text=" + $('input[name*="_title"]').val();
        }

        var width = 600;
        var height = 600;

        var leftPosition, topPosition;
        leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
        topPosition = (window.screen.height / 2) - ((height / 2) + 100);
        window.open(Share, "Window2",
                "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
                + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
                + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
        return false;
    });

    function addZeros(n) {
        return (n < 10) ? '0' + n : n;
    }

    //GET FACEBOOK SHARE
    if ($(".workcontrol_socialshare_facebook").length) {
        var shareUrl = $('.workcontrol_socialshare_facebook a').attr('href');
        $.getJSON("//api.facebook.com/restserver.php?format=json&method=links.getStats&urls=" + shareUrl, function (data) {
            $('.workcontrol_socialshare_facebook span').text((data[0] ? addZeros(data[0].share_count) : '00'));
        });
    }

    //GET FACEBOOK SHARE
    if ($(".workcontrol_socialshare_googleplus").length) {
        var shareUrl = $('.workcontrol_socialshare_googleplus a').attr('href');
        var BASE = $('link[rel="base"]').attr('href');
        $.post('../_cdn/widgets/share/google.php', {url: shareUrl}, function (data) {
            if (parseInt(data)) {
                $('.workcontrol_socialshare_googleplus span').text(addZeros(data));
            }
        });
    }

    //ADD COUNT
    $('.workcontrol_socialshare_item a').click(function () {
        var SpanCount = $(this).find('span').attr('class');
        var SpanText = $(this).find('span').text();
        $("." + SpanCount).text(addZeros(parseInt(SpanText) + parseInt(1)));
    });
});

//FUNCTION TINYMCE
function wc_tinyMCE() {
    tinyMCE.init({
        selector: "textarea.work_mce",
        language: 'pt_BR',
        menubar: false,
        theme: "modern",
        height: 200,
        skin: 'light',
        entity_encoding: "raw",
        theme_advanced_resizing: true,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor media"
        ],
        toolbar: "styleselect | forecolor | backcolor | pastetext | removeformat |  bold | italic | underline | strikethrough | bullist | numlist | alignleft | aligncenter | alignright |  link | unlink | upinsideimage | media |  outdent | indent | fullscreen | preview | code",
        content_css: "_css/tinyMCE.css?wc=true",
        style_formats: [
            {title: 'Normal', block: 'p'},
            {title: 'Titulo 3', block: 'h3'},
            {title: 'Titulo 4', block: 'h4'},
            {title: 'Titulo 5', block: 'h5'},
            {title: 'Código', block: 'pre', classes: 'brush: php;'}
        ],
        link_class_list: [
            {title: 'None', value: ''},
            {title: 'Call To Action', value: 'calltoaction'}
        ],
        setup: function (editor) {
            editor.addButton('upinsideimage', {
                title: 'Enviar Imagem',
                icon: 'image',
                onclick: function () {
                    $('.workcontrol_imageupload').fadeIn('fast');
                }
            });
        },
        link_title: false,
        target_list: false,
        theme_advanced_blockformats: "h1,h2,h3,h4,h5,p,pre",
        media_dimensions: false,
        media_poster: false,
        media_alt_source: false,
        media_embed: false,
        extended_valid_elements: "a[href|target=_blank|rel|class]",
        imagemanager_insert_template: '<img src="{$url}" title="{$title}" alt="{$title}" />',
        image_dimensions: false,
        relative_urls: false,
        remove_script_host: false,
        paste_as_text: true
    });
}

//FUNCTION TINYMCE BASIC
function wc_tinyMCE_basic() {
    tinyMCE.init({
        selector: "textarea.work_mce_basic",
        language: 'pt_BR',
        menubar: false,
        theme: "modern",
        height: 200,
        skin: 'light',
        entity_encoding: "raw",
        theme_advanced_resizing: true,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor media"
        ],
        toolbar: "styleselect | forecolor | backcolor | pastetext | removeformat |  bold | italic | underline | strikethrough | bullist | numlist |  link | unlink | fullscreen",
        content_css: "_css/tinyMCE.css",
        style_formats: [
            {title: 'Normal', block: 'p'},
            {title: 'Titulo 3', block: 'h3'},
            {title: 'Titulo 4', block: 'h4'},
            {title: 'Titulo 5', block: 'h5'}
        ],
        link_class_list: [
            {title: 'None', value: ''},
            {title: 'Call To Action', value: 'calltoaction'}
        ],
        link_title: false,
        target_list: false,
        theme_advanced_blockformats: "h1,h2,h3,h4,h5,p,pre",
        media_dimensions: false,
        media_poster: false,
        media_alt_source: false,
        media_embed: false,
        extended_valid_elements: "a[href|target=_blank|rel|class]",
        imagemanager_insert_template: '<img src="{$url}" title="{$title}" alt="{$title}" />',
        image_dimensions: false,
        relative_urls: false,
        remove_script_host: false,
        paste_as_text: true
    });
}

