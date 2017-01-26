var util = require("../../../util");

var calculator = require("./calculator");
var analysis = require("./analysis");

var db = require("./db");
var email = require("./email");

var nextQuote;

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
 * Enum for Reply Type
 * @readonly
 * @enum {number}
 */
var replyType = {
	name: 0,
	email: 1
}

function ageOptions(payload) {
	return ['Ask mom', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', 'Be polite and don\'t ask'];
}

function answerIndex(question, payload, reply) {
	var options = question.options(payload);

	return (options === null ? null : question.options(payload).indexOf(reply));
}

function canadianEducationLevelOptions(payload) {
	return ['High school or less',
		'One-year or two-year program',
		'Three or more years program'];
}

function educationLevelOptions(payload) {
	return ['Less than high school',
		'High school',
		'One-year program',
		'Two-year program',
		'Bachelor\'s degree',
		'Two or more degrees',
		'Master\'s degree',
		'Ph.D.'];
}

function languageQuestion(test, testQuestion, payload, ability, principalApplicant) {
	var questionText = [];

	var abilityName;
	var testName;
	var testSectionName;

	if (principalApplicant === undefined)
		principalApplicant = true;

	if (parseInt(test) === answerIndex(testQuestion, payload, 'TEF'))
		switch (ability)
		{
			case languageAbility.speaking:
				questionText.push("{QUOTE_FRENCH}Quel est" + (principalApplicant ? " votre score " : " le score de votre époux ou conjoint de fait ") + "sur l’épreuve d'expression orale?");
				break;

			case languageAbility.listening:
				questionText.push("{QUOTE_FRENCH}Quel est" + (principalApplicant ? " votre score " : " le score de votre époux ou conjoint de fait ") + "sur l’épreuve de compréhension orale?");
				break;

			case languageAbility.reading:
				questionText.push("{QUOTE_FRENCH}Quel est" + (principalApplicant ? " votre score " : " le score de votre époux ou conjoint de fait ") + "sur l’épreuve de compréhension écrite?");
				break;

			case languageAbility.writing:
				questionText.push("{QUOTE_FRENCH}Quel est" + (principalApplicant ? " votre score " : " le score de votre époux ou conjoint de fait ") + "sur l’épreuve d'expression écrite?");
				break;
		}
	else
	{
		switch (ability)
		{
			case languageAbility.speaking:
				abilityName = 'speak';
				testSectionName = 'speaking';
				break;

			case languageAbility.listening:
				abilityName = 'listen to';
				testSectionName = 'listening';
				break;

			case languageAbility.reading:
				abilityName = 'read';
				testSectionName = 'reading';
				break;

			case languageAbility.writing:
				abilityName = 'write';
				testSectionName = 'writing';
				break;
		}

		switch (parseInt(test))
		{
			case answerIndex(testQuestion, payload, 'CELPIP'):
				testName = "CELPIP";

				questionText.push("{QUOTE}What is your" + (principalApplicant ? " " : " spouse or common-law partner's ") + testSectionName + " score on the " + testName + " test?");
				break;

			case answerIndex(testQuestion, payload, 'IELTS'):
				testName = "IELTS";

				questionText.push("{QUOTE}What is your" + (principalApplicant ? " " : " spouse or common-law partner's ") + testSectionName + " score on the " + testName + " test?");
				break;

			default:
				switch (ability)
				{
					case languageAbility.speaking:
						if (principalApplicant)
						{
							questionText.push("Hmm... I am sorry to tell you this but in order to be eligible to Express Entry you must take an English or French language test first, ok?");
							questionText.push("But since we are here, let's make a simulation about your language skills, shall we?");
						}
						else
						{
							questionText.push("Hmm... I am sorry to tell you this but in order to benefit from the points of your spouse or common-law partner language test, it had to be taken within the last two years, ok?");
							questionText.push("But since we are here, let's make a simulation about your spouse or common-law partner language skills, shall we?");
						}

						questionText.push("How well do you think you" + (principalApplicant ? " " : "r spouse or common-law partner ") + "can speak English or French?");
						break;

					case languageAbility.listening:
						questionText.push("And what about your" + (principalApplicant ? " " : " spouse or common-law partner ") + "listening skills in English or French?");
						break;

					case languageAbility.reading:
						questionText.push("About your" + (principalApplicant ? " " : " spouse or common-law partner ") + "reading skills in English or French, how good are they?");
						break;

					case languageAbility.writing:
						questionText.push("Ok. How would you rate your" + (principalApplicant ? " " : " spouse or common-law partner ") + "writting skills in English or French?");
						break;
				}
		}
	}

	return questionText;
}

function languageOptions(test, testQuestion, payload, ability, principalApplicant) {
	/*
		console.log('answer: ', test);
		console.log('No: ', answerIndex(testQuestion, payload, 'No'));
		console.log('No, and won\'t: ', answerIndex(testQuestion, payload, 'No, and won\'t'));
		console.log('No, but will: ', answerIndex(testQuestion, payload, 'No, but will'));
		console.log('CELPIP: ', answerIndex(testQuestion, payload, 'CELPIP'));
		console.log('IELTS: ', answerIndex(testQuestion, payload, 'IELTS'));
		console.log('TEF: ', answerIndex(testQuestion, payload, 'TEF'));
	*/
	if (principalApplicant === undefined)
		principalApplicant = true;

	switch (parseInt(test))
	{
		case answerIndex(testQuestion, payload, 'No'):
		case answerIndex(testQuestion, payload, 'No, but will'):
			return ["0%", "10%", "20%", "30%", "40%", "50%", "60%", "70%", "80%", "90%", "100%"];
			break;

		case answerIndex(testQuestion, payload, 'CELPIP'):
			return ["M, 0 - 3", "4", "5", "6", "7", "8", "9", "10 - 12"];
			break;

		case answerIndex(testQuestion, payload, 'IELTS'):
			if (principalApplicant)
				return ["0 - 3.5", "4 - 4.5", "5", "5.5", "6", "6.5", "7", "7.5 - 9"];
			else
				switch (ability)
				{
					case languageAbility.speaking:
					case languageAbility.writing:
						return ["0 - 3.5", "4 - 4.5", "5", "5.5", "6", "6.5", "7", "7.5 - 9"];
						break;

					case languageAbility.listening:
						return ["0 - 4", "4.5", "5", "5.5", "6 - 7", "7.5", "8", "8.5 - 9"];
						break;

					case languageAbility.reading:
						return ["0 - 3.5", "4 - 4.5", "5", "5.5", "6", "6.5", "7", "7.5 - 9"];
						break;
				}
			break;

		case answerIndex(testQuestion, payload, 'TEF'):
			switch (ability)
			{
				case languageAbility.speaking:
				case languageAbility.writing:
					return ["0 - 180", "181 - 225", "226 - 270", "271 - 309", "310 - 348", "349 - 370", "371 - 392", "393 - 450"];
					break;

				case languageAbility.listening:
					return ["0 - 144", "145 - 180", "181 - 216", "217 - 248", "249 - 279", "280 - 297", "298 - 315", "316 - 360"];
					break;

				case languageAbility.reading:
					return ["0 - 120", "121 - 150", "151 - 180", "181 - 206", "207 - 232", "233 - 247", "248 - 262", "263 - 300"];
					break;
			}
			break;
	}
}

function nocJobOfferOptions(payload) {
	return ["00",
		'0',
		'A',
		'B',
		'C',
		'D'];
}

function workExperienceInCanadaOptions(payload) {
	return ["None or less than a year",
		"1 year",
		"2 years",
		"3 years",
		"4 years",
		"5 years or more"];
}

function workExperienceLastTenYearsOptions(payload) {
	return ["None or less than a year",
		"1 year",
		"2 years",
		"3 years",
		"4 years",
		"5 years",
		"Too many to count"];
}

function yesNo() {
	return ["Yes", "No"];
}

function yesNoAnswer(reply) {
	return (reply === "Yes");
}

/**
 * Questions to be asked
 * @readonly
 * @enum {string}
 */
var questions = {
	name: {
		id: null,
		question: function (payload) { return "Hello, nice to meet you.\nI'm Yukan, the Canada Bot. What is your name?" },
		options: function (payload) { return null },
		replyType: { type: replyType.name },
		processReply: function (payload, reply) { payload.name = reply; },
		nextQuestion: function (payload) { return questions.age }
	},
	age: {
		id: null,
		question: function (payload) { return "Hi " + payload.name + ". How old are you?" },
		options: ageOptions,
		processReply: function (payload, reply) { payload.age = answerIndex(this, payload, reply) + 17; },
		nextQuestion: function (payload) { return questions.educationLevel },
	},
	educationLevel: {
		id: null,
		question: function (payload) {
			var questionText = [];

			if (parseInt(payload.age) <= 17)
			{
				questionText.push("Hummmm! I'm telling your parents!");
				questionText.push("Just Kidding...");
			}
			else if (parseInt(payload.age) >= 18 && parseInt(payload.age) <= 25)
			{
				questionText.push("Those college years! So many memories...");
			}
			else if (parseInt(payload.age) >= 26 && parseInt(payload.age) <= 29)
			{
				questionText.push("The prime of your life, good for you...");
			}
			else if (parseInt(payload.age) >= 30 && parseInt(payload.age) <= 46)
			{
				questionText.push("Great! Hope you don't mind, I'm telling everyone!");
				questionText.push("Just kidding...");
			}

			questionText.push("What is your education level?");

			return questionText;


			//return ["Great! Hope you don't mind, I'm telling everyone!", "Just Kidding...", "What is your education level?"] 
		},
		options: educationLevelOptions,
		processReply: function (payload, reply) { payload.educationLevel = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.canadianDegreeDiplomaCertificate },
	},
	canadianDegreeDiplomaCertificate: {
		id: null,
		question: function (payload) { return "Since we are talking about education, have you earned a Canadian degree, diploma or certificate?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.canadianDegreeDiplomaCertificate = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.canadianDegreeDiplomaCertificate ? questions.canadianEducationLevel : questions.firstLanguageTest) },
	},
	canadianEducationLevel: {
		id: null,
		question: function (payload) { return "Cool! What is your education level in Canada?" },
		options: canadianEducationLevelOptions,
		processReply: function (payload, reply) { payload.canadianEducationLevel = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.firstLanguageTest },
	},
	firstLanguageTest: {
		id: null,
		question: function (payload) {
			var questionText = [];

			if (!util.parseBoolean(payload.canadianEducationLevel))
				questionText.push("All right, let's move on then.");

			questionText.push("Did you take a language test over the last 2 years?");

			return questionText;
		},
		options: function (payload) {
			return ['No',
				'CELPIP',
				'IELTS',
				'TEF'];
		},
		processReply: function (payload, reply) { payload.firstLanguageTest = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.firstLanguageSpeaking },
	},
	firstLanguageSpeaking: {
		id: null,
		question: function (payload) { return languageQuestion(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.speaking); },
		options: function (payload) { return languageOptions(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.speaking); },
		processReply: function (payload, reply) { payload.firstLanguageSpeaking = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.firstLanguageListening },
	},
	firstLanguageListening: {
		id: null,
		question: function (payload) { return languageQuestion(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.listening); },
		options: function (payload) { return languageOptions(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.listening); },
		processReply: function (payload, reply) { payload.firstLanguageListening = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.firstLanguageReading },
	},
	firstLanguageReading: {
		id: null,
		question: function (payload) { return languageQuestion(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.reading); },
		options: function (payload) { return languageOptions(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.reading); },
		processReply: function (payload, reply) { payload.firstLanguageReading = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.firstLanguageWriting },
	},
	firstLanguageWriting: {
		id: null,
		question: function (payload) { return languageQuestion(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.writing); },
		options: function (payload) { return languageOptions(payload.firstLanguageTest, questions.firstLanguageTest, payload, languageAbility.writing); },
		processReply: function (payload, reply) { payload.firstLanguageWriting = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return (payload.firstLanguageTest == 0 ? questions.workExperienceLastTenYears : questions.secondLanguageTest) },
	},
	secondLanguageTest: {
		id: null,
		question: function (payload) {
			var questionText = [];

			if (parseInt(payload.firstLanguageSpeaking) >= 9 &&
				parseInt(payload.firstLanguageListening) >= 9 &&
				parseInt(payload.firstLanguageReading) >= 9 &&
				parseInt(payload.firstLanguageWriting) >= 9)
			{
				var languageQuestion = questions['firstLanguageTest'];

				switch (parseInt(payload.firstLanguageTest))
				{
					case answerIndex(languageQuestion, payload, 'CELPIP'):
					case answerIndex(languageQuestion, payload, 'IELTS'):
						questionText.push("You are a Canadian already, eh?");
						questionText.push("Amazing score!");
						questionText.push("Wow! You must be proud!");
						questionText.push("Did you know that even some native speakers can't score that high? Congratulations!");
						questionText.push("{QUOTE}Did you take a second language test?");
						break;

					case answerIndex(languageQuestion, payload, 'TEF'):
						questionText.push("Vous êtes déjà un Canadien, n'est pas?");
						questionText.push("Un score étonnant!");
						questionText.push("Hou la la! Vous devez être fier de vous!");
						questionText.push("Saviez-vous que même certains natives ne peuvent pas marquer aussi haut? Félicitations à vous!");
						questionText.push("{QUOTE_FRENCH}Avez-vous fait un test de la langue seconde?");
						break;

					default:
						questionText.push("{QUOTE}Did you take a second language test?");
						break;
				}
			}

			return questionText;
		},
		options: function (payload) {
			return (payload.firstLanguageTest == 3 ? ['No',
				'CELPIP',
				'IELTS'] : ['No',
					'TEF']);
		},
		processReply: function (payload, reply) { payload.secondLanguageTest = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return (payload.secondLanguageTest == 0 ? questions.workExperienceLastTenYears : questions.secondLanguageSpeaking) },
	},
	secondLanguageSpeaking: {
		id: null,
		question: function (payload) { return languageQuestion(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.speaking); },
		options: function (payload) { return languageOptions(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.speaking); },
		processReply: function (payload, reply) { payload.secondLanguageSpeaking = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.secondLanguageListening },
	},
	secondLanguageListening: {
		id: null,
		question: function (payload) { return languageQuestion(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.listening); },
		options: function (payload) { return languageOptions(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.listening); },
		processReply: function (payload, reply) { payload.secondLanguageListening = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.secondLanguageReading },
	},
	secondLanguageReading: {
		id: null,
		question: function (payload) { return languageQuestion(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.reading); },
		options: function (payload) { return languageOptions(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.reading); },
		processReply: function (payload, reply) { payload.secondLanguageReading = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.secondLanguageWriting },
	},
	secondLanguageWriting: {
		id: null,
		question: function (payload) { return languageQuestion(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.writing); },
		options: function (payload) { return languageOptions(payload.secondLanguageTest, questions.secondLanguageTest, payload, languageAbility.writing); },
		processReply: function (payload, reply) { payload.secondLanguageWriting = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.workExperienceLastTenYears },
	},
	workExperienceLastTenYears: {
		id: null,
		question: function (payload) { 
			var questionText = [];

			if (parseInt(payload.secondLanguageSpeaking) >= 9 &&
				parseInt(payload.secondLanguageListening) >= 9 &&
				parseInt(payload.secondLanguageReading) >= 9 &&
				parseInt(payload.secondLanguageWriting) >= 9)
			{
				var languageQuestion = questions['secondLanguageTest'];

				switch (parseInt(payload.secondLanguageTest))
				{
					case answerIndex(languageQuestion, payload, 'CELPIP'):
					case answerIndex(languageQuestion, payload, 'IELTS'):
						questionText.push("You are a Canadian already, eh?");
						questionText.push("Amazing score!");
						questionText.push("Wow! You must be proud!");
						questionText.push("Did you know that even some native speakers can't score that high? Congratulations!");
						break;

					case answerIndex(languageQuestion, payload, 'TEF'):
						questionText.push("Vous êtes déjà un Canadien, n'est pas?");
						questionText.push("Un score étonnant!");
						questionText.push("Hou la la! Vous devez être fier de vous!");
						questionText.push("Saviez-vous que même certains natives ne peuvent pas marquer aussi haut? Félicitations à vous!");
						break;
				}
			}

			questionText.push("Ok, let's talk about your work experience. In the last 10 years, how many years of skilled work experience do you have?");
			
			return questionText
		},
		options: workExperienceLastTenYearsOptions,
		processReply: function (payload, reply) { payload.workExperienceLastTenYears = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.workExperienceInCanada },
	},
	workExperienceInCanada: {
		id: null,
		question: function (payload) {
			var questionText = [];

			if (parseInt(payload.workExperienceLastTenYears) >= 6)
				questionText.push("It looks like we have an expert over here! Canada is eager for senior level professionals!");

			questionText.push("What about in Canada?");

			return questionText;
		},
		options: workExperienceInCanadaOptions,
		processReply: function (payload, reply) { payload.workExperienceInCanada = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.certificateQualificationProvince },
	},
	certificateQualificationProvince: {
		id: null,
		question: function (payload) { return "{QUOTE}Do you have a certificate of qualification from a Canadian province or territory?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.certificateQualificationProvince = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return questions.validJobOffer },
	},
	validJobOffer: {
		id: null,
		question: function (payload) { return "{QUOTE}Do you have a valid job offer supported by a Labour Market Impact Assessment (if needed)?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.validJobOffer = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (util.parseBoolean(payload.validJobOffer) ? questions.nocJobOffer : questions.nominationCertificate) },
	},
	nocJobOffer: {
		id: null,
		question: function (payload) { return "Good! Which NOC skill type or level is this job offer?" },
		options: nocJobOfferOptions,
		processReply: function (payload, reply) { payload.nocJobOffer = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.nominationCertificate },
	},
	nominationCertificate: {
		id: null,
		question: function (payload) { return "{QUOTE}Do you have a nomination certificate from a province or territory?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.nominationCertificate = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			return questions.married
			//return (util.parseBoolean(payload.married) && !util.parseBoolean(payload.spouseCanadianCitizen) && util.parseBoolean(payload.spouseCommingAlong) ? questions.spouseAge : questions.calculation) 
		},
	},
	married: {
		id: null,
		question: function (payload) { return "And one last question. Are you married or have a common-law partner? By the way, same-sex marriages are legally recognized in all provinces and territories in Canada." },
		options: yesNo,
		processReply: function (payload, reply) { payload.married = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.married ? questions.spouseCanadianCitizen : questions.calculation) },
	},
	spouseCanadianCitizen: {
		id: null,
		question: function (payload) {
			var questionText = ["Love is in the air...",
				"Ok, now I am going to ask some questions about your partner.",
				"Is your spouse or common-law partner a citizen or permanent resident of Canada?"];

			return questionText;
		},
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseCanadianCitizen = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.spouseCanadianCitizen ? questions.calculation : questions.spouseCommingAlong) },
	},
	spouseCommingAlong: {
		id: null,
		question: function (payload) { return "{QUOTE}Will your spouse or common-law partner come with you to Canada?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseCommingAlong = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.spouseCommingAlong ? questions.spouseAge : questions.calculation) }
	},

	spouseAge: {
		id: null,
		question: function (payload) { return "{QUOTE}How old is your spouse or common-law partner?" },
		options: ageOptions,
		processReply: function (payload, reply) { payload.spouseAge = answerIndex(this, payload, reply) + 17; },
		nextQuestion: function (payload) { return questions.spouseEducationLevel },
	},
	spouseEducationLevel: {
		id: null,
		question: function (payload) { return "{QUOTE}What is your spouse or common-law partner's education level?" },
		options: educationLevelOptions,
		processReply: function (payload, reply) { payload.spouseEducationLevel = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.spouseCanadianDegreeDiplomaCertificate },
	},
	spouseCanadianDegreeDiplomaCertificate: {
		id: null,
		question: function (payload) { return "Since we are talking about education, have your spouse or common-law partner earned a Canadian degree, diploma or certificate?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseCanadianDegreeDiplomaCertificate = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.spouseCanadianDegreeDiplomaCertificate ? questions.spouseCanadianEducationLevel : questions.spouseFirstLanguageTest) },
	},
	spouseCanadianEducationLevel: {
		id: null,
		question: function (payload) { return "Cool! What is your spouse or common-law partner's education level in Canada?" },
		options: canadianEducationLevelOptions,
		processReply: function (payload, reply) { payload.spouseCanadianEducationLevel = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageTest },
	},
	spouseFirstLanguageTest: {
		id: null,
		question: function (payload) {
			var questionText = [];

			if (!util.parseBoolean(payload.spouseCanadianEducationLevel))
				questionText.push("All right, let's move on then.");

			questionText.push("Did your spouse or common-law partner take a language test over the last 2 years?");

			return questionText;
		},
		options: function (payload) {
			return ['No, and won\'t',
				'No, but will',
				'CELPIP',
				'IELTS',
				'TEF'];
		},
		processReply: function (payload, reply) {
			payload.spouseFirstLanguageTest = answerIndex(this, payload, reply);
		},
		nextQuestion: function (payload) {
			if (payload.spouseFirstLanguageTest == 0)
				return questions.spouseWorkExperienceLastTenYears;
			else
			{
				return questions.spouseFirstLanguageSpeaking;
			}
		}
	},
	spouseFirstLanguageSpeaking: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.speaking, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.speaking, false); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageSpeaking = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageListening },
	},
	spouseFirstLanguageListening: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.listening, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.listening, false); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageListening = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageReading },
	},
	spouseFirstLanguageReading: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.reading, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.reading, false); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageReading = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageWriting },
	},
	spouseFirstLanguageWriting: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.writing, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.writing, false); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageWriting = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return (payload.spouseFirstLanguageTest == 0 ? questions.spouseWorkExperienceInCanada : questions.spouseSecondLanguageTest) },
	},
	spouseSecondLanguageTest: {
		id: null,
		question: function (payload) {
			var questionText = [];

			if (parseInt(payload.spouseFirstLanguageSpeaking) >= 9 &&
				parseInt(payload.spouseFirstLanguageListening) >= 9 &&
				parseInt(payload.spouseFirstLanguageReading) >= 9 &&
				parseInt(payload.spouseFirstLanguageWriting) >= 9)
			{
				var languageQuestion = questions['spouseFirstLanguageTest'];
				
				switch (parseInt(payload.spouseFirstLanguageTest))
				{
					case answerIndex(languageQuestion, payload, 'CELPIP'):
					case answerIndex(languageQuestion, payload, 'IELTS'):
						questionText.push("Your spouse or common-law partner is a Canadian already, eh?");
						questionText.push("Amazing score!");
						questionText.push("Wow! Your spouse or common-law partner must be proud!");
						questionText.push("Did you know that even some native speakers can't score that high? Congratulations!");
						questionText.push("{QUOTE}Did your spouse or common-law partner take a second language test?");
						break;

					case answerIndex(languageQuestion, payload, 'TEF'):
						questionText.push("Votre époux ou conjoint êtes déjà un(e) Canadien(ne), n'est pas?");
						questionText.push("Un score étonnant!");
						questionText.push("Hou la la! Vous devez être fier de vous!");
						questionText.push("Saviez-vous que même certains natives ne peuvent pas marquer aussi haut? Félicitations à vous!");
						questionText.push("{QUOTE_FRENCH}Est-ce que votre époux ou conjoint de fait a passé un test de la langue seconde?");
						break;

					default:
						questionText.push("{QUOTE}Did your spouse or common-law partner take a second language test?");
						break;
				}
			}

			return questionText;
		},
		options: function (payload) {
			return (payload.spouseFirstLanguageTest == 4 ? ['No',
				'CELPIP',
				'IELTS'] : ['No',
					'TEF']);
		},
		processReply: function (payload, reply) { payload.spouseSecondLanguageTest = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return (payload.spouseSecondLanguageTest == 0 ? questions.spouseWorkExperienceLastTenYears : questions.spouseSecondLanguageSpeaking) },
	},
	spouseSecondLanguageSpeaking: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.speaking, false); },
		options: function (payload) { return languageOptions(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.speaking); },
		processReply: function (payload, reply) { payload.spouseSecondLanguageSpeaking = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseSecondLanguageListening },
	},
	spouseSecondLanguageListening: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.listening, false); },
		options: function (payload) { return languageOptions(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.listening); },
		processReply: function (payload, reply) { payload.spouseSecondLanguageListening = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseSecondLanguageReading },
	},
	spouseSecondLanguageReading: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.reading, false); },
		options: function (payload) { return languageOptions(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.reading); },
		processReply: function (payload, reply) { payload.spouseSecondLanguageReading = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseSecondLanguageWriting },
	},
	spouseSecondLanguageWriting: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.writing, false); },
		options: function (payload) { return languageOptions(payload.spouseSecondLanguageTest, questions.spouseSecondLanguageTest, payload, languageAbility.writing); },
		processReply: function (payload, reply) { payload.spouseSecondLanguageWriting = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseWorkExperienceLastTenYears },
	},
	spouseWorkExperienceLastTenYears: {
		id: null,
		question: function (payload) { 
			var questionText = [];

			if (parseInt(payload.spouseSecondLanguageSpeaking) >= 9 &&
				parseInt(payload.spouseSecondLanguageListening) >= 9 &&
				parseInt(payload.spouseSecondLanguageReading) >= 9 &&
				parseInt(payload.spouseSecondLanguageWriting) >= 9)
			{
				var languageQuestion = questions['spouseSecondLanguageTest'];

				switch (parseInt(payload.spouseSecondLanguageTest))
				{
					case answerIndex(languageQuestion, payload, 'CELPIP'):
					case answerIndex(languageQuestion, payload, 'IELTS'):
						questionText.push("Your spouse or common-law partner is a Canadian already, eh?");
						questionText.push("Amazing score!");
						questionText.push("Wow! Your spouse or common-law partner must be proud!");
						questionText.push("Did you know that even some native speakers can't score that high? Congratulations!");
						break;

					case answerIndex(languageQuestion, payload, 'TEF'):
						questionText.push("Votre époux ou conjoint êtes déjà un(e) Canadien(ne), n'est pas?");
						questionText.push("Un score étonnant!");
						questionText.push("Hou la la! Vous devez être fier de vous!");
						questionText.push("Saviez-vous que même certains natives ne peuvent pas marquer aussi haut? Félicitations à vous!");
						break;
				}
			}

			questionText.push("Ok, let's talk about your your spouse or common-law partner work experience. In the last 10 years, how many years of skilled work experience does your spouse or common-law partner have?");

			return questionText;
		},
		options: workExperienceLastTenYearsOptions,
		processReply: function (payload, reply) { payload.spouseWorkExperienceLastTenYears = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.spouseWorkExperienceInCanada },
	},
	spouseWorkExperienceInCanada: {
		id: null,
		question: function (payload) {
			var questionText = [];

			if (parseInt(payload.spouseWorkExperienceLastTenYears) >= 6)
				questionText.push("It looks like we have an expert over here! Canada is eager for senior level professionals!");

			questionText.push("What about in Canada?");

			return questionText;
		},
		options: workExperienceInCanadaOptions,
		processReply: function (payload, reply) { payload.spouseWorkExperienceInCanada = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.spouseCertificateQualificationProvince },
	},
	spouseCertificateQualificationProvince: {
		id: null,
		question: function (payload) { return "{QUOTE}Does your spouse or common-law partner have a certificate of qualification from a Canadian province or territory?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseCertificateQualificationProvince = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return questions.spouseValidJobOffer },
	},
	spouseValidJobOffer: {
		id: null,
		question: function (payload) { return "{QUOTE}Does your spouse or common-law partner have a valid job offer supported by a Labour Market Impact Assessment (if needed)?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseValidJobOffer = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (util.parseBoolean(payload.spouseValidJobOffer) ? questions.spouseNocJobOffer : questions.spouseNominationCertificate) },
	},
	spouseNocJobOffer: {
		id: null,
		question: function (payload) { return "Good! Which NOC skill type or level is the job offer?" },
		options: nocJobOfferOptions,
		processReply: function (payload, reply) { payload.spouseNocJobOffer = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.spouseNominationCertificate },
	},
	spouseNominationCertificate: {
		id: null,
		question: function (payload) { return "{QUOTE}Does your spouse or common-law partner have a nomination certificate from a province or territory?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseNominationCertificate = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return questions.calculation },
	},

	calculation: {
		id: null,
		question: function (payload) {
			var scores = calculate(payload);

			payload.remarks = calculator.report(scores);

			if (util.parseBoolean(payload.nominationCertificate))
				payload.questionAfterRemarks = "Since you were nominated by a province or territory, you already have enough points to pass the next draw. Welcome to Canada! I can send this analysis to your e-mail. Would you like me to?";
			else
				payload.questionAfterRemarks = "I can show you some ideas on how to improve your score over the next 3 years.<br />Would you like to see them?";

			return ["{QUOTE}Let me check your score...", "Your score is " + util.formatNumber(scores.total, 0) + "! Here are the details:"];
		},
		options: yesNo,
		processReply: function (payload, reply) {
			if (util.parseBoolean(payload.nominationCertificate))
				payload.sendEmail = yesNoAnswer(reply);
			else
				payload.plan = yesNoAnswer(reply);
		},
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.nominationCertificate))
				if (util.parseBoolean(payload.sendEmail))
					return questions.askEmail;
				else
					if (util.parseBoolean(payload.spouseCommingAlong) && payload.spouseFirstLanguageTest != 0 && !util.parseBoolean(payload.nominationCertificate))
						return questions.invertRoles;
					else
						return questions.startOver;
			else
				if (util.parseBoolean(payload.plan))
					return questions.plan;
				else
					return questions.sendEmail;
		},
	},
	plan: {
		id: null,
		question: function (payload) {
			payload.remarks = plan(payload);
			payload.questionAfterRemarks = "{QUOTE}I can send this analysis to your e-mail. Would you like me to?";

			return "{QUOTE}Here are the details for your 3-year plan:";
		},
		options: yesNo,
		processReply: function (payload, reply) {
			payload.sendEmail = yesNoAnswer(reply);

			if (!util.parseBoolean(payload.sendEmail))
			{
				var scores = calculate(payload);
				db.saveChat(payload, scores.total);
			}
		},
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.sendEmail))
				return questions.askEmail;
			else
				if (util.parseBoolean(payload.spouseCommingAlong) && payload.spouseFirstLanguageTest != 0 && !util.parseBoolean(payload.nominationCertificate))
					return questions.invertRoles;
				else
					return questions.startOver;
		},
	},
	sendEmail: {
		id: null,
		question: function (payload) { return "{QUOTE}I can send this analysis to your e-mail. Would you like me to?" },
		options: yesNo,
		processReply: function (payload, reply) {
			payload.sendEmail = yesNoAnswer(reply);

			if (!util.parseBoolean(payload.sendEmail))
			{
				var scores = calculate(payload);
				db.saveChat(payload, scores.total);
			}
		},
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.sendEmail))
				return questions.askEmail;
			else
				if (util.parseBoolean(payload.spouseCommingAlong) && payload.spouseFirstLanguageTest != 0 && !util.parseBoolean(payload.nominationCertificate))
					return questions.invertRoles;
				else
					return questions.startOver;
		},
	},
	askEmail: {
		id: null,
		question: function (payload) { return "{QUOTE}What is your e-mail?" },
		options: function (payload) { return null },
		replyType: { type: replyType.email },
		processReply: function (payload, reply) {
			payload.email = reply;

			var scores = calculate(payload);
			db.saveChat(payload, scores.total);

			sendEmail(payload);
		},
		nextQuestion: function (payload) {
			return questions.emailSent;
		},
	},
	emailSent: {
		id: null,
		question: function (payload) {
			var questionText = ["{QUOTE}I'm sending it right now and you should receive it soon.", "If you don't receive it, make sure it's not on your spam folder, sometimes it happens."]

			if (util.parseBoolean(payload.spouseCommingAlong) && payload.spouseFirstLanguageTest != 0 && !util.parseBoolean(payload.nominationCertificate))
				questionText.push("Oh, I can do this analysis invertion your role with you spouse or common-law partner. Would you like me to do it?");
			else
				questionText.push("Would you like to start over?");

			return questionText;
		},
		options: yesNo,
		processReply: function (payload, reply) { payload.invertRoles = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.spouseCommingAlong) && payload.spouseFirstLanguageTest != 0 && !util.parseBoolean(payload.nominationCertificate))
			{
				if (util.parseBoolean(payload.invertRoles))
				{
					clearPayload(payload, questions.married.id);

					return questions.married;
				}
				else
					return questions.done;

			}
			else
			{
				if (util.parseBoolean(payload.invertRoles))
					return questions.calculationInverted;
				else
					return questions.startOver;
			}
		},
	},
	invertRoles: {
		id: null,
		question: function (payload) { return "{QUOTE}I can do this analysis invertion your role with you spouse or common-law partner. Would you like me to do it?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.invertRoles = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.invertRoles))
			{
				return questions.calculationInverted;
			}
			else
				return questions.startOver;
		},
	},
	calculationInverted: {
		id: null,
		question: function (payload) {
			var scores = calculateInverted(payload);

			payload.remarks = calculator.report(scores);
			payload.questionAfterRemarks = "I can show you some ideas on how to improve your score with your roles inverted over the next 3 years.<br />Would you like to see them?";

			return "{QUOTE}Your score with your roles inverted is " + util.formatNumber(scores.total, 0) + "! Here are the details:";
		},
		options: yesNo,
		processReply: function (payload, reply) { payload.planInverted = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.planInverted))
				return questions.planInverted;
			else
			{
				return questions.sendEmail;
			}
		},
	},
	planInverted: {
		id: null,
		question: function (payload) {
			payload.remarks = planInverted(payload);
			payload.questionAfterRemarks = "{QUOTE}I can send this analysis to your e-mail. Would you like me to?";

			return "{QUOTE}Here are the details for your 3-year plan:";
		},
		options: yesNo,
		processReply: function (payload, reply) { payload.sendEmailInverted = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.sendEmailInverted))
				return questions.askEmailInverted;
			else
				return questions.startOver;
		},
	},
	askEmailInverted: {
		id: null,
		question: function (payload) { return "{QUOTE}What is your e-mail?" },
		options: function (payload) { return null },
		replyType: { type: replyType.email },
		processReply: function (payload, reply) {
			payload.emailInverted = reply;

			sendEmailInverted(payload);
		},
		nextQuestion: function (payload) {
			return questions.emailInvertedSent;
		},
	},
	emailInvertedSent: {
		id: null,
		question: function (payload) { return ["{QUOTE}I'm sending it right now and you should receive it soon.", "If you don't receive it, make sure it's not on your spam folder, sometimes it happens.", "Would you like to start over?"] },
		options: yesNo,
		processReply: function (payload, reply) { payload.startOver = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.startOver))
			{
				clearPayload(payload, questions.married.id);

				return questions.married;
			}
			else
				return questions.done;
		},
	},
	startOver: {
		id: null,
		question: function (payload) { return "{QUOTE}Would you like to start over?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.startOver = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.startOver))
			{
				clearPayload(payload, questions.married.id);

				return questions.married;
			}
			else
				return questions.done;
		},
	},
	done: {
		id: null,
		question: function (payload) { return "" },
		options: function (payload) { return [] },
		processReply: function (payload, reply) { },
		nextQuestion: function (payload) { return questions.done; },
	}
}

var questionsArray = Object.keys(questions);

for (q = 0; q < questionsArray.length; q++)
{
	questions[questionsArray[q]].id = q;
}

function validateAnswer(question, payload, reply) {
	var options = question.options(payload);

	if (options === null)
	{
		if (question.replyType !== undefined)
		{
			switch (question.replyType.type)
			{
				case replyType.name:
					if (reply.indexOf('@') >= 0)
						return false;
					else
						if (!isNaN(reply))
							return false;
					break;

				case replyType.email:
					return util.validateEmail(reply);
					break;
			}
		}
		return true;
	}
	else
		return (options.indexOf(reply) >= 0);
}

function questionFlow(payload, reply, back, callback) {
	//console.log('payload: ', payload);
	//console.log('reply: ', reply);
	//console.log('back: ', back);

	if (payload === undefined) payload = {};

	var responseJSON = {
		"question": null, // what the bot will respond with (more is appended below)
		"remarks": null, // any other information that is not a question
		"questionAfterRemarks": null, // what the bot will respond with (more is appended below)
		"payload": null, // working data to examine in future calls to this function to keep track of state
		"options": null, // a JSON object containing suggested/quick replies to display to the user
		"replyType": null // a JSON object containing the type and maxlength of the input, when applicable
	};

	var question;

	if (back === undefined)
	{
		if (Object.keys(payload).length > 0)
		{
			//console.log('payload.question: ', payload.question);
			question = questions[questionsArray[payload.question]];
			//console.log('question: ', question);

			//console.log('answerValid: ', validateAnswer(question, payload, reply));
			if (validateAnswer(question, payload, reply))
			{
				question.processReply(payload, reply);

				//console.log('payload Before: ', payload);
				question = question.nextQuestion(payload);
				//console.log('payload After: ', payload);
			}
		}
		else
		{
			question = questions.name;
		}
	}
	else
	{
		//console.log('back: ', back);
		question = questions[questionsArray[back]];
		//console.log('question: ', question);

		//console.log('payload: ', payload);
		clearPayload(payload, back);
		//console.log('payload: ', payload);
	}

	//console.log('nextQuestion: ', question);

	responseJSON.question = question.question(payload);
	responseJSON.options = question.options(payload);

	payload.question = question.id;

	responseJSON.question = quote(responseJSON.question);
	responseJSON.payload = payload;

	if (payload.questionAfterRemarks !== undefined)
	{
		responseJSON.questionAfterRemarks = quote(payload.questionAfterRemarks);
		delete payload['questionAfterRemarks'];
	}

	if (payload.remarks !== undefined)
	{
		responseJSON.remarks = payload.remarks;
		delete payload['remarks'];
	}

	if (payload.score !== undefined)
	{
		responseJSON.score = payload.score;
		delete payload['score'];
	}

	if (payload.scoreInverted !== undefined)
	{
		responseJSON.scoreInverted = payload.scoreInverted;
		delete payload['scoreInverted'];
	}

	//console.log("responseJSON: ", responseJSON);
	return responseJSON;
}

function parameters(payload) {
	var parameters = {};

	parameters.married = util.parseBoolean(payload.married);
	parameters.spouseCanadianCitizen = util.parseBoolean(payload.spouseCanadianCitizen);
	parameters.spouseCommingAlong = util.parseBoolean(payload.spouseCommingAlong);
	parameters.age = parseInt(payload.age);
	parameters.educationLevel = parseInt(payload.educationLevel);
	if (payload.canadianEducationLevel !== undefined) parameters.educationInCanada = parseInt(payload.canadianEducationLevel);
	parameters.firstLanguage = calculator.languageObject();
	parameters.firstLanguage.test = parseInt(payload.firstLanguageTest);
	parameters.firstLanguage.speaking = parseInt(payload.firstLanguageSpeaking);
	parameters.firstLanguage.listening = parseInt(payload.firstLanguageListening);
	parameters.firstLanguage.reading = parseInt(payload.firstLanguageReading);
	parameters.firstLanguage.writing = parseInt(payload.firstLanguageWriting);
	parameters.secondLanguage = calculator.languageObject();
	parameters.secondLanguage.test = (parameters.firstLanguage.test === calculator.languageTest.tef ? parseInt(payload.secondLanguageTest) : calculator.languageTest.tef);
	parameters.secondLanguage.speaking = (payload.secondLanguageSpeaking === undefined ? 0 : parseInt(payload.secondLanguageSpeaking));
	parameters.secondLanguage.listening = (payload.secondLanguageListening === undefined ? 0 : parseInt(payload.secondLanguageListening));
	parameters.secondLanguage.reading = (payload.secondLanguageReading === undefined ? 0 : parseInt(payload.secondLanguageReading));
	parameters.secondLanguage.writing = (payload.secondLanguageWriting === undefined ? 0 : parseInt(payload.secondLanguageWriting));
	parameters.workInCanada = parseInt(payload.workExperienceInCanada);
	parameters.workExperience = parseInt(payload.workExperienceLastTenYears);
	parameters.certificateFromProvince = util.parseBoolean(payload.certificateQualificationProvince);
	if (payload.nocJobOffer !== undefined) parameters.nocJobOffer = parseInt(payload.nocJobOffer);
	parameters.provincialNomination = util.parseBoolean(payload.nominationCertificate);
	if (payload.spouseEducationLevel !== undefined) parameters.spouseEducationLevel = parseInt(payload.spouseEducationLevel);
	if (payload.spouseWorkExperienceInCanada !== undefined) parameters.spouseWorkInCanada = parseInt(payload.spouseWorkExperienceInCanada);
	parameters.spouseLanguage = calculator.languageObject();
	parameters.spouseLanguage.test = (payload.spouseFirstLanguageTest === undefined ? calculator.languageTest.none : parseInt(payload.spouseFirstLanguageTest) - 1);
	parameters.spouseLanguage.speaking = (payload.spouseFirstLanguageSpeaking === undefined ? 0 : parseInt(payload.spouseFirstLanguageSpeaking));
	parameters.spouseLanguage.listening = (payload.spouseFirstLanguageListening === undefined ? 0 : parseInt(payload.spouseFirstLanguageListening));
	parameters.spouseLanguage.reading = (payload.spouseFirstLanguageReading === undefined ? 0 : parseInt(payload.spouseFirstLanguageReading));
	parameters.spouseLanguage.writing = (payload.spouseFirstLanguageWriting === undefined ? 0 : parseInt(payload.spouseFirstLanguageWriting));

	return parameters;
}

function parametersInverted(payload) {
	var parameters = {};

	parameters.married = util.parseBoolean(payload.married);
	parameters.spouseCanadianCitizen = util.parseBoolean(payload.spouseCanadianCitizen);
	parameters.spouseCommingAlong = util.parseBoolean(payload.spouseCommingAlong);
	parameters.age = parseInt(payload.spouseAge);
	parameters.educationLevel = parseInt(payload.spouseEducationLevel);
	if (payload.spouseCanadianEducationLevel !== undefined) parameters.educationInCanada = parseInt(payload.spouseCanadianEducationLevel);
	parameters.firstLanguage = calculator.languageObject();
	parameters.firstLanguage.test = parseInt(payload.spouseFirstLanguageTest) - 1;
	parameters.firstLanguage.speaking = parseInt(payload.spouseFirstLanguageSpeaking);
	parameters.firstLanguage.listening = parseInt(payload.spouseFirstLanguageListening);
	parameters.firstLanguage.reading = parseInt(payload.spouseFirstLanguageReading);
	parameters.firstLanguage.writing = parseInt(payload.spouseFirstLanguageWriting);
	parameters.secondLanguage = calculator.languageObject();
	parameters.secondLanguage.test = (parameters.firstLanguage.test === calculator.languageTest.tef ? parseInt(payload.spouseSecondLanguageTest) : calculator.languageTest.tef);
	parameters.secondLanguage.speaking = (payload.spouseSecondLanguageSpeaking === undefined ? 0 : parseInt(payload.spouseSecondLanguageSpeaking));
	parameters.secondLanguage.listening = (payload.spouseSecondLanguageListening === undefined ? 0 : parseInt(payload.spouseSecondLanguageListening));
	parameters.secondLanguage.reading = (payload.spouseSecondLanguageReading === undefined ? 0 : parseInt(payload.spouseSecondLanguageReading));
	parameters.secondLanguage.writing = (payload.spouseSecondLanguageWriting === undefined ? 0 : parseInt(payload.spouseSecondLanguageWriting));
	parameters.workInCanada = parseInt(payload.spouseWorkExperienceInCanada);
	parameters.workExperience = parseInt(payload.spouseWorkExperienceLastTenYears);
	parameters.certificateFromProvince = util.parseBoolean(payload.spouseCertificateQualificationProvince);
	if (payload.nocJobOffer !== undefined) parameters.nocJobOffer = parseInt(payload.spouseNocJobOffer);
	parameters.provincialNomination = util.parseBoolean(payload.spouseNominationCertificate);
	parameters.spouseEducationLevel = parseInt(payload.educationLevel);
	parameters.spouseWorkInCanada = parseInt(payload.workExperienceInCanada);
	parameters.spouseLanguage = calculator.languageObject();
	parameters.spouseLanguage.test = parseInt(payload.firstLanguageTest);
	parameters.spouseLanguage.speaking = parseInt(payload.firstLanguageSpeaking);
	parameters.spouseLanguage.listening = parseInt(payload.firstLanguageListening);
	parameters.spouseLanguage.reading = parseInt(payload.firstLanguageReading);
	parameters.spouseLanguage.writing = parseInt(payload.firstLanguageWriting);

	return parameters;
}

function calculate(payload) {
	return calculator.calculate(parameters(payload));
}

function plan(payload) {
	var calculationParameters = parameters(payload);
	var scores = calculator.calculate(calculationParameters);

	return analysis.analyse(calculationParameters, scores);
}

function calculateInverted(payload) {
	return calculator.calculate(parametersInverted(payload));
}

function planInverted(payload) {
	var calculationParameters = parametersInverted(payload);
	var scores = calculator.calculate(calculationParameters);

	return analysis.analyse(calculationParameters, scores);
}

function sendEmail(payload) {
	var scores = calculate(payload);

	var remarks = calculator.report(scores);

	var analysis = plan(payload);

	email.sendEmail(payload.email, remarks, analysis);
}

function sendEmailInverted(payload) {
	var scores = calculateInverted(payload);

	var remarks = calculator.report(scores);

	var analysis = planInverted(payload);

	email.sendEmail(payload.emailInverted, remarks, analysis);
}

function clearPayload(payload, startingQuestion) {
	var ignore = "";

	if (typeof (startingQuestion) === 'string')
		startingQuestion = parseInt(startingQuestion);

	for (q = 0; q < startingQuestion; q++)
	{
		ignore += "[" + questionsArray[q] + "]";
	}

	var payloadArray = Object.keys(payload);
	var propertyName;

	for (p = 0; p < payloadArray.length; p++)
	{
		propertyName = payloadArray[p];

		if (ignore.indexOf(propertyName) < 0)
			delete payload[propertyName];
	}
}

function quote(text, english) {
	var quotes = ['Ok. ',
		'Cool! ',
		'Awesome! ',
		'All right, ',
		'Nice! ',
		'Now, tell me something. '];

	var quotesFrench = ['Ok. ',
		'Chouette! ',
		'Génial! ',
		'D\'accord, ',
		'Sympa! ',
		'Voilá, dites-moi. '];

	if (nextQuote === undefined)
		nextQuote = 0;
	else
	{
		nextQuote += 1;

		if (nextQuote >= quotes.length)
			nextQuote = 0;
	}

	if (typeof (text) === 'string')
		return text.replace('{QUOTE}', quotes[nextQuote]).replace('{QUOTE_FRENCH}', quotesFrench[nextQuote]);
	else
	{
		for (t = 0; t < text.length; t++)
		{
			text[t] = text[t].replace('{QUOTE}', quotes[nextQuote]).replace('{QUOTE_FRENCH}', quotesFrench[nextQuote]);
		}

		return text;
	}
}

module.exports = {
	questionFlow: questionFlow,
	questions: questions
};