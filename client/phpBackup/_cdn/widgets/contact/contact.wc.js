/*
 To change this license header, choose License Headers in Project Properties.
 To change this template file, choose Tools | Templates
 and open the template in the editor.

 Created on : 23/06/2016, 12:59:15
 Author     : Robson V. Leite <robsonvleite.com>
 */

$(function () {
    var WcAjax = $("link[rel='base']").attr('href') + "/_cdn/widgets/contact/contact.ajax.php";

    $('.jwc_contact').click(function () {
        $('.jwc_contact_modal').fadeIn(200);
        return false;
    });

    $('.jwc_contact_close').click(function () {
        $('.jwc_contact_modal').fadeOut(200);
        return false;
    });

    $('.jwc_contact_form').submit(function () {
        var WcForm = $(this);
        WcForm.find('img').fadeIn(200);

        var ContactData = "action=wc_send_contact&" + $(this).serialize();
        $.post(WcAjax, ContactData, function (data) {
            WcForm.find('img').fadeOut(200);

            if (data.wc_contact_error) {
                $('.jwc_contact_error').html(data.wc_contact_error).fadeIn();
            } else {
                $('.jwc_contact_error').fadeOut();
            }

            if (data.wc_send_mail) {
                $('.jwc_contant_sended_name').text(data.wc_send_mail);
                $('.jwc_contact_form').fadeOut(400, function () {
                    $('.jwc_contant_sended').fadeIn(400);
                });
            }
        }, 'json');
        return false;
    });
});

