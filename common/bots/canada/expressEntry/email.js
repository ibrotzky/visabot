var helper = require('sendgrid').mail;

var sg = require('sendgrid')(process.env.SENDGRID_API_KEY);

function sendEmail(to, remarks, analysis) {
    if (analysis === undefined || analysis === null)
        analysis = '';

    var from = new helper.Email('visabot@vanhack.com');
    var to = new helper.Email(to);
    var subject = 'Here are the details for your Express Entry Assessment';

    var content = '<style>' +
        '    table.scoreDetails' +
        '    {' +
        '        border: none;' +
        '        width: 440px;' +
        '    }' +
        '' +
        //'        table.scoreDetails, table.scoreDetails th, table.scoreDetails td' +
        //'        {' +
        //'            border: 1px solid #ffffff;' +
        //'        }' +
        //'' +
        '        table.scoreDetails th, table.scoreDetails td' +
        '        {' +
        '            border: 1px solid #ffffff;' +
        '            padding: 5px 10px;' +
        '        }' +
        '' +
        '        table.scoreDetails thead' +
        '        {' +
        '            background-color: #3f3f3f;' +
        '            color: #ffffff;' +
        '            padding: 5px 10px;' +
        '        }' +
        '' +
        '        table.scoreDetails tbody' +
        '        {' +
        '            background-color: #636363;' +
        '            color: #ffffff;' +
        '            padding: 5px 10px;' +
        '        }' +
        '' +
        '        table.scoreDetails .ident' +
        '        {' +
        '            background-color: #a8a8a8;' +
        '            color: #ffffff;' +
        '            padding: 5px 10px 5px 20px;' +
        '        }' +
        '' +
        '        table.scoreDetails .score' +
        '        {' +
        '            padding: 5px 20px;' +
        '            text-align: center;' +
        '            width: 60px;' +
        '        }' +
        '' +
        '' +
        '    table.analysis' +
        '    {' +
        '        border: 1px solid #ffffff;' +
        '        width: 600px;' +
        '    }' +
        '' +
        '        table.analysis, table.analysis th, table.analysis td' +
        '        {' +
        '            border: 1px solid #ffffff;' +
        '        }' +
        '' +
        '        table.analysis th, table.analysis td' +
        '        {' +
        '            border: 1px solid #ffffff;' +
        '            padding: 5px 10px;' +
        '        }' +
        '' +
        '        table.analysis thead' +
        '        {' +
        '            background-color: #3f3f3f;' +
        '            color: #ffffff;' +
        '            padding: 5px 10px;' +
        '        }' +
        '' +
        '        table.analysis tbody' +
        '        {' +
        '            background-color: #ffffff;' +
        '            padding: 5px 10px;' +
        '        }' +
        '' +
        '        table.analysis tbody td.space' +
        '        {' +
        '            background-color: #ffffff;' +
        '        }' +
        '' +
        '        table.analysis tbody tr:nth-child(even)' +
        '        {' +
        '            background-color: #a8a8a8;' +
        '            color: #ffffff;' +
        '        }' +
        '' +
        '        table.analysis tbody tr:nth-child(odd)' +
        '        {' +
        '            background-color: #636363;' +
        '            color: #ffffff;' +
        '        }' +
        '' +
        '        table.analysis .ident' +
        '        {' +
        '            background-color: #a8a8a8;' +
        '            color: #ffffff;' +
        '            padding: 5px 10px 5px 20px;' +
        '        }' +
        '' +
        '        table.analysis .score' +
        '        {' +
        '            padding: 5px 20px;' +
        '            text-align: center;' +
        '            width: 60px;' +
        '        }' +
        '</style>' +
        remarks + (analysis !== '' ? '<br /><br />' + analysis : '');

    var mail = new helper.Mail(from, subject, to, new helper.Content('text/html', content));

    var request = sg.emptyRequest({
        method: 'POST',
        path: '/v3/mail/send',
        body: mail.toJSON(),
    });

    sg.API(request, function (error, response) {
        if (response.statusCode != 202)
        {
            console.log('statusCode: ', response.statusCode);
            console.log('body: ', response.body);
            console.log('headers: ', response.headers);
        }
    });
}

module.exports = {
    sendEmail: sendEmail
}