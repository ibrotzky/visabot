var util = require("../../../util");

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

function ageOptions(payload) {
	return ['17 or less', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45 or more'];
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
	var abilityName;
	var testName;
	var testSectionName;

	if (principalApplicant === undefined)
		principalApplicant = true;

	switch (ability) {
		case languageAbility.speaking:
			abilityName = 'speak';
			testSectionName = 'speaking';
			break;

		case languageAbility.listening:
			abilityName = 'listen';
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

	if (parseInt(test) === 0)
		return "{QUOTE}How well can you" + (principalApplicant ? " " : "r spouse or common-law partner ") + abilityName + " English?";
	else {
		switch (parseInt(test)) {
			case answerIndex(testQuestion, payload, 'CELPIP'):
				testName = "CELPIP";
				break;

			case answerIndex(testQuestion, payload, 'IELTS'):
				testName = "IELTS";
				break;

			case answerIndex(testQuestion, payload, 'TEF'):
				testName = "TEF";
				break;
		}

		return "{QUOTE}What is your" + (principalApplicant ? " " : " spouse or common-law partner's ") + testSectionName + " score on the " + testName + " test?";
	}
}

function languageOptions(test, testQuestion, payload, ability) {
	/*
	console.log('answer: ', test);
	console.log('No: ', answerIndex(testQuestion, payload, 'No'));
	console.log('CELPIP: ', answerIndex(testQuestion, payload, 'CELPIP'));
	console.log('IELTS: ', answerIndex(testQuestion, payload, 'IELTS'));
	console.log('TEF: ', answerIndex(testQuestion, payload, 'TEF'));
	*/
	switch (parseInt(test)) {
		case answerIndex(testQuestion, payload, 'No'):
			return ["0%", "10%", "20%", "30%", "40%", "50%", "60%", "70%", "80%", "90%", "100%"];
			break;

		case answerIndex(testQuestion, payload, 'CELPIP'):
			return ["M, 0 - 3", "4", "5", "6", "7", "8", "9", "10 - 12"];
			break;

		case answerIndex(testQuestion, payload, 'IELTS'):
			return ["0 - 3.5", "4 - 4.5", "5", "5.5", "6", "6.5", "7", "7.5 - 9"];
			break;

		case answerIndex(testQuestion, payload, 'TEF'):
			switch (ability) {
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
	return ["None or less then a year",
		"1 year",
		"2 years",
		"3 years",
		"4 years",
		"5 years or more"];
}

function workExperienceLastTenYearsOptions(payload) {
	return ["None or less then a year",
		"1 year",
		"2 years",
		"3 years or more"];
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
		options: ageOptions,
		processReply: function (payload, reply) {
			switch (reply) {
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
		options: educationLevelOptions,
		processReply: function (payload, reply) { payload.educationLevel = answerIndex(this, payload, reply); },
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
		options: canadianEducationLevelOptions,
		processReply: function (payload, reply) { payload.canadianEducationLevel = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.firstLanguageTest },
	},
	firstLanguageTest: {
		id: null,
		question: function (payload) { return "{QUOTE}Did you take a language test?" },
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
		nextQuestion: function (payload) { return (payload.firstLanguageTest == 0 ? questions.workExperienceInCanada : questions.secondLanguageTest) },
	},
	secondLanguageTest: {
		id: null,
		question: function (payload) { return "{QUOTE}Did you take a second language test?" },
		options: function (payload) {
			return (payload.firstLanguageTest == 3 ? ['No',
				'CELPIP',
				'IELTS'] : ['No',
					'TEF']);
		},
		processReply: function (payload, reply) { payload.secondLanguageTest = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return (payload.secondLanguageTest == 0 ? questions.workExperienceInCanada : questions.secondLanguageSpeaking) },
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
		nextQuestion: function (payload) { return questions.workExperienceInCanada },
	},
	workExperienceInCanada: {
		id: null,
		question: function (payload) { return "{QUOTE}In the last ten years, how many years of skilled work experience in Canada do you have?" },
		options: workExperienceInCanadaOptions,
		processReply: function (payload, reply) { payload.workExperienceInCanada = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.workExperienceLastTenYears },
	},
	workExperienceLastTenYears: {
		id: null,
		question: function (payload) { return "{QUOTE}In the last 10 years, how many years of skilled work experience do you have?" },
		options: workExperienceLastTenYearsOptions,
		processReply: function (payload, reply) { payload.workExperienceLastTenYears = answerIndex(this, payload, reply); },
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
		question: function (payload) { return "{QUOTE}Which NOC skill type or level is the job offer?" },
		options: nocJobOfferOptions,
		processReply: function (payload, reply) { payload.nocJobOffer = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.nominationCertificate },
	},
	nominationCertificate: {
		id: null,
		question: function (payload) { return "{QUOTE}Do you have a nomination certificate from a province or territory?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.nominationCertificate = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (util.parseBoolean(payload.married) && !util.parseBoolean(payload.spouseCanadianCitizen) && util.parseBoolean(payload.spouseCommingAlong) ? questions.spouseAge : questions.calculation) },
	},

	spouseAge: {
		id: null,
		question: function (payload) { return "{QUOTE}How old is your spouse or common-law partner?" },
		options: ageOptions,
		processReply: function (payload, reply) {
			switch (reply) {
				case '17 or less':
					payload.spouseAge = 17;
					break;

				case '20 to 29':
					payload.spouseAge = 20;
					break;

				case '45 or more':
					payload.spouseAge = 45;
					break;

				default:
					payload.spouseAge = parseInt(reply);
					break;
			}
		},
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
		question: function (payload) { return "{QUOTE}Have your spouse or common-law partner earned a Canadian degree, diploma or certificate?" },
		options: yesNo,
		processReply: function (payload, reply) { payload.spouseCanadianDegreeDiplomaCertificate = yesNoAnswer(reply); },
		nextQuestion: function (payload) { return (payload.spouseCanadianDegreeDiplomaCertificate ? questions.spouseCanadianEducationLevel : questions.spouseFirstLanguageTest) },
	},
	spouseCanadianEducationLevel: {
		id: null,
		question: function (payload) { return "{QUOTE}What is your spouse or common-law partner's education level in Canada?" },
		options: canadianEducationLevelOptions,
		processReply: function (payload, reply) { payload.spouseCanadianEducationLevel = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageTest },
	},
	spouseFirstLanguageTest: {
		id: null,
		question: function (payload) { return "{QUOTE}Will your spouse or common-law partner take a language test?" },
		options: function (payload) {
			return ['No',
				'CELPIP',
				'IELTS',
				'TEF'];
		},
		processReply: function (payload, reply) { payload.spouseFirstLanguageTest = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return (payload.spouseFirstLanguageTest == 0 ? questions.spouseWorkExperienceInCanada : questions.spouseFirstLanguageSpeaking) },
	},
	spouseFirstLanguageSpeaking: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.speaking, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.speaking); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageSpeaking = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageListening },
	},
	spouseFirstLanguageListening: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.listening, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.listening); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageListening = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageReading },
	},
	spouseFirstLanguageReading: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.reading, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.reading); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageReading = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return questions.spouseFirstLanguageWriting },
	},
	spouseFirstLanguageWriting: {
		id: null,
		question: function (payload) { return languageQuestion(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.writing, false); },
		options: function (payload) { return languageOptions(payload.spouseFirstLanguageTest, questions.spouseFirstLanguageTest, payload, languageAbility.writing); },
		processReply: function (payload, reply) { payload.spouseFirstLanguageWriting = answerIndex(this, payload, reply) + 3; },
		nextQuestion: function (payload) { return (payload.spouseFirstLanguageTest == 0 ? questions.spouseWorkExperienceInCanada : questions.spouseSecondLanguageTest) },
	},
	spouseSecondLanguageTest: {
		id: null,
		question: function (payload) { return "{QUOTE}Did your spouse or common-law partner take a second language test?" },
		options: function (payload) {
			return (payload.spouseFirstLanguageTest == 3 ? ['No',
				'CELPIP',
				'IELTS'] : ['No',
					'TEF']);
		},
		processReply: function (payload, reply) { payload.spouseSecondLanguageTest = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return (payload.spouseSecondLanguageTest == 0 ? questions.spouseWorkExperienceInCanada : questions.spouseSecondLanguageSpeaking) },
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
		nextQuestion: function (payload) { return questions.spouseWorkExperienceInCanada },
	},
	spouseWorkExperienceInCanada: {
		id: null,
		question: function (payload) { return "{QUOTE}In the last ten years, how many years of skilled work experience in Canada does your spouse or common-law partner have?" },
		options: workExperienceInCanadaOptions,
		processReply: function (payload, reply) { payload.spouseWorkExperienceInCanada = answerIndex(this, payload, reply); },
		nextQuestion: function (payload) { return questions.spouseWorkExperienceLastTenYears },
	},
	spouseWorkExperienceLastTenYears: {
		id: null,
		question: function (payload) { return "{QUOTE}In the last 10 years, how many years of skilled work experience does your spouse or common-law partner have?" },
		options: workExperienceLastTenYearsOptions,
		processReply: function (payload, reply) { payload.spouseWorkExperienceLastTenYears = answerIndex(this, payload, reply); },
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
		question: function (payload) { return "{QUOTE}Which NOC skill type or level is the job offer?" },
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
		question: function (payload) { return calculate(payload); },
		options: yesNo,
		processReply: function (payload, reply) { payload.calculateInverted = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.calculateInverted)) {
				if (util.parseBoolean(payload.spouseCommingAlong) && payload.spouseFirstLanguageTest !== 0 && !util.parseBoolean(payload.nominationCertificate))
					return questions.calculationInverted;
				else
					return questions.startOver;
			}
			else
				return questions.done;
		},
	},
	calculationInverted: {
		id: null,
		question: function (payload) { return calculateInverted(payload); },
		options: yesNo,
		processReply: function (payload, reply) { payload.startOver = yesNoAnswer(reply); },
		nextQuestion: function (payload) {
			if (util.parseBoolean(payload.startOver)) {
				var payloadArray = Object.keys(payload);

				for (p = 0; p < payloadArray.length; p++) {
					delete payload[payloadArray[p]];
				}

				return questions.name;
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
			if (util.parseBoolean(payload.startOver)) {
				var payloadArray = Object.keys(payload);

				for (p = 0; p < payloadArray.length; p++) {
					delete payload[payloadArray[p]];
				}

				return questions.name;
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

for (q = 0; q < questionsArray.length; q++) {
	questions[questionsArray[q]].id = q;
}

function validateAnswer(question, payload, reply) {
	var options = question.options(payload);

	if (options === null)
		return true;
	else
		return (options.indexOf(reply) >= 0);
}

function questionFlow(payload, reply, callback) {
	//console.log('payload: ', payload);
	//console.log('reply: ', reply);

	if (payload === undefined) payload = {};

	var responseJSON = {
		"response": null, // what the bot will respond with (more is appended below)
		"continue": false, // denotes that Motion AI should hit this module again, rather than continue further in the flow
		"customPayload": null, // working data to examine in future calls to this function to keep track of state
		"quickReplies": null, // a JSON object containing suggested/quick replies to display to the user
		"cards": null // a cards JSON object to display a carousel to the user (see docs)
	};

	var question;

	if (Object.keys(payload).length > 0) {
		//console.log('payload.question: ', payload.question);
		question = questions[questionsArray[payload.question]];
		//console.log('question: ', question);

		//console.log('answerValid: ', validateAnswer(question, payload, reply));
		if (validateAnswer(question, payload, reply)) {
			question.processReply(payload, reply);

			//console.log('payload Before: ', payload);
			question = question.nextQuestion(payload);
			//console.log('payload After: ', payload);
		}
	}
	else {
		question = questions.name;
	}
	//console.log('nextQuestion: ', question);

	responseJSON.response = question.question(payload);
	responseJSON.quickReplies = question.options(payload);

	payload.question = question.id;

	responseJSON.response = responseJSON.response.replace('{QUOTE}', '');
	responseJSON.customPayload = payload;

	if (payload.score !== undefined) {
		responseJSON.score = payload.score;
		delete payload['score'];
	}

	if (payload.scoreInverted !== undefined) {
		responseJSON.scoreInverted = payload.scoreInverted;
		delete payload['scoreInverted'];
	}

	//console.log("responseJSON: ", responseJSON);
	return responseJSON;
}

function calculate(payload) {
	var calculator = require("./calculator");
	var analysis = require("./analysis");

	var score = "{QUOTE}";

	var parameters = {};
	var calculation;

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
	parameters.spouseLanguage.test = (payload.spouseFirstLanguageTest === undefined ? calculator.languageTest.none : parseInt(payload.spouseFirstLanguageTest));
	parameters.spouseLanguage.speaking = (payload.spouseFirstLanguageSpeaking === undefined ? 0 : parseInt(payload.spouseFirstLanguageSpeaking));
	parameters.spouseLanguage.listening = (payload.spouseFirstLanguageListening === undefined ? 0 : parseInt(payload.spouseFirstLanguageListening));
	parameters.spouseLanguage.reading = (payload.spouseFirstLanguageReading === undefined ? 0 : parseInt(payload.spouseFirstLanguageReading));
	parameters.spouseLanguage.writing = (payload.spouseFirstLanguageWriting === undefined ? 0 : parseInt(payload.spouseFirstLanguageWriting));

	calculation = calculator.calculate(parameters);
	payload.score = calculation;

	score += "Your score is " + util.formatNumber(calculation.total, 0) + ".<br /><br />" + calculator.report();

	score += analysis.analyse(parameters, calculation);

	//Calculate Inverting the roles
	if (util.parseBoolean(payload.spouseCommingAlong) && parameters.spouseLanguage.test !== calculator.languageTest.none && !parameters.provincialNomination)
		score += "<br /><br />I can do this analysis inverting your role with your spouse or common-law partner. Would you like me to do it?";
	else
		score += "<br /><br />Would you like to start over?";

	return score;
}

function calculateInverted(payload) {
	var calculator = require("./calculator");
	var analysis = require("./analysis");

	var score = "{QUOTE}";

	var parameters = {};
	var calculation;

	parameters.married = util.parseBoolean(payload.married);
	parameters.spouseCanadianCitizen = util.parseBoolean(payload.spouseCanadianCitizen);
	parameters.spouseCommingAlong = util.parseBoolean(payload.spouseCommingAlong);
	parameters.age = parseInt(payload.spouseAge);
	parameters.educationLevel = parseInt(payload.spouseEducationLevel);
	if (payload.spouseCanadianEducationLevel !== undefined) parameters.educationInCanada = parseInt(payload.spouseCanadianEducationLevel);
	parameters.firstLanguage = calculator.languageObject();
	parameters.firstLanguage.test = parseInt(payload.spouseFirstLanguageTest);
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

	calculation = calculator.calculate(parameters);
	payload.score = calculation;

	score += "Your score with your roles inverted is " + util.formatNumber(calculation.total, 0) + ".<br /><br />" + calculator.report();

	score += analysis.analyse(parameters, calculation, false);

	score += "<br /><br />Would you like to start over?";

	return score;
}

module.exports = {
	questionFlow: questionFlow
};