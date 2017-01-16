var chat;
var chatHistory;
var question;
var remarks;
var questionAfterRemarks;
var reply;

var lastQuestion = null;

var payload = {};

var slideDownSpeed = 250;
var slideUpSpeed = 250;

function answerQuestion(answer, post) {
    if (post === undefined && reply.find(".reply").length > 0)
        reply.slideUp(slideUpSpeed, function () { answerQuestion(answer, true) });
    else
        post = true;

    if (!post)
        return;

    var request = {
        "payload": payload,
        "reply": answer
    }

    console.log('Request:', request);

    $.ajax({
        type: 'GET',
        url: '/api/canada/expressEntry',
        cache: false,
        dataType: 'json',
        data: request
    }).success(function (data, textStatus, jqXHR) {
        var responseJSON = data.responseJSON;

        if (lastQuestion !== null)
        {
            var questionNode = $($("<div id='question" + payload.question + "'>").html(getTemplate("questionTemplate")));

            questionNode.find(".balloon").html(lastQuestion);
            questionNode.find("div.back > span").attr("data-id", payload.question);

            $(questionNode).appendTo(chatHistory);

            question.html('');

            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        }

        lastQuestion = responseJSON.question;

        if (typeof (lastQuestion) === 'object')
            lastQuestion = lastQuestion[lastQuestion.length - 1];


        if (answer !== null)
        {
            var answerNode = $($("<div id='answer" + payload.question + "' style='display: none;'>").html(getTemplate("answerTemplate")));

            answerNode.find(".balloon").html(answer);

            answerNode = $(answerNode).appendTo(chatHistory);

            answerNode.slideDown(slideDownSpeed, function () {
                questionNode.find("div.back").css('visibility', 'visible');

                showQuestion(responseJSON);

                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
            });
        }
        else
        {
            showQuestion(responseJSON);
        }
    }).error(function (jqXHR, textStatus, errorThrown) {
        $("#questionReply").slideDown(slideDownSpeed);

        console.log('Error: ', jqXHR.responseText);
    });
}

function showQuestion(responseJSON, show) {
    if (show === undefined)
    {
        payload = responseJSON.payload;

        if (responseJSON.question === '')
        {
            question.hide();
            options.hide();
        }
        else
        {
            showQuestion(responseJSON, true);
        }
    }
    else
    {
        var questionNode = getTemplate("questionTemplate");

        question.html(questionNode);

        var typedCallback = function () {
            question.find(".balloon span.typed-cursor").hide();

            if (responseJSON.remarks !== null)
                showRemarks(responseJSON);
            else
                showOptions();
        }

        configureOptions(responseJSON);

        if (typeof (responseJSON.question) === 'string')
            responseJSON.question = [responseJSON.question];

        question.find(".balloon span").typed({ strings: responseJSON.question, startDelay: 300, typeSpeed: -50, backSpeed: -50, backDelay: 1500, callback: typedCallback });

        console.log('question:', payload.question);
        console.log('responseJSON:', responseJSON);
        console.log('payload:', JSON.stringify(payload));
    }
}

function showRemarks(responseJSON) {
    var remarksNode = getTemplate("remarksTemplate");

    remarks.html(remarksNode);

    remarks.find(".remarks .balloon div").html(responseJSON.remarks);

    remarks.find(".remarks").slideDown(slideDownSpeed * 3, function () {
        showQuestionAfterRemarks(responseJSON);
    });

}

function showQuestionAfterRemarks(responseJSON) {
    var questionNode = getTemplate("questionTemplate");

    questionAfterRemarks.html(questionNode);

    var typedCallback = function () {
        questionAfterRemarks.find(".balloon span.typed-cursor").hide();

        showOptions();
    }

    configureOptions(responseJSON);

    if (typeof (responseJSON.question) === 'string')
        responseJSON.question = [responseJSON.question];

    questionAfterRemarks.find(".balloon span").typed({ strings: responseJSON.showQuestionAfterRemarks, startDelay: 300, typeSpeed: -50, backSpeed: -50, backDelay: 1500, callback: typedCallback });
}

function configureOptions(responseJSON) {
    if (responseJSON.options === null)
    {
        var replyNode = getTemplate("replyInputTemplate");

        replyNode.find("input").keyup(function (e) {
            if (e.keyCode === 13)
                replyNode.find("#reply").click();
        });

        replyNode.find("#reply").click(function () {
            var name = $("#replyInput").val().trim();

            if (name.length > 0)
                answerQuestion(name);
        });

        reply.html(replyNode);
    }
    else
    {
        var replyNode = getTemplate("replyButtonsTemplate");

        for (r = 0; r < responseJSON.options.length; r++)
        {
            $("<button id='reply" + r + "'class='btn btn-default btn-options'>" + responseJSON.options[r] + "</button><span>&nbsp</span>").appendTo(replyNode);
        }

        replyNode.find('[id*="reply"]').click(function () {
            answerQuestion($(this).text());
        });

        reply.html(replyNode);
    }
}

function showOptions() {
    reply.slideDown(slideDownSpeed);

    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
}

function backQuestion(e) {
    var id;

    if (typeof (e) === 'object')
    {
        button = $(e);

        id = parseInt(button.attr('data-id'));

        var hideAndDelete = chatHistory.find("#question" + id).nextAll();
        hideAndDelete.push(chatHistory.find("#question" + id)[0]);

        hideAndDelete.slideUp(slideUpSpeed, function () { hideAndDelete.remove(); });

        reply.slideUp(slideUpSpeed, function () { backQuestion(id); });
    }
    else
    {
        id = e;

        var request = {
            "payload": payload,
            "reply": "",
            "back": id
        }

        console.log('Request:', request);

        $.ajax({
            type: 'GET',
            url: '/api/canada/expressEntry',
            cache: false,
            dataType: 'json',
            data: request
        }).success(function (data, textStatus, jqXHR) {
            var responseJSON = data.responseJSON;

            lastQuestion = responseJSON.question;
            payload = responseJSON.payload;

            showQuestion(responseJSON, true);
        }).error(function (jqXHR, textStatus, errorThrown) {
            chat.find("input").removeAttr('disabled');
            chat.find("button").removeAttr('disabled');

            console.log('Error: ', jqXHR.responseText);
        });
    }
}

function startOver() {
    chatHistory.html('');
    question.html('');

    lastQuestion = null;
    payload = {};

    answerQuestion(null);
}

function getTemplate(id) {
    return $($('<div>').append($("#" + id)[0].content.cloneNode(true)).html());
}

$(window).load(function () {
    answerQuestion(null);

    //showBootstrapClass();
});
/*
$(document).ready(function () {
    $(window).on('resize', function () {
        showBootstrapClass();
    });
});

function showBootstrapClass() {
    var winWidth = $(window).width();

    if (winWidth < 768)
    {
        document.title = 'Window Width: ' + winWidth + 'class used: col-xs';
    } else if (winWidth <= 991)
    {
        document.title = 'Window Width: ' + winWidth + 'class used: col-sm';
    } else if (winWidth <= 1199)
    {
        document.title = 'Window Width: ' + winWidth + 'class used: col-md';
    } else
    {
        document.title = 'Window Width: ' + winWidth + 'class used: col-lg';
    }
}
*/