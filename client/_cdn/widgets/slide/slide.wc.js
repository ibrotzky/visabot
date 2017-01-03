$(function () {
    var WorkControlSlideSeccont = $('.workcontroltime').attr('id');
    var WorkControlSlideAuto = setInterval(WorkControlSlideGO, parseInt(WorkControlSlideSeccont) * 1000);

    //SLIDE SLIDES
    function WorkControlSlideGO() {
        if ($('.wc_slide_item.first').next('.wc_slide_item').size()) {
            var WcSlideBg = $('.wc_slide_item.first').next('.wc_slide_item').find('img').attr('src');
            $('.wc_slides').css({
                'background-image': 'url(' + WcSlideBg + ')',
                'background-size': '100% auto',
                'background-repeat': 'no-repeat'
            });
            $('.wc_slide_item.first').fadeOut(400, function () {
                $(this).removeClass('first').next().fadeIn(400).addClass('first');
                WorControlSlideMark();
            });
        } else if ($('.wc_slide_item').length > 1) {
            var WcSlideBg = $('.wc_slide_item:eq(0)').find('img').attr('src');
            $('.wc_slides').css({
                'background-image': 'url(' + WcSlideBg + ')',
                'background-size': '100% auto',
                'background-repeat': 'no-repeat'
            });

            $('.wc_slide_item.first').fadeOut(400, function () {
                $('.wc_slide_item').removeClass('first');
                $('.wc_slide_item:eq(0)').fadeIn(400).addClass('first');
                WorControlSlideMark();
            });
        }
    }

    //SLIDE PAGER
    function WorControlSlideMark() {
        var wcSlideTHis = $('.wc_slide_item.first').index() - 1;
        $('.wc_slide_pager span').removeClass('active');
        $('.wc_slide_pager span:eq(' + wcSlideTHis + ')').addClass('active');
    }

    //PAGER ACTION
    $('.wc_slide_pager span').click(function () {
        $('.wc_slide_item').stop();
        clearInterval(WorkControlSlideAuto);

        if (!$(this).hasClass('active')) {
            var WcMark = $(this).index();
            $('.wc_slide_pager span').removeClass('active');
            $(this).addClass('active');

            var WcSlideBg = $('.wc_slide_item:eq(' + WcMark + ')').find('img').attr('src');
            $('.wc_slides').css({
                'background-image': 'url(' + WcSlideBg + ')',
                'background-size': '100% auto',
                'background-repeat': 'no-repeat'
            });

            $('.wc_slide_item.first').fadeOut(400, function () {
                $('.wc_slide_item').removeClass('first');
                $('.wc_slide_item:eq(' + WcMark + ')').fadeIn(400).addClass('first');
            });
        }
    });

    //MOUSE STOP
    $('.wc_slide_pager').mouseenter(function () {
        clearInterval(WorkControlSlideAuto);
    }).mouseleave(function () {
        WorkControlSlideAuto = setInterval(WorkControlSlideGO, WorkControlSlideSeccont * 1000);
    });
});