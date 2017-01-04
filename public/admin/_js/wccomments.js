$(function () {
    $('.dashboard_content').on('click', '.wc_comment_action', function () {
        var CommId = $(this).attr('rel');
        var Action = $(this).attr('action');
        var Comment = $('#' + CommId);

        $.post('_ajax/Comments.ajax.php', {callback: 'Comments', callback_action: Action, id: CommId}, function (data) {
            //LIKES
            if (data.like) {
                Comment.find(".comm_actions#" + CommId + " a[action='like']").fadeOut(400);
                if (Comment.find(".comm_likes#" + CommId + " .na").length) {
                    Comment.find(".comm_likes#" + CommId + " .na").html(data.admin);
                } else {
                    Comment.find(".comm_likes#" + CommId + " span").after(", " + data.admin);
                }
            }

            //APROVAR
            if (data.aprove) {
                Comment.find('.comm_content').css('border-color', '#00B494');
                Comment.find(".aprove").html(data.aprove);
                if (data.alias) {
                    $('#' + data.alias).find('.comm_content').css('border-color', '#00B494');
                    $('#' + data.alias).find(".aprove").html(data.aprove);
                }
            }

            //DELETAR
            if (data.remove) {
                Comment.fadeOut(function () {
                    $(this).remove();
                });
                
                if (data.alias) {
                    $('#' + data.alias).find('.comm_content').css('border-color', '#00B494');
                    $('#' + data.alias).find(".aprove").html(data.aprove);
                }
            }

            //ERROR
            if (data.trigger) {
                Trigger(data.trigger);
            }
        }, 'json');
        return false;
    });

    $('.dashboard_content').on('click', '.wc_comment_open', function () {
        $(".form_" + $(this).attr('rel')).slideDown().find('textarea').focus();
        return false;
    });

    $('.dashboard_content').on('click', '.wc_comment_close', function () {
        $(".form_" + $(this).attr('id')).slideUp();
    });

    $('.dashboard_content').on('submit', 'form', function () {
        var form = $(this);
        var comm = form.find('input[name="alias_id"]').val();
        var data = form.serialize() + "&callback=Comments&callback_action=response";
        $.ajax({
            url: '_ajax/Comments.ajax.php',
            data: data,
            dataType: 'json',
            type: 'POST',
            beforeSend: function () {
                form.find('img').fadeIn();
            },
            success: function (data) {
                if (data.trigger) {
                    Trigger(data.trigger);
                }

                form.find('img').fadeOut(400, function () {
                    $('#' + comm + ' .response_list').before(data.comment);
                    $('.ajax_response').fadeIn(400, function () {
                        form.slideUp(400, function () {
                            form.find('textarea').val('');
                        });
                    });
                });
            }
        });
        return false;
    });
});