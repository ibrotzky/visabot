var calculator = require("./calculator");

function analyse(parameters, score) {
    if (parameters.provincialNomination)
        return "<br /><br />Since you were nominated by a province or territory, you already have enough points to pass the next draw. Welcome to Canada! :)";
    
    var analysis = "<br /><br />Here are some things you could do to improve your score.<br /><br />";

    var federalSkilledWorker = validateFederalSkilledWorker(parameters);
    var skilledTrade = validateSkilledTrade(parameters);
    var canadianExperience = validateCanadianExperience(parameters);

    if (federalSkilledWorker && skilledTrade && canadianExperience)
    {
        analysis = "You are eligible to all three programs: Federal Skilled Worker, Skilled Trade and Canadian Experience.<br />"


    }

    /*
        console.log('federalSkilledWorker: ', federalSkilledWorker);
        console.log('skilledTrade: ', skilledTrade);
        console.log('canadianExperience: ', canadianExperience);
    */



    //analysis += skilledWorker(parameters, score);
    //analysis += skilledTrade(parameters, score);
    //analysis += canadianExperience(parameters, score);

    //var analysisParameters = JSON.parse(JSON.stringify(parameters));

    analysis = "";

    return analysis;
}

function validateFederalSkilledWorker(parameters) {
    if (parameters.workExperience < 1)
        return false;

    if (parameters.educationLevel < 1)
        return false;

    if (parameters.firstLanguage.speaking < 7 ||
        parameters.firstLanguage.listening < 7 ||
        parameters.firstLanguage.reading < 7 ||
        parameters.firstLanguage.writing < 7)
        return false;

    return true;
}

function federalSkilledWorker(parameters) {
    var analysis = "<br /><br />Skilled Worker:";

    return analysis;
}

function validateSkilledTrade(parameters) {
    if (parameters.workExperience < 2)
        return false;

    if (!parameters.certificateFromProvince)
        if (parameters.nocJobOffer === undefined)
            return false;
        else
            if (parameters.nocJobOffer > calculator.nocList.B)
                return false;

    if (parameters.firstLanguage.speaking < 5 ||
        parameters.firstLanguage.listening < 5 ||
        parameters.firstLanguage.reading < 4 ||
        parameters.firstLanguage.writing < 4)
        return false;

    return true;
}

function skilledTrade(parameters) {
    return "<br /><br />Skilled Trade";
}

function validateCanadianExperience(parameters) {
    if (parameters.workInCanada < 1)
        return false;

    if (parameters.firstLanguage.speaking < 4 ||
        parameters.firstLanguage.listening < 4 ||
        parameters.firstLanguage.reading < 4 ||
        parameters.firstLanguage.writing < 4)
        return false;

    return true;
}

function canadianExperience(parameters) {
    return "<br /><br />Canadian Experience Class";
}

module.exports = {
    analyse: analyse
};