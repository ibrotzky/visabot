var chat;
var chatHistory;
var question;

var lastQuestion = null;

var payload = {};

var slideDownSpeed = 400;
var slideUpSpeed = 400;

var userInitials;

var questionTemplate = '' +
    '<table class="question">' +
    '   <tr>' +
    '       <td class="circle">' +
    '           <div class="circleBot">' +
    '               <div class="bot">CB</div>' +
    '           </div>' +
    '       </td>' +
    '       <td id="question"></td>' +
    '   </tr>' +
    '   <tr>' +
    '       <td></td>' +
    '       <td id="options"></td>' +
    '   </tr>' +
    '</table>';

var answerTemplate = '' +
    '<div id="answer">'+
    '   <table class="answer">' +
    '       <tr>' +
    '           <td>' +
    '               <div class="answer"></div>' +
    '           </td>' +
    '           <td class="circle">' +
    '               <div class="circleUser">' +
    '                   <div class="user"></div>' +
    '               </div>' +
    '           </td>' +
    '       </tr>' +
    '       <tr>' +
    '           <td class="back">' +
    '               <button class="back" onclick="backQuestion">back to this question</button>' +
    '           </td>' +
    '       </tr>' +
    '   </table>' +
    '</div>';

//payload = {"question":44,"name":"Bruno","married":true,"spouseCanadianCitizen":false,"spouseCommingAlong":true,"age":27,"educationLevel":4,"canadianDegreeDiplomaCertificate":false,"firstLanguageTest":2,"firstLanguageSpeaking":9,"firstLanguageListening":9,"firstLanguageReading":9,"firstLanguageWriting":9,"secondLanguageTest":"0","workExperienceInCanada":"0","workExperienceLastTenYears":3,"certificateQualificationProvince":false,"validJobOffer":false, "nocJobOffer": undefined,"nominationCertificate":true,"spouseAge":42,"spouseEducationLevel":4,"spouseCanadianDegreeDiplomaCertificate":false,"spouseFirstLanguageTest":2,"spouseFirstLanguageSpeaking":9,"spouseFirstLanguageListening":9,"spouseFirstLanguageReading":9,"spouseFirstLanguageWriting":9,"spouseSecondLanguageTest":"0","spouseWorkExperienceInCanada":"0","spouseWorkExperienceLastTenYears":3,"spouseCertificateQualificationProvince":false,"spouseValidJobOffer":false,"spouseNominationCertificate":false};

if (window.location.href.indexOf('?') > 0) {
    payload = {
        "question": 44, "name": "Bruno", "married": true, "spouseCanadianCitizen": false, "spouseCommingAlong": true, "age": 33, "educationLevel": 4, "canadianDegreeDiplomaCertificate": false, "firstLanguageTest": 2, "firstLanguageSpeaking": 6, "firstLanguageListening": 9, "firstLanguageReading": 9, "firstLanguageWriting": 9, "secondLanguageTest": "0", "workExperienceInCanada": "0", "workExperienceLastTenYears": 3, "certificateQualificationProvince": false, "validJobOffer": false, "nocJobOffer": undefined, "nominationCertificate": false,
        "spouseAge": 42, "spouseEducationLevel": 4, "spouseCanadianDegreeDiplomaCertificate": false, "spouseFirstLanguageTest": 2, "spouseFirstLanguageSpeaking": 9, "spouseFirstLanguageListening": 9, "spouseFirstLanguageReading": 9, "spouseFirstLanguageWriting": 9, "spouseSecondLanguageTest": "0", "spouseWorkExperienceInCanada": "0", "spouseWorkExperienceLastTenYears": 3, "spouseCertificateQualificationProvince": false, "spouseValidJobOffer": false, "spouseNominationCertificate": false
    };

    //payload = { "question": 26, "name": "Bruno", "married": true, "spouseCanadianCitizen": false, "spouseCommingAlong": true, "age": 33, "educationLevel": 4, "canadianDegreeDiplomaCertificate": false, "firstLanguageTest": "0", "firstLanguageSpeaking": 12, "firstLanguageListening": 12, "firstLanguageReading": 12, "firstLanguageWriting": 12, "workExperienceInCanada": "0", "workExperienceLastTenYears": 3, "certificateQualificationProvince": false, "validJobOffer": false, "nominationCertificate": false, "spouseAge": 42, "spouseEducationLevel": 4 };

    //payload = {"question":43,"name":"Douglas Miguel","married":true,"spouseCanadianCitizen":false,"spouseCommingAlong":true,"age":30,"educationLevel":4,"canadianDegreeDiplomaCertificate":false,"firstLanguageTest":"0","firstLanguageSpeaking":9,"firstLanguageListening":10,"firstLanguageReading":11,"firstLanguageWriting":8,"workExperienceInCanada":"0","workExperienceLastTenYears":3,"certificateQualificationProvince":false,"validJobOffer":false,"nominationCertificate":false,"spouseAge":30,"spouseEducationLevel":1,"spouseCanadianDegreeDiplomaCertificate":false,"spouseFirstLanguageTest":2,"spouseFirstLanguageSpeaking":10,"spouseFirstLanguageListening":10,"spouseFirstLanguageReading":10,"spouseFirstLanguageWriting":10,"spouseSecondLanguageTest":1,"spouseSecondLanguageSpeaking":10,"spouseSecondLanguageListening":10,"spouseSecondLanguageReading":10,"spouseSecondLanguageWriting":10,"spouseWorkExperienceInCanada":"0","spouseWorkExperienceLastTenYears":3,"spouseCertificateQualificationProvince":false,"spouseValidJobOffer":false};

    //payload = { "question": 44, "name": "Richard", "married": true, "spouseCanadianCitizen": false, "spouseCommingAlong": true, "age": 24, "educationLevel": 3, "canadianDegreeDiplomaCertificate": false, "firstLanguageTest": 2, "firstLanguageSpeaking": 9, "firstLanguageListening": 10, "firstLanguageReading": 10, "firstLanguageWriting": 10, "secondLanguageTest": "0", "workExperienceInCanada": "0", "workExperienceLastTenYears": 3, "certificateQualificationProvince": false, "validJobOffer": false, "nominationCertificate": false, "spouseAge": 24, "spouseEducationLevel": 1, "spouseCanadianDegreeDiplomaCertificate": false, "spouseFirstLanguageTest": "0", "spouseWorkExperienceInCanada": "0", "spouseWorkExperienceLastTenYears": "0", "spouseCertificateQualificationProvince": false, "spouseValidJobOffer": false, "spouseNominationCertificate": false };

    //payload = { "question": 44, "name": "Ilya", "married": true, "spouseCanadianCitizen": true, "age": 20, "educationLevel": 3, "canadianDegreeDiplomaCertificate": true, "canadianEducationLevel": 2, "firstLanguageTest": 3, "firstLanguageSpeaking": 10, "firstLanguageListening": 10, "firstLanguageReading": 10, "firstLanguageWriting": 10, "secondLanguageTest": 2, "secondLanguageSpeaking": 10, "secondLanguageListening": 10, "secondLanguageReading": 10, "secondLanguageWriting": 10, "workExperienceInCanada": 5, "workExperienceLastTenYears": 3, "certificateQualificationProvince": true, "validJobOffer": true, "nocJobOffer": "0", "nominationCertificate": true };

    //chat.remove("#chatHistory");
    //chat.remove("#question");
}

function answerQuestion(answer, post) {
    if (post === undefined && $("#questionReply").length > 0)
        $("#questionReply").slideUp(slideUpSpeed, function () { answerQuestion(answer, true) });
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

        if (lastQuestion !== null) {
            var questionCell = $(questionTemplate);

            questionCell.find("#question").html('<div class="question"style="width: ' + ($("#question div.question").last().outerWidth() + 1) + 'px;">' + lastQuestion + '</div>');

            $(questionCell).appendTo(chatHistory);

            question.html('');

            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
        }

        lastQuestion = responseJSON.question;

        if (answer !== null) {
            var answerCell = $(answerTemplate);

            answerCell.attr('data-id', payload.question);
            answerCell.find(".user").html(responseJSON.payload.name.substring(0, 1));
            answerCell.find("div.answer").html(answer);

            $(answerCell).appendTo(chatHistory);

            $("div#answer").last().slideDown(slideDownSpeed, function () {
                showQuestion(responseJSON);

                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
            });
        }
        else {
            showQuestion(responseJSON);
        }
    }).error(function (jqXHR, textStatus, errorThrown) {
        $("#questionReply").slideDown(slideDownSpeed);

        console.log('Error: ', jqXHR.responseText);
    });
}

function showQuestion(responseJSON, show) {
    if (show === undefined) {
        payload = responseJSON.payload;

        if (responseJSON.question === '') {
            question.hide();
            options.hide();
        }
        else {
            if ($("#questionReply").length === 0)
                showQuestion(responseJSON, true);
            else
                $("#questionReply").slideUp(slideUpSpeed, showQuestion);
        }
    }
    else {
        var questionCell = $(questionTemplate);

        questionCell.find("#question").html('' +
            '           <div class="question">' +
            '               <div id="questionText"></div>' +
            '           </div>');

        var typewriterCallback = function () {
            $("#questionReply").slideDown(slideDownSpeed);

            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        }

        if (responseJSON.options === null) {
            var optionsContent = '' +
                '<div id="questionReply">' +
                '   <table class="answer">' +
                '       <tr>' +
                '           <td>' +
                '               <input id="replyInput" type="text" class="form-control"></input>' +
                '           </td>' +
                '           <td class="button">' +
                '               <button id="reply" class="btn btn-block">Reply</button>' +
                '           </td>' +
                '       </tr>' +
                '   </table>' +
                '</div>';

            questionCell.find("#options").html(optionsContent);

            question.html(questionCell);

            $("#reply").click(function () {
                var name = $("#replyInput").val().trim();

                if (name.length > 0)
                    answerQuestion(name);
            });

            typewriter("questionText", responseJSON.question, typewriterCallback);
        }
        else {
            var optionsContent = '<div id="questionReply">';

            for (r = 0; r < responseJSON.options.length; r++) {
                optionsContent += "<button id='reply" + r + "'class='btn btn-default'>" + responseJSON.options[r] + "</button><span>&nbsp</span>";
            }

            optionsContent += '</div>';

            questionCell.find("#options").html(optionsContent);

            question.html(questionCell);

            $('[id*="reply"]').click(function () {
                answerQuestion($(this).text());
            });

            typewriter("questionText", responseJSON.question, typewriterCallback);
        }

        console.log('question:', payload.question);
        console.log('responseJSON:', responseJSON);
        console.log('payload:', JSON.stringify(payload));
    }
}

function backQuestion(button) {
    var id = button.id.replace('back', '');

    $(button).attr('disabled', 'disabled');
    $(button).parent().nextAll().remove();
    $(button).parent().remove();

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

        payload = responseJSON.payload;

        if (responseJSON.question === '') {
            question.hide();
            options.hide();
        }
        else {
            question.html(responseJSON.question);
            options.html('<br />');

            if (responseJSON.options === null) {
                var row = $("<div class='row'></div>");
                var textbox = $("<div class='col-xs-6'><input id='replyInput' type='text' class='form-control'></input></div>");
                var button = $("<div class='col-xs-6'><button id='reply' class='btn btn-block'>Reply</button></div>");

                textbox.appendTo(row);
                button.appendTo(row);

                row.appendTo(options);

                $("#reply").click(function () {
                    var name = $("#replyInput").val().trim();

                    if (name.length > 0)
                        answerQuestion(name);
                });
            }
            else {
                for (r = 0; r < responseJSON.options.length; r++) {
                    var button = $("<button id='reply" + r + "'class='btn btn-default'>" + responseJSON.options[r] + "</button>");

                    button.appendTo(options);
                    $("<span>&nbsp</span>").appendTo(options);

                    $("#reply" + r).click(function () {
                        answerQuestion($(this).text());
                    });
                }
            }

            if (responseJSON.score === undefined)
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        }

        console.log('question:', payload.question);
        console.log('responseJSON:', responseJSON);
        console.log('payload:', JSON.stringify(payload));
    }).error(function (jqXHR, textStatus, errorThrown) {
        chat.find("input").removeAttr('disabled');
        chat.find("button").removeAttr('disabled');

        console.log('Error: ', jqXHR.responseText);
    });
}

$(window).load(function () {
    answerQuestion(null);
});

$(document).ready(function () {
    $(window).on('resize', function () {
        var winWidth = $(window).width();
        if (winWidth < 768) {
            console.log('Window Width: ' + winWidth + 'class used: col-xs');
        } else if (winWidth <= 991) {
            console.log('Window Width: ' + winWidth + 'class used: col-sm');
        } else if (winWidth <= 1199) {
            console.log('Window Width: ' + winWidth + 'class used: col-md');
        } else {
            console.log('Window Width: ' + winWidth + 'class used: col-lg');
        }
    });
});