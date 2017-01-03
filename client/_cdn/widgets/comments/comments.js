$(function () {
    var base = $('link[rel="base"]').attr('href') + "/_cdn/widgets/comments";

    //STOP ALL FORM
    $(".comments form, .comment_login_box form").submit(function () {
        return false;
    });

    //LIKE
    $('.wc_like').click(function () {
        var Likethis = $(this);
        var CommentId = $(this).attr('id');

        $.post(base + '/comments.ajax.php', {action: 'like', id: CommentId}, function (data) {
            if (data.like) {
                if ($('.comments_single_likes#' + CommentId + " .na").length) {
                    $('.comments_single_likes#' + CommentId + " .na").html(data.like);
                } else {
                    $('.comments_single_likes#' + CommentId + " span").first().before(data.like + ", ");
                }
                Likethis.off().css('color', '#00B494').find('b').text(parseInt(Likethis.find('b').text()) + 1);
                setTimeout(function () {
                    Likethis.css('color', '#ccc');
                }, 500);
            } else if (data.liked) {
                Likethis.off().css('color', '#C54550');
                setTimeout(function () {
                    Likethis.html("<b>" + Likethis.find('b').html() + '</b> VOCÊ JÁ CURTIU ISSO!').css({'color': '#ccc', 'text-decoration': 'none', 'cursor': 'default'});
                }, 500);
            } else {
                wcCommentLogin(null, Likethis);
            }
        }, 'json');
    });

    //RESPONSE
    $('.wc_response').click(function () {
        var Response = $(this);
        var CommentId = $(this).attr('id');
        var CommnetTo = Response.attr('rel');

        $('.wc_response').fadeIn(1, function () {
            Response.fadeOut(2);
        });

        //START SESSION COMMENT ID
        $.post(base + '/comments.ajax.php', {action: 'setcomment', id: CommentId, to: CommnetTo}, function (data) {
            $('.comments_single form').slideUp().find('textarea').val('');
            $('#comment' + CommentId + ' form').first().find('textarea').after(data.comment);
            $('#comment' + CommentId + ' form').first().slideDown();
        }, 'json');
    });

    //CLOSE FORM
    $('.wc_close').click(function () {
        $('.wc_response').fadeIn();
        $('.comments_single form').slideUp();
    });

    //FORM COMMENT SEND
    $('form[name="add_comment"]').submit(function () {
        var Form = $(this);
        var Data = Form.serialize() + "&action=response";

        $.ajax({
            url: base + '/comments.ajax.php',
            data: Data,
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function () {
                Form.find('img').fadeIn();
            },
            success: function (data) {
                Form.find('img').fadeOut();
                if (data.login) {
                    wcCommentLogin(Form, null);
                } else if (data.response) {
                    Form.before(data.response);
                    $('.ajax_response').fadeIn(400, function () {
                        if (data.alias) {
                            Form.slideUp().find('textarea').val('');
                            $('.wc_response').fadeIn();
                        } else {
                            Form.find('textarea').val('');
                        }
                    });
                }
            }
        });
        return false;
    });

    //COMMENT LOGIN
    $('.comment_login_close').click(function () {
        $('.comment_login_box').fadeOut(100);
    });

    //GET USER IF EXISTS OF INPUT CREATE SHOW
    $('.wc_login_email').change(function () {
        var email = $(this).val();
        $.post(base + '/comments.ajax.php', {action: 'getuser', email: email}, function (data) {
            $('.comment_login_error').fadeOut(1);
            if (data.create) {
                $('.comment_login_action').val('createuser');
                $('.comment_login_join').fadeOut(1);
                $('.comment_login_create').fadeIn(200, function () {
                    $("form[name='comment_login'] input:eq(2)").focus();
                });
            } else if (data.login) {
                $('.comment_login_action').val('loginuser');
                $('.comment_login_create').fadeOut(1);
                $('.comment_login_join').fadeIn(200, function () {
                    $("form[name='comment_login'] input:eq(4)").focus();
                });
            }

            if (data.trigger) {
                $('.comment_login_error').html(data.trigger).fadeIn(400);
            }
        }, 'json');
    });

    //RECOVER
    $('html').on('click', '.comment_recover_password', function () {
        var Email = $(this).attr('id');
        $('form[name="comment_login"] img').fadeIn();

        //START SESSION RECOVER CODE
        $.post(base + '/comments.ajax.php', {action: 'recoversend', email: Email}, function (data) {
            $('form[name="comment_login"] img').fadeOut();
            if (data.email) {
                $('.comment_login_action').val('recoveruser');
                $('.comment_recover_label b').text(data.email);
                $('.comment_label').slideUp(400, function () {
                    $('.comment_recover_label').slideDown();
                });
            }
            if (data.trigger) {
                $('.comment_login_error').html(data.trigger).fadeIn(400);
            }
        }, 'json');
    });

    //SEND LOGIN
    $('form[name="comment_login"]').submit(function () {
        var Form = $(this);
        var Data = Form.serialize();

        $.ajax({
            url: base + '/comments.ajax.php',
            data: Data,
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function () {
                Form.find('img').fadeIn();
            },
            success: function (data) {
                Form.find('img').fadeOut();
                if (typeof RequestComment !== 'undefined' && data.user) {
                    $('.comment_login_box').fadeOut(400, function () {
                        $(this).remove();
                        RequestComment.submit();
                    });
                } else if (typeof RequestLike !== 'undefined' && data.user) {
                    $('.comment_login_box').fadeOut(400, function () {
                        $(this).remove();
                        RequestLike.click();
                    });
                } else if (data.recover_error) {
                    $('.comment_recover_label').slideUp(400, function () {
                        $('.comment_label input').val('');
                        $('.comment_label:eq(0)').slideDown();
                    });
                }

                if (data.trigger) {
                    $('.comment_login_error').html(data.trigger).fadeIn(400);
                }
            }
        });
    });

    //RECOVER BACK
    $('.comment_recover_back').click(function () {
        $('.comment_recover_label').slideUp(400, function () {
            $('.comment_label input').val('');
            $('.comment_label:eq(0)').slideDown();
            $('.comment_login_error').fadeOut(400, function () {
                $(this).html('');
            });
        });
    });
});

//USER COMMENT LOGIN
function wcCommentLogin(FormSubmit, LikeSubmit) {
    $('.comment_login_box').fadeIn(100);
    if (FormSubmit) {
        //SEND RESPONSE
        //FormSubmit.submit();
        RequestComment = FormSubmit;
    } else {
        //LIKE COMMENT
        RequestLike = LikeSubmit;
    }
}