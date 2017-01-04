var calculator = require("./calculator");


function analyse(parameters, score, principalApplicant) {
    if (principalApplicant === undefined)
        principalApplicant = true;



    var analysis = "<br /><br />Here are some things " + (principalApplicant ? "you" : "your spouse or common-law partner") + " could do to improve " + (principalApplicant ? "your" : "his/her") + " score. I'll break it down into the three categories for the Express Entry Proccess.";



    analysis += skilledWorker(parameters, score);
    analysis += skilledTrade(parameters, score);
    analysis += canadianExperience(parameters, score);

    //var analysisParameters = JSON.parse(JSON.stringify(parameters));

    return analysis;
}

function skilledWorker(parameters, score) {
    var analysis = "<br /><br />Skilled Worker:";

    return analysis;
}

function skilledTrade(parameters, score) {
    return "<br /><br />Skilled Trade";
}

function canadianExperience(parameters, score) {
    return "<br /><br />Canadian Experience Class";
}

module.exports = {
    analyse: analyse
};