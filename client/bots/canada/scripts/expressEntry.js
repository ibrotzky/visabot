var chat;
var chatHistory;
var question;
var remarks;
var questionAfterRemarks;
var reply;

var lastQuestion = null;
var lastRemarks = null;
var lastQuestionAfterRemarks = null;

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

    //console.log('Request:', request);

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

        if (lastRemarks !== null)
        {
            var remarksNode = $($("<div id='remarks" + payload.question + "'>").html(getTemplate("remarksTemplate")));

            remarksNode.find(".remarks").show();
            remarksNode.find(".remarks .balloon div").html(lastRemarks);

            $(remarksNode).appendTo(chatHistory);

            remarks.html('');

            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        }

        if (lastQuestionAfterRemarks !== null)
        {
            var questionAfterRemarksNode = $($("<div id='questionAfterRemarks" + payload.question + "'>").html(getTemplate("questionTemplate")));

            questionAfterRemarksNode.find(".balloon").html(lastQuestionAfterRemarks);

            $(questionAfterRemarksNode).appendTo(chatHistory);

            questionAfterRemarks.html('');

            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        }

        lastQuestion = responseJSON.question;
        lastRemarks = responseJSON.remarks;
        lastQuestionAfterRemarks = responseJSON.questionAfterRemarks;

        if (lastQuestion !== null && typeof (lastQuestion) === 'object')
            lastQuestion = lastQuestion[lastQuestion.length - 1];

        if (lastQuestionAfterRemarks !== null && typeof (lastQuestionAfterRemarks) === 'object')
            lastQuestionAfterRemarks = lastQuestionAfterRemarks[lastQuestionAfterRemarks.length - 1];

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
            reply.hide();
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

        typed(question.find(".balloon span"), responseJSON.question, typedCallback);

        //console.log('question:', payload.question);
        //console.log('responseJSON:', responseJSON);
        //console.log('payload:', JSON.stringify(payload));
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

        showOptions(false);
    }

    configureOptions(responseJSON);

    if (typeof (responseJSON.questionAfterRemarks) === 'string')
        responseJSON.questionAfterRemarks = [responseJSON.questionAfterRemarks];

    typed(questionAfterRemarks.find(".balloon span"), responseJSON.questionAfterRemarks, typedCallback);
}

function configureOptions(responseJSON, scrollDown) {
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

function showOptions(scrollDown) {
    if (scrollDown === undefined)
        scrollDown = true;
    
    reply.slideDown(slideDownSpeed);

    if (scrollDown)
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

        remarks.slideUp(slideUpSpeed, function () { remarks.html(''); remarks.show(); });
        questionAfterRemarks.slideUp(slideUpSpeed, function () { questionAfterRemarks.html(''); questionAfterRemarks.show(); });
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

        //console.log('Request:', request);

        $.ajax({
            type: 'GET',
            url: '/api/canada/expressEntry',
            cache: false,
            dataType: 'json',
            data: request
        }).success(function (data, textStatus, jqXHR) {
            var responseJSON = data.responseJSON;

            lastQuestion = responseJSON.question;
            lastRemarks = responseJSON.remarks;
            lastQuestionAfterRemarks = responseJSON.questionAfterRemarks;
            
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

function typed(object, text, callback)
{
    object.typed({ 
        strings: text, 
        startDelay: 300, 
        typeSpeed: -50, 
        backSpeed: -50, 
        backDelay: 2000, 
        callback: callback 
    });
}

function questionIndex(name)
{
    $.ajax({
        type: 'GET',
        url: '/api/canada/expressEntryQuestionIndex',
        cache: false,
        dataType: 'json',
        data: {name: name}
    }).success(function (data, textStatus, jqXHR) {
        console.log('Data: ', data);
    }).error(function (jqXHR, textStatus, errorThrown) {
        console.log('Error: ', jqXHR.responseText);
    });

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