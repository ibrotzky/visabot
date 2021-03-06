var calculator = require("./calculator");
var util = require("../../../util");

var currentParameters;
var currentScore;

var planSize = 3;

function analyse(parameters, scores, title) {
    var analysis = "";

    if (title === undefined)
    {
        currentParameters = util.cloneObject(parameters);
        currentScore = util.cloneObject(scores);

        analysis += "<br />";

        analysis += "<table class='analysis'>";
        analysis += "	<thead>";
        analysis += "		<tr>";
        analysis += "			<th>Current score is: " + util.formatNumber(scores.total, 0) + "</th>";
        analysis += "			<th>This year</th>";

        for (i = 1; i <= planSize; i++)
        {
            analysis += "			<th>" + i +" year" + (i === 1 ? "" : "s")  + "</th>";
        }

        analysis += "		</tr>";
        analysis += "	</thead>";

        analysis += analyse(parameters, currentScore, "Keeping your current level on the first official language");

        analysis += "</table><br />";

        analysis += "There some other things you could do to get more points, for instance:<br/><br />";
        analysis += "If you get a Job Offer in the NOC 00, you'll get 200 more points.<br/>";
        analysis += "If you get a Job Offer in the NOCs 0, A or B, you'll get 50 more points.<br/>";
        analysis += "If you get a Certificate of Qualification from a province or territory, you'll get 50 more points.<br/>";
        analysis += "If you get a Nomination Certificate from a province or territory, you'll get 600 more points.";
    }
    else
    {
        analysis += "		<tr>";
        analysis += "			<td  colspan='7' class='space'>";
        analysis += "		</tr>";

        analysis += "	<thead>";
        analysis += "		<tr>";
        analysis += "			<th colspan='7' class='section'>" + title + "</th>";
        analysis += "		</tr>";
        analysis += "	</thead>";

        analysis += "	<thead>";
        analysis += "		<tr>";
        analysis += "			<th colspan='7'>Core/Human capital factors</th>";
        analysis += "		</tr>";
        analysis += "	</thead>";
        analysis += "	<tbody>";

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

        analysis += secondOfficialLanguage(util.cloneObject(parameters));
        
        analysis += "	</tbody>";

        if (parameters.spouseCommingAlong)
        {
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

        if (parameters.firstLanguage.speaking < 9 ||
            parameters.firstLanguage.listening < 9 ||
            parameters.firstLanguage.reading < 9 ||
            parameters.firstLanguage.writing < 9)
        {
            analysis += "	<thead>";
            analysis += "		<tr>";
            analysis += "			<th colspan='7'>Skill transferability factors</th>";
            analysis += "		</tr>";
            analysis += "	</thead>";
            analysis += "		<tr>";
            analysis += "			<td colspan='7'>Education</th>";
            analysis += "	    </tr>";

            analysis += firstOfficialLanguage(util.cloneObject(parameters));
        }

        if (parameters.educationInCanada === undefined || parameters.educationInCanada < 2)
        {
            analysis += "	<thead>";
            analysis += "		<tr>";
            analysis += "			<th colspan='7'>Additional points</th>";
            analysis += "		</tr>";
            analysis += "	</thead>";

            if (parameters.educationInCanada === undefined || parameters.educationInCanada == 0)
                analysis += studyInCanadaOneTwoYearDegree(util.cloneObject(parameters));

            if (parameters.educationInCanada === undefined || parameters.educationInCanada == 1)
                analysis += studyInCanadaThreeOrMoreYearsDegree(util.cloneObject(parameters));
        }

        if (parameters.firstLanguage.speaking < 7 ||
            parameters.firstLanguage.listening < 7 ||
            parameters.firstLanguage.reading < 7 ||
            parameters.firstLanguage.writing < 7)
        {
            parameters.firstLanguage.speaking = 7;
            parameters.firstLanguage.listening = 7;
            parameters.firstLanguage.reading = 7;
            parameters.firstLanguage.writing = 7;

            analysis += analyse(parameters, currentScore, "Improving your first official language level to CLB 7");
        }

        if (parameters.firstLanguage.speaking >= 7 && parameters.firstLanguage.speaking < 9 ||
            parameters.firstLanguage.listening >= 7 && parameters.firstLanguage.listening < 9 ||
            parameters.firstLanguage.reading >= 7 && parameters.firstLanguage.reading < 9 ||
            parameters.firstLanguage.writing >= 7 && parameters.firstLanguage.writing < 9)
        {
            parameters.firstLanguage.speaking = 9;
            parameters.firstLanguage.listening = 9;
            parameters.firstLanguage.reading = 9;
            parameters.firstLanguage.writing = 9;

            analysis += analyse(parameters, currentScore, "Improving your first official language level to CLB 9");
        }
    }
    
    return analysis;
}

function age(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Age</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function educationHighSchool(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: High School</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.Secondary;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function educationOneYearDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: One-year program</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.OneYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function educationTwoYearDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two-year program</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.TwoYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function educationBachelorsDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: Bachelor\'s degree</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function educationTwoOrMoreDegress(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two or more degrees (One is a Bachelor's)</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.educationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function secondOfficialLanguage(parameters) {
    var simulation = "";

    if (parameters.secondLanguage.test === calculator.languageTest.none)
    {
        parameters.secondLanguage.test = calculator.languageTest.tef;
        parameters.secondLanguage.speaking = 0;
        parameters.secondLanguage.listening = 0;
        parameters.secondLanguage.reading = 0;
        parameters.secondLanguage.writing = 0;
    }

    if (parameters.secondLanguage.speaking < 5 ||
        parameters.secondLanguage.listening < 5 ||
        parameters.secondLanguage.reading < 5 ||
        parameters.secondLanguage.writing < 5)
    {
        simulation += "		<tr>";
        simulation += "			<td>Second Official Language to CLB 5 or 6</th>";

        for (i = 0; i <= planSize; i++)
        {
            parameters.age = currentParameters.age + i;
            parameters.secondLanguage.speaking = 5;
            parameters.secondLanguage.listening = 5;
            parameters.secondLanguage.reading = 5;
            parameters.secondLanguage.writing = 5;

            calculator.calculate(parameters);

            simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
        }
    }

    if (parameters.secondLanguage.speaking >= 5 && parameters.secondLanguage.speaking < 7 ||
        parameters.secondLanguage.listening >= 5 && parameters.secondLanguage.listening < 7 ||
        parameters.secondLanguage.reading >= 5 && parameters.secondLanguage.reading < 7 ||
        parameters.secondLanguage.writing >= 5 && parameters.secondLanguage.writing < 7)
    {
        simulation += "		<tr>";
        simulation += "			<td>Second Official Language to CLB 7 or 8</th>";

        for (i = 0; i <= planSize; i++)
        {
            parameters.age = currentParameters.age + i;
            parameters.secondLanguage.speaking = 7;
            parameters.secondLanguage.listening = 7;
            parameters.secondLanguage.reading = 7;
            parameters.secondLanguage.writing = 7;

            calculator.calculate(parameters);

            simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
        }
    }

    if (parameters.secondLanguage.speaking >= 7 && parameters.secondLanguage.speaking < 9 ||
        parameters.secondLanguage.listening >= 7 && parameters.secondLanguage.listening < 9 ||
        parameters.secondLanguage.reading >= 7 && parameters.secondLanguage.reading < 9 ||
        parameters.secondLanguage.writing >= 7 && parameters.secondLanguage.writing < 9)
    {
        simulation += "		<tr>";
        simulation += "			<td>Second Official Language to CLB 9</th>";

        for (i = 0; i <= planSize; i++)
        {
            parameters.age = currentParameters.age + i;
            parameters.secondLanguage.speaking = 9;
            parameters.secondLanguage.listening = 9;
            parameters.secondLanguage.reading = 9;
            parameters.secondLanguage.writing = 9;

            calculator.calculate(parameters);

            simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
        }
    }

    return simulation;
}

function canadianWorkExperience(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Canadian work experience</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.workInCanada += i;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function spouseEducationHighSchool(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: High School</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.Secondary;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function spouseEducationOneYearDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: One-year program</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.OneYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function spouseEducationTwoYearDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two-year program</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.TwoYearDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function spouseEducationBachelorsDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: Bachelor\'s degree</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function spouseEducationTwoOrMoreDegress(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Level of education: Two or more degrees (One is a Bachelor's)</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.spouseEducationLevel = calculator.educationLevel.BachelorsDegree;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function spouseFirstOfficialLanguage(parameters) {
    var simulation = "";

    if (parameters.spouseLanguage.test !== calculator.languageTest.none)
    {
        if (parameters.spouseLanguage.speaking < 7 ||
            parameters.spouseLanguage.listening < 7 ||
            parameters.spouseLanguage.reading < 7 ||
            parameters.spouseLanguage.writing < 7)
        {
            simulation += "		<tr>";
            simulation += "			<td>First Official Language to CLB 7</th>";

            for (i = 0; i <= planSize; i++)
            {
                parameters.age = currentParameters.age + i;
                parameters.spouseLanguage.speaking = 7;
                parameters.spouseLanguage.listening = 7;
                parameters.spouseLanguage.reading = 7;
                parameters.spouseLanguage.writing = 7;

                calculator.calculate(parameters);

                simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
            }
        }

        if (parameters.spouseLanguage.speaking >= 7 && parameters.spouseLanguage.speaking < 9 ||
            parameters.spouseLanguage.listening >= 7 && parameters.spouseLanguage.listening < 9 ||
            parameters.spouseLanguage.reading >= 7 && parameters.spouseLanguage.reading < 9 ||
            parameters.spouseLanguage.writing >= 7 && parameters.spouseLanguage.writing < 9)
        {
            simulation += "		<tr>";
            simulation += "			<td>First Official Language to CLB 9</th>";

            for (i = 0; i <= planSize; i++)
            {
                parameters.age = currentParameters.age + i;
                parameters.spouseLanguage.speaking = 9;
                parameters.spouseLanguage.listening = 9;
                parameters.spouseLanguage.reading = 9;
                parameters.spouseLanguage.writing = 9;

                calculator.calculate(parameters);

                simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
            }
        }
    }

    return simulation;
}

function spouseCanadianWorkExperience(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Canadian work experience</th>";

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;
        parameters.spouseWorkInCanada += i;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function studyInCanadaOneTwoYearDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Study in Canada One-year or two-year program</th>";

    parameters.educationInCanada = 1;

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function studyInCanadaThreeOrMoreYearsDegree(parameters) {
    var simulation = "";

    simulation += "		<tr>";
    simulation += "			<td>Study in Canada Three or more years program</th>";

    parameters.educationInCanada = 2;

    for (i = 0; i <= planSize; i++)
    {
        parameters.age = currentParameters.age + i;

        calculator.calculate(parameters);

        simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
    }

    return simulation;
}

function firstOfficialLanguage(parameters) {
    var simulation = "";

    if (parameters.firstLanguage.speaking < 7 ||
        parameters.firstLanguage.listening < 7 ||
        parameters.firstLanguage.reading < 7 ||
        parameters.firstLanguage.writing < 7)
    {
        simulation += "		<tr>";
        simulation += "			<td>First Official Language to CLB 7</th>";

        for (i = 0; i <= planSize; i++)
        {
            parameters.age = currentParameters.age + i;
            parameters.firstLanguage.speaking = 7;
            parameters.firstLanguage.listening = 7;
            parameters.firstLanguage.reading = 7;
            parameters.firstLanguage.writing = 7;

            calculator.calculate(parameters);

            simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
        }
    }

    if (parameters.firstLanguage.speaking >= 7 && parameters.firstLanguage.speaking < 9 ||
        parameters.firstLanguage.listening >= 7 && parameters.firstLanguage.listening < 9 ||
        parameters.firstLanguage.reading >= 7 && parameters.firstLanguage.reading < 9 ||
        parameters.firstLanguage.writing >= 7 && parameters.firstLanguage.writing < 9)
    {
        simulation += "		<tr>";
        simulation += "			<td>First Official Language to CLB 9</th>";

        for (i = 0; i <= planSize; i++)
        {
            parameters.age = currentParameters.age + i;
            parameters.firstLanguage.speaking = 9;
            parameters.firstLanguage.listening = 9;
            parameters.firstLanguage.reading = 9;
            parameters.firstLanguage.writing = 9;

            calculator.calculate(parameters);

            simulation += "			<td class='score'>" + util.formatNumber(calculator.scores.total) + scoreDifferente(calculator.scores.total, currentScore.total) + "</td>";
        }
    }

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