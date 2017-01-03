$(function () {
    $('.workcontrol_socialshare_item a').click(function () {
        var url = $(this).attr('href');
        var width = 600;
        var height = 600;

        var leftPosition, topPosition;
        leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
        topPosition = (window.screen.height / 2) - ((height / 2) + 100);
        window.open(url, "Window2",
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
        var shareUrl = $('.workcontrol_socialshare_facebook a').attr('rel');
        $.getJSON("//graph.facebook.com/" + shareUrl, function (data) {
            console.log(data);
            $('.workcontrol_socialshare_facebook span').text((data.share.share_count ? addZeros(data.share.share_count) : '00'));
        });
    }

    //GET GOOGLE SHARE
    if ($(".workcontrol_socialshare_googleplus").length) {
        var shareUrl = $('.workcontrol_socialshare_googleplus a').attr('rel');
        var BASE = $('link[rel="base"]').attr('href');
        $.post(BASE + '/_cdn/widgets/share/google.php', {url: shareUrl}, function (data) {
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