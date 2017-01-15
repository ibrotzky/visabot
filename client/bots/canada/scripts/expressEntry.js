var chat;
var chatHistory;
var question;
var remarks;
var questionAfterRemarks;
var reply;

var lastQuestion = null;

var payload = {};

var slideDownSpeed = 400;
var slideUpSpeed = 400;

//payload = {"question":44,"name":"Bruno","married":true,"spouseCanadianCitizen":false,"spouseCommingAlong":true,"age":27,"educationLevel":4,"canadianDegreeDiplomaCertificate":false,"firstLanguageTest":2,"firstLanguageSpeaking":9,"firstLanguageListening":9,"firstLanguageReading":9,"firstLanguageWriting":9,"secondLanguageTest":"0","workExperienceInCanada":"0","workExperienceLastTenYears":3,"certificateQualificationProvince":false,"validJobOffer":false, "nocJobOffer": undefined,"nominationCertificate":true,"spouseAge":42,"spouseEducationLevel":4,"spouseCanadianDegreeDiplomaCertificate":false,"spouseFirstLanguageTest":2,"spouseFirstLanguageSpeaking":9,"spouseFirstLanguageListening":9,"spouseFirstLanguageReading":9,"spouseFirstLanguageWriting":9,"spouseSecondLanguageTest":"0","spouseWorkExperienceInCanada":"0","spouseWorkExperienceLastTenYears":3,"spouseCertificateQualificationProvince":false,"spouseValidJobOffer":false,"spouseNominationCertificate":false};

if (window.location.href.indexOf('?') > 0)
{
    payload = {
        "question": 44, "name": "Bruno", "married": true, "spouseCanadianCitizen": false, "spouseCommingAlong": true, "age": 33, "educationLevel": 4, "canadianDegreeDiplomaCertificate": false, "firstLanguageTest": 2, "firstLanguageSpeaking": 6, "firstLanguageListening": 9, "firstLanguageReading": 9, "firstLanguageWriting": 9, "secondLanguageTest": "0", "workExperienceInCanada": "0", "workExperienceLastTenYears": 3, "certificateQualificationProvince": false, "validJobOffer": false, "nocJobOffer": undefined, "nominationCertificate": false,
        "spouseAge": 42, "spouseEducationLevel": 4, "spouseCanadianDegreeDiplomaCertificate": false, "spouseFirstLanguageTest": 2, "spouseFirstLanguageSpeaking": 9, "spouseFirstLanguageListening": 9, "spouseFirstLanguageReading": 9, "spouseFirstLanguageWriting": 9, "spouseSecondLanguageTest": "0", "spouseWorkExperienceInCanada": "0", "spouseWorkExperienceLastTenYears": 3, "spouseCertificateQualificationProvince": false, "spouseValidJobOffer": false, "spouseNominationCertificate": false
    };

    payload = {"question":4,"name":"asd","married":false};

    //payload = { "question": 26, "name": "Bruno", "married": true, "spouseCanadianCitizen": false, "spouseCommingAlong": true, "age": 33, "educationLevel": 4, "canadianDegreeDiplomaCertificate": false, "firstLanguageTest": "0", "firstLanguageSpeaking": 12, "firstLanguageListening": 12, "firstLanguageReading": 12, "firstLanguageWriting": 12, "workExperienceInCanada": "0", "workExperienceLastTenYears": 3, "certificateQualificationProvince": false, "validJobOffer": false, "nominationCertificate": false, "spouseAge": 42, "spouseEducationLevel": 4 };

    //payload = {"question":43,"name":"Douglas Miguel","married":true,"spouseCanadianCitizen":false,"spouseCommingAlong":true,"age":30,"educationLevel":4,"canadianDegreeDiplomaCertificate":false,"firstLanguageTest":"0","firstLanguageSpeaking":9,"firstLanguageListening":10,"firstLanguageReading":11,"firstLanguageWriting":8,"workExperienceInCanada":"0","workExperienceLastTenYears":3,"certificateQualificationProvince":false,"validJobOffer":false,"nominationCertificate":false,"spouseAge":30,"spouseEducationLevel":1,"spouseCanadianDegreeDiplomaCertificate":false,"spouseFirstLanguageTest":2,"spouseFirstLanguageSpeaking":10,"spouseFirstLanguageListening":10,"spouseFirstLanguageReading":10,"spouseFirstLanguageWriting":10,"spouseSecondLanguageTest":1,"spouseSecondLanguageSpeaking":10,"spouseSecondLanguageListening":10,"spouseSecondLanguageReading":10,"spouseSecondLanguageWriting":10,"spouseWorkExperienceInCanada":"0","spouseWorkExperienceLastTenYears":3,"spouseCertificateQualificationProvince":false,"spouseValidJobOffer":false};

    //payload = { "question": 44, "name": "Richard", "married": true, "spouseCanadianCitizen": false, "spouseCommingAlong": true, "age": 24, "educationLevel": 3, "canadianDegreeDiplomaCertificate": false, "firstLanguageTest": 2, "firstLanguageSpeaking": 9, "firstLanguageListening": 10, "firstLanguageReading": 10, "firstLanguageWriting": 10, "secondLanguageTest": "0", "workExperienceInCanada": "0", "workExperienceLastTenYears": 3, "certificateQualificationProvince": false, "validJobOffer": false, "nominationCertificate": false, "spouseAge": 24, "spouseEducationLevel": 1, "spouseCanadianDegreeDiplomaCertificate": false, "spouseFirstLanguageTest": "0", "spouseWorkExperienceInCanada": "0", "spouseWorkExperienceLastTenYears": "0", "spouseCertificateQualificationProvince": false, "spouseValidJobOffer": false, "spouseNominationCertificate": false };

    //payload = { "question": 44, "name": "Ilya", "married": true, "spouseCanadianCitizen": true, "age": 20, "educationLevel": 3, "canadianDegreeDiplomaCertificate": true, "canadianEducationLevel": 2, "firstLanguageTest": 3, "firstLanguageSpeaking": 10, "firstLanguageListening": 10, "firstLanguageReading": 10, "firstLanguageWriting": 10, "secondLanguageTest": 2, "secondLanguageSpeaking": 10, "secondLanguageListening": 10, "secondLanguageReading": 10, "secondLanguageWriting": 10, "workExperienceInCanada": 5, "workExperienceLastTenYears": 3, "certificateQualificationProvince": true, "validJobOffer": true, "nocJobOffer": "0", "nominationCertificate": true };

    //chat.remove("#chatHistory");
    //chat.remove("#question");
}

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
            $("#question .balloon span.typed-cursor").hide();

            if (responseJSON.remarks !== null)
                $("#question div#remarks").slideDown(slideDownSpeed, function () {
                    $("#question div#questionAfterRemarks").show();

                    typewriter("#question div#questionAfterRemarks #questionText", responseJSON.questionAfterRemarks, function () { showOptions(); });
                });
            else
                showOptions();
        }

        if (responseJSON.options === null)
        {
            var replyNode = getTemplate("replyInputTemplate");

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

        if (typeof(responseJSON.question) === 'string')
            responseJSON.question = [responseJSON.question];

        $("#question .balloon span").typed({strings: responseJSON.question, callback: typedCallback});
        
        console.log('question:', payload.question);
        console.log('responseJSON:', responseJSON);
        console.log('payload:', JSON.stringify(payload));
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

function getTemplate(id){
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