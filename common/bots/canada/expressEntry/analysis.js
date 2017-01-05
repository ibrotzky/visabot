var calculator = require("./calculator");
var util = require("../../../util");

var currentParameters;
var currentScore;

function analyse(parameters, scores) {
    if (parameters.provincialNomination)
        return "<br /><br />Since you were nominated by a province or territory, you already have enough points to pass the next draw. Welcome to Canada! :)";

    currentParameters = parameters;
    currentScore = scores;

    var analysis = "<br /><br />";

    analysis += "<table class='analysis'>";
    analysis += "	<caption>Here is a 5-year plan with some ideas to improve your score</caption>";
    analysis += "	<thead>";
    analysis += "		<tr>";
    analysis += "			<th>Current score is: " + util.formatNumber(scores.total, 0) + "</th>";
    analysis += "			<th>This year</th>";
    analysis += "			<th>1 year</th>";
    analysis += "			<th>2 years</th>";
    analysis += "			<th>3 years</th>";
    analysis += "			<th>4 years</th>";
    analysis += "			<th>5 years</th>";
    analysis += "		</tr>";
    analysis += "	</thead>";

    analysis += "	<thead>";
    analysis += "		<tr>";
    analysis += "			<th colspan='7' class='section'>Keeping your current level on the first official language</th>";
    analysis += "		</tr>";
    analysis += "	</thead>";

    analysis += "	<thead>";
    analysis += "		<tr>";
    analysis += "			<th colspan='7'>Core/Human capital factors</th>";
    analysis += "		</tr>";
    analysis += "	</thead>";

    analysis += age(util.cloneObject(parameters));

    if (parameters.educationLevel < calculator.educationLevel.Secondary)
        analysis += educationHighSchool(util.cloneObject(parameters));

    if (parameters.educationLevel < calculator.educationLevel.OneYearDegree)
        analysis += educationOneYearDegree(util.cloneObject(parameters));

    if (parameters.educationLevel < calculator.educationLevel.TwoYearDegree)
        analysis += educationTwoYearDegree(util.cloneObject(parameters));

    if (parameters.educationLevel < calculator.educationLevel.BachelorsDegree)
        analysis += educationBachelorsDegree(util.cloneObject(parameters));

    if (parameters.educationLevel < calculator.educationLevel.TwoOrMoreDegress)
        analysis += educationTwoOrMoreDegress(util.cloneObject(parameters));

    analysis += canadianWorkExperience(util.cloneObject(parameters));

    if (parameters.spouseCommingAlong) {
        analysis += "	<thead>";
        analysis += "		<tr>";
        analysis += "			<th colspan='7'>Spouse factors</th>";
        analysis += "		</tr>";
        analysis += "	</thead>";

        if (parameters.spouseEducationLevel < calculator.educationLevel.Secondary)
            analysis += spouseEducationHighSchool(util.cloneObject(parameters));

        if (parameters.spouseEducationLevel < calculator.educationLevel.OneYearDegree)
            analysis += spouseEducationOneYearDegree(util.cloneObject(parameters));

        if (parameters.spouseEducationLevel < calculator.educationLevel.TwoYearDegree)
            analysis += spouseEducationTwoYearDegree(util.cloneObject(parameters));

        if (parameters.spouseEducationLevel < calculator.educationLevel.BachelorsDegree)
            analysis += spouseEducationBachelorsDegree(util.cloneObject(parameters));

        if (parameters.spouseEducationLevel < calculator.educationLevel.TwoOrMoreDegress)
            analysis += spouseEducationTwoOrMoreDegress(util.cloneObject(parameters));

        analysis += spouseFirstOfficialLanguage(util.cloneObject(parameters));

        analysis += spouseCanadianWorkExperience(util.cloneObject(parameters));
    }

    analysis += "	<thead>";
    analysis += "		<tr>";
    analysis += "			<th colspan='7'>Additional points</th>";
    analysis += "		</tr>";
    analysis += "	</thead>";

    analysis += studyInCanadaOneTwoYearDegree(util.cloneObject(parameters));

    analysis += studyInCanadaThreeOrMoreYearsDegree(util.cloneObject(parameters));

    analysis += "</table><br /><br />";

    analysis += "There some other things you could do to get more points, for instance:<br/>";
    analysis += "If you get a Job Offer in the NOC 00, you'll get 200 more points.<br/>";
    analysis += "If you get a Job Offer in the NOCs 0, A or B, you'll get 50 more points.<br/>";
    analysis += "If you get a Certificate of Qualification from a province or territory, you'll get 50 more points.<br/>";
    analysis += "If you get a Nomination Certificate from a province or territory, you'll get 600 more points.";

    return analysis;
}

function age(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Age</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function educationHighSchool(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: High School</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.Secondary;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function educationOneYearDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: One-year program</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.OneYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function educationTwoYearDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two-year program</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.TwoYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function educationBachelorsDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: Bachelor\'s degree</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function educationTwoOrMoreDegress(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two or more degrees (One is a Bachelor's)</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function canadianWorkExperience(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Canadian work experience</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.workInCanada += 1;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function spouseEducationHighSchool(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: High School</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.Secondary;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function spouseEducationOneYearDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: One-year program</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.OneYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function spouseEducationTwoYearDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two-year program</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.TwoYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function spouseEducationBachelorsDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: Bachelor\'s degree</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function spouseEducationTwoOrMoreDegress(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two or more degrees (One is a Bachelor's)</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function spouseFirstOfficialLanguage(parameters) {
    var simulation = "";

    if (parameters.spouseLanguage.test !== calculator.languageTest.none) {
        if (parameters.spouseLanguage.speaking < 7 ||
            parameters.spouseLanguage.listening < 7 ||
            parameters.spouseLanguage.reading < 7 ||
            parameters.spouseLanguage.writing < 7) {
            simulation += "	<tbody>";
            simulation += "		<tr>";
            simulation += "			<td>First Official Language to CLB 7</th>";

            for (i = 0; i <= 5; i++) {
                parameters.age = currentParameters.age + i;
                parameters.spouseLanguage.speaking = 7;
                parameters.spouseLanguage.listening = 7;
                parameters.spouseLanguage.reading = 7;
                parameters.spouseLanguage.writing = 7;

                calculator.calculate(parameters);

                simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
            }

            simulation += "	</tbody>";
        }

        if (parameters.spouseLanguage.speaking >= 7 && parameters.spouseLanguage.speaking < 9 ||
            parameters.spouseLanguage.listening >= 7 && parameters.spouseLanguage.listening < 9 ||
            parameters.spouseLanguage.reading >= 7 && parameters.spouseLanguage.reading < 9 ||
            parameters.spouseLanguage.writing >= 7 && parameters.spouseLanguage.writing < 9) {
            simulation += "	<tbody>";
            simulation += "		<tr>";
            simulation += "			<td>First Official Language to CLB 9</th>";

            for (i = 0; i <= 5; i++) {
                parameters.age = currentParameters.age + i;
                parameters.spouseLanguage.speaking = 9;
                parameters.spouseLanguage.listening = 9;
                parameters.spouseLanguage.reading = 9;
                parameters.spouseLanguage.writing = 9;

                calculator.calculate(parameters);

                simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
            }

            simulation += "	</tbody>";
        }
    }

    return simulation;
}

function spouseCanadianWorkExperience(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Canadian work experience</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseWorkInCanada += 1;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function studyInCanadaOneTwoYearDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Study in Canada One-year or two-year program</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseWorkInCanada += 1;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function studyInCanadaThreeOrMoreYearsDegree(parameters) {
    var simulation = "";

    simulation += "	<tbody>";
    simulation += "		<tr>";
    simulation += "			<td>Study in Canada Three or more years program</th>";

    for (i = 0; i <= 5; i++) {
        parameters.age = currentParameters.age + i;
        parameters.spouseWorkInCanada += 1;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    simulation += "	</tbody>";

    return simulation;
}

function scoreDifferente(newScore, currentScore) {
    var difference = " (";

    difference += (newScore > currentScore ? '+' : '');
    difference += newScore - currentScore;

    difference += ")";

    return difference;
}

module.exports = {
    analyse: analyse
};