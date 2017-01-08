var expressEntry = function (payload, reply, back, callback) {
    var flow = require('../bots/canada/expressEntry/flow');
	
    var responseJSON = flow.questionFlow(payload, reply, back);

    callback(null, responseJSON);
}

module.exports = function(canada) {
    canada.expressEntry = expressEntry;

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
};