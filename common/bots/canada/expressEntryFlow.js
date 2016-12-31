/**
 * Enum for Language Ability
 * @readonly
 * @enum {number}
 */
var languageAbility = {
	speaking: 0,
	listening: 1,
	reading: 2,
	writing: 3
}

/**
 * Create the object with the languague test properties
 */
function languageObject() {
	return {
		test: null,
		speaking: null,
		listening: null,
		reading: null,
		writing: null
	}
}

function yesNo() {
	return ["Yes", "No"];
}

function yesNoAnswer(reply) {
	return (reply === "Yes");
}

function answerIndex(question, reply) {
	return question.options().indexOf(reply);
}

/**
 * Questions to be asked
 * @readonly
 * @enum {string}
 */
var questions = {
	name: {
		id: null,
		question: function (payload) { return "Hello, nice to meet you.\nI'm CanadaBot. What is your name?" },
		options: function (payload) { return null },
		processReply: function (payload, reply) { payload.name = reply; },
		nextQuestion: function (payload) { return questions.married }
	},
	married: {
		id: null,
		question: function (payload) { return "Hi " + payload.name + ". Are you married or has a common-law partner?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.married = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.married ? questions.spouseCanadianCitizen : questions.age) },
	},
	spouseCanadianCitizen: {
		id: null,
		question: function (payload) { return "{QUOTE}Is your spouse or common-law partner a citizen or permanent resident of Canada?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseCanadianCitizen = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.spouseCanadianCitizen ? questions.age : questions.spouseCommingAlong) },
	},
	spouseCommingAlong: {
		id: null,
		question: function (payload) { return "{QUOTE}Will your spouse or common-law partner come with you to Canada?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseCommingAlong = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return questions.age },
	},
	age: {
		id: null,
		question: function (payload) { return "{QUOTE}How old are you?" },
		options: function (payload) { return ['17 or less', '18', '19', '20 to 29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45 or more'] },
		processReply: function (payload, reply) {
			switch (reply)
			{
				case '17 or less':
					payload.age = 17;
					break;

				case '20 to 29':
					payload.age = 20;
					break;

				case '45 or more':
					payload.age = 45;
					break;

				default:
					payload.age = parseInt(reply);
					break;
			}
		},
		nextQuestion: function (payload) { return questions.educationLevel },
	},
	educationLevel: {
		id: null,
		question: function (payload) { return "{QUOTE}What is your education level?" },
		options: function (payload) {
			return ['Less than high school',
				'High school',
				'One-year program',
				'Two-year program',
				'Bachelor\'s degree',
				'Two or more degrees',
				'Master\'s degree',
				'Ph.D.']
		},
		processReply: function (payload, reply) { payload.educationLevel = answerIndex(this, reply); },
		nextQuestion: function (payload) { return questions.canadianDegreeDiplomaCertificate },
	},
	canadianDegreeDiplomaCertificate: {
		id: null,
		question: function (payload) { return "{QUOTE}Have you earned a Canadian degree, diploma or certificate?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.canadianDegreeDiplomaCertificate = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.canadianDegreeDiplomaCertificate ? questions.canadianEducationLevel : questions.firstLanguageTest) },
	},
	canadianEducationLevel: {
		id: null,
		question: function (payload) { return "{QUOTE}What is your education level in Canada?" },
		options: function () {
			return ['High school or less',
				'One-year or two-year program',
				'Three or more years program'];
		},
		processReply: function (payload, reply) { payload.canadianEducationLevel = answerIndex(this, reply); },
		nextQuestion: function (payload) { return questions.firstLanguageTest },
	},
	firstLanguageTest: {
		id: null,
		question: function (payload) { return "{QUOTE}firstLanguageTest" },
		options: function () { return null; },
		processReply: function (payload, reply) { },
		nextQuestion: function (payload) { return null },
	},
}

var questionsArray = Object.keys(questions);

for (q = 0; q < questionsArray.length; q++)
{
	questions[questionsArray[q]].id = q;
}

function validateLanguageScore(test, ability, score) {
	if (isNaN(score))
		return false;
	else
		score = parseFloat(score);

	var scoreValid = true;

	switch (parseInt(test))
	{
		case languageTest.none:
			if (score < 0 || score > 10)
				scoreValid = false;
			break;

		case languageTest.celpip:
			if (score < 0 || score > 12)
				scoreValid = false;
			break;

		case languageTest.ielts:
			if (score < 1 || score > 9)
				scoreValid = false;
			break;

		case languageTest.tef:
			switch (ability)
			{
				case languageAbility.speaking:
					if (score < 0 || score > 450)
						scoreValid = false;
					break;

				case languageAbility.listening:
					if (score < 0 || score > 360)
						scoreValid = false;
					break;

				case languageAbility.reading:
					if (score < 0 || score > 300)
						scoreValid = false;
					break;

				case languageAbility.writing:
					if (score < 0 || score > 450)
						scoreValid = false;
					break;
			}
			break;
	}

	return scoreValid;
}

function validateAnswer(options, reply) {
	if (options === null)
		return true;
	else
		return (options.indexOf(reply) >= 0);
}

var questionFlow = function (payload, reply, callback) {
	//console.log('payload: ', payload);
	//console.log('reply: ', reply);

	if (payload === undefined) payload = null;

	var responseJSON = {
		"response": null, // what the bot will respond with (more is appended below)
		"continue": false, // denotes that Motion AI should hit this module again, rather than continue further in the flow
		"customPayload": null, // working data to examine in future calls to this function to keep track of state
		"quickReplies": null, // a JSON object containing suggested/quick replies to display to the user
		"cards": null // a cards JSON object to display a carousel to the user (see docs)
	};

	var question;

	if (payload !== null)
	{
		//console.log('payload.question: ', payload.question);
		question = questions[questionsArray[payload.question]];
		//console.log('question: ', question);

		//console.log('answerValid: ', validateAnswer(question.options(), reply));
		if (validateAnswer(question.options(), reply))
		{
			question.processReply(payload, reply);

			question = question.nextQuestion(payload);
		}
	}
	else
	{
		question = questions.name;
	}
	//console.log('nextQuestion: ', question);

	responseJSON.response = question.question(payload);
	responseJSON.quickReplies = question.options(payload);

	payload.question = question.id;

	responseJSON.response = responseJSON.response.replace('{QUOTE}', '');
	responseJSON.customPayload = payload;

	//console.log("responseJSON: ", responseJSON);
	return responseJSON;
}

module.exports = {
	questionFlow: questionFlow
};