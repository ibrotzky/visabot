var expressEntry = function (payload, reply, back, callback) {
    var flow = require('../bots/canada/expressEntry/flow');
	
    var responseJSON = flow.questionFlow(payload, reply, back);

    callback(null, responseJSON);
}

var expressEntryQuestionIndex = function(name, callback)
{
    var flow = require('../bots/canada/expressEntry/flow');

    var question = { id: flow.questions[name].id };

    callback(null, question);
}

module.exports = function(canada) {
    canada.expressEntry = expressEntry;
    canada.expressEntryQuestionIndex = expressEntryQuestionIndex;

    canada.remoteMethod(
        'expressEntry', {
            http: {
                path: '/expressEntry',
                verb: 'get'
            },
            accepts: [
                { arg: 'payload', type: 'object', required: false },
                { arg: 'reply', type: 'string', required: false },
                { arg: 'back', type: 'number', required: false }
            ],
            returns: { arg: 'responseJSON', type: 'object'}
        }
    );

    canada.remoteMethod(
        'expressEntryQuestionIndex', {
            http: {
                path: '/expressEntryQuestionIndex',
                verb: 'get'
            },
            accepts: [
                { arg: 'name', type: 'string', required: true }
            ],
            returns: { arg: 'question', type: 'object'}
        }
    );
};