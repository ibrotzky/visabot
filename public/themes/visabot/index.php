<?php
if (APP_SLIDE):
    $SlideSeconts = 3;
    require '_cdn/widgets/slide/slide.wc.php';
endif;
?>

<div class="container main_content principal">
    <img title="CanadaBot" alt="[CanadaBot]" src="<?= INCLUDE_PATH ?>/images/logoHeader.svg"/>
    <h1>I Belive In Diversity, So I Help Immigrants<br>To Come And Bring Their Talents To Canada.</h1>
    <div class="clear"></div>
</div>
<div class="container main_content about">
    <img title="CanadaBot" alt="[CanadaBot]" src="<?= INCLUDE_PATH ?>/images/Bot.svg"/>
    <h1>Hi, I'm CanadaBot!</h1>
    <p class="tagline">I'm here to help you move to Canada Soon!</p>
    <div class="clear"></div>
</div>
<div class="container main_content contact">        
    <p>I'm still under construction, but if you'd like to be<br>the first to get access to me, let me know your e-mail!</p>
    <form class="contact_form" name="contact" action="" method="post" enctype="multipart/form-data">
        <input type="email" name="email" title="E-mail" placeholder="your@e-mail.com" required>
        <button value="notify me" onclick="notifyMe();">Notify me</button>
    </form>
    <p>Don't worry, I won't send you spam. I don't like it either.<br>(It's actually illegal to do that here in Canada!)</p>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    function notifyMe()
    {
        var div = $("#widgetu199");
        var email = $("#widgetu199_input");

        div.removeClass("fld-err-st")

        if (email.val() === '') {
            div.addClass("fld-err-st")

            return;
        } else {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

            if (!pattern.test(email.val())) {
                div.addClass("fld-err-st")

                return;
            }
        }

        var divForm = $("#widgetu192");
        var divSuccess = $("#success");
        var divError = $("#error");
        var divIllegal = $("#u178-6");

        $.ajax({
            type: 'POST',
            url: '/api/newsletters',
            cache: false,
            dataType: 'json',
            data: {email: email.val()}
        }).success(function (data, textStatus, jqXHR)
        {
            divForm.hide();
            divSuccess.show();
            divIllegal.css('visibility', 'hidden');

        }).error(function (jqXHR, textStatus, errorThrown)
        {
            divForm.hide();
            divError.show();
            divIllegal.css('visibility', 'hidden');

            console.log('Error: ', jqXHR.responseText);
        });
    }
</script>