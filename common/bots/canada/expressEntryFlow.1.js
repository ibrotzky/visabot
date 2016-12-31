/**
 * Enum for Education Level
 * @readonly
 * @enum {number}
 */
var educationLevel = {
	/** Less than secondary school (high school) */
	LessThanSecondary: 0,
	/** Secondary diploma (high school graduation) */
	Secondary: 1,
	/** One-year degree, diploma or certificate from  a university, college, trade or technical school, or other institute */
	OneYearDegree: 2,
	/** Two-year program at a university, college, trade or technical school, or other institute */
	TwoYearDegree: 3,
	/** Bachelor's degree OR  a three or more year program at a university, college, trade or technical school, or other institute */
	BachelorsDegree: 4,
	/** Two or more certificates, diplomas, or degrees. One must be for a program of three or more years */
	TwoOrMoreDegress: 5,
	/** Master's degree, OR professional degree needed to practice in a licensed profession (For “professional degree,” the degree program must have been in: medicine, veterinary medicine, dentistry, optometry, law, chiropractic medicine, or pharmacy.) */
	MastersDegree: 6,
	/** Doctoral level university degree (Ph.D.) */
	DoctoralDegree: 7
}

/**
 * Enum for Education Level aquired in Canada
 * @readonly
 * @enum {number}
 */
var educationInCanada = {
	/** Secondary (high school) or less */
	SecondaryOrLess: 0,
	/** One-year or two-year diploma or certificate */
	OneOrTwoYearDiplomaOrCertificate: 1,
	/** Degree, diploma or certificate of three years or longer OR a Master’s, professional or doctoral degree of at least one academic year */
	ThreeOrMoreYearsDegree: 2
}

/**
 * Enum for Language Test
 * @readonly
 * @enum {number}
 */
var languageTest = {
	none: 0,
	celpip: 1,
	ielts: 2,
	tef: 3
}

/**
 * Enum for NOC List
 * @readonly
 * @enum {number}
 */
var nocList = {
	_00: 0,
	_0: 1,
	A: 2,
	B: 3,
	C: 4,
	D: 5
}

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

var yesNo = ["Yes", "No"];

var questionsArray = ['name', 'maritalStatus', 'spouseCanadianCitizen', 'spouseCommingAlong'];

/**
 * Questions to be asked
 * @readonly
 * @enum {string}
 */
var questions = {
	name: { id: '0' },
	maritalStatus: { id: '1', question: "Hi {NAME}. Are you married or has a common-law partner?", options: yesNo },
	spouseCanadianCitizen: { id: '2i', question: "Is your spouse or common-law partner a citizen or permanent resident of Canada?", options: yesNo },
	spouseCommingAlong: { id: '2ii', question: "Will your spouse or common-law partner come with you to Canada?", options: yesNo },
    age: { id: '3', question: "How old are you? (Only the numbers please)", options: ['17 or less','18', '19', '20 to 29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45 or more'] },
	educationLevel: {
		id: '4', question: "What is your education level?", options: ['Less than secondary school (high school)',
			'Secondary diploma (high school graduation)',
			'One-year program',
			'Two-year program',
			'Bachelor\'s degree OR  a three or more year program',
			'Two or more certificates, diplomas, or degrees',
			'Master\'s degree',
			'Ph.D.']
	},
	canadianDegreeDiplomaCertificate: { id: '4b', question: "", options: yesNo },
	canadianEducationLevel: { id: '4c', question: "", options: null },
	firstLanguageTest: { id: '5ii', question: "", options: null },
	firstLanguageSpeaking: { id: '5iiS', question: "", options: null },
	firstLanguageListening: { id: '5iiL', question: "", options: null },
	firstLanguageReading: { id: '5iiR', question: "", options: null },
	firstLanguageWriting: { id: '5iiW', question: "", options: null },
	secondLanguageTest: { id: '5iii', question: "", options: null },
	secondLanguageSpeaking: { id: '5iiiS', question: "", options: null },
	secondLanguageListening: { id: '5iiiL', question: "", options: null },
	secondLanguageReading: { id: '5iiiR', question: "", options: null },
	secondLanguageWriting: { id: '5iiiW', question: "", options: null },
	workExperienceInCanada: { id: '6i', question: "", options: null },
	workExperienceLastTenYears: { id: '6ii', question: "", options: null },
	certificateQualificationProvince: { id: '7', question: "", options: yesNo },
	validJobOffer: { id: '8', question: "", options: yesNo },
	jobOfferNoc: { id: '8a', question: "", options: null },
	nominationCertificate: { id: '9', question: "", options: yesNo },
	spouseEducationLevel: { id: '10', question: "", options: null },
	spouseWorkExperienceInCanada: { id: '11', question: "", options: null },
	spouseLanguageTest: { id: '12', question: "", options: null },
	spouseLanguageSpeaking: { id: '12S', question: "", options: null },
	spouseLanguageListening: { id: '12L', question: "", options: null },
	spouseLanguageReading: { id: '12R', question: "", options: null },
	spouseLanguageWriting: { id: '12W', question: "", options: null },
	calculate: { id: 'CALC', question: "", options: null }
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

var questionFlow = function (payload, reply, callback) {
	//console.log('payload: ', payload);
	//console.log('reply: ', reply);

	if (payload === undefined || payload === '') payload = null;

	if (typeof (payload) === 'string')
		payload = JSON.parse(payload);

	var responseJSON = {
		"response": null, // what the bot will respond with (more is appended below)
		"continue": false, // denotes that Motion AI should hit this module again, rather than continue further in the flow
		"customPayload": null, // working data to examine in future calls to this function to keep track of state
		"quickReplies": null, // a JSON object containing suggested/quick replies to display to the user
		"cards": null // a cards JSON object to display a carousel to the user (see docs)
	};

	if (payload == null)
	{
		payload = {
			question: questions.name
		}

		responseJSON.response = "Hello, nice to meet you.\nI'm CanadaBot. What is your name?";
	}
	else
	{
		var repeatQuestion = false;

        payload.question = payload.question.toString();

        //var answerIndex = payload.options.indexOf(reply);

		// Stores the answer in it's respective property
		switch (payload.question)
		{
			case questions.name.id:
				payload.name = reply;
				break;

			case questions.maritalStatus.id:
				payload.married = (reply === 'Yes');
				break;

			case questions.spouseCanadianCitizen.id:
				payload.spouseCanadianCitizen = (reply === 'Yes');
				if (!payload.spouseCanadianCitizen) payload.spouseCommingAlong = false;
				break;

			case questions.spouseCommingAlong.id:
				payload.spouseCommingAlong = (reply === 'Yes');
				break;

			case questions.age.id:
				if (isNaN(reply))
					repeatQuestion = true;
				else
					payload.age = parseInt(reply);
				break;

			case questions.educationLevel.id:
				switch (reply)
				{
					case 'Less than secondary school (high school)':
						payload.educationLevel = educationLevel.LessThanSecondary;
						break;

					case 'Secondary diploma (high school graduation)':
						payload.educationLevel = educationLevel.Secondary;
						break;

					case 'One-year degree, diploma or certificate from  a university, college, trade or technical school, or other institute':
						payload.educationLevel = educationLevel.OneYearDegree;
						break;

					case 'Two-year program at a university, college, trade or technical school, or other institute':
						payload.educationLevel = educationLevel.TwoYearDegree;
						break;

					case 'Bachelor\'s degree OR  a three or more year program at a university, college, trade or technical school, or other institute':
						payload.educationLevel = educationLevel.BachelorsDegree;
						break;

					case 'Two or more certificates, diplomas, or degrees. One must be for a program of three or more years':
						payload.educationLevel = educationLevel.TwoOrMoreDegress;
						break;

					case 'Master\'s degree, OR professional degree needed to practice in a licensed profession (For “professional degree,” the degree program must have been in: medicine, veterinary medicine, dentistry, optometry, law, chiropractic medicine, or pharmacy.)':
						payload.educationLevel = educationLevel.MastersDegree;
						break;

					case 'Doctoral level university degree (Ph.D.)':
						payload.educationLevel = educationLevel.DoctoralDegree;
						break;

					default:
						repeatQuestion = true;
						break;
				}
				break;

			case questions.canadianDegreeDiplomaCertificate.id:
				break;

			case questions.canadianEducationLevel.id:
				switch (reply)
				{
					case 'Secondary (high school) or less':
						payload.educationInCanada = educationInCanada.SecondaryOrLess;
						break;

					case 'One-year or two-year diploma or certificate':
						payload.educationInCanada = educationInCanada.OneOrTwoYearDiplomaOrCertificate;
						break;

					case 'Degree, diploma or certificate of three years or longer OR a Master’s, professional or doctoral degree of at least one academic year':
						payload.educationInCanada = educationInCanada.ThreeOrMoreYearsDegree;
						break;

					default:
						repeatQuestion = true;
						break;
				}
				break;

			case questions.firstLanguageTest.id:
				payload.firstLanguage = languageObject();

				switch (reply)
				{
					case 'No':
						payload.firstLanguage.test = languageTest.none;
						break;

					case 'CELPIP':
						payload.firstLanguage.test = languageTest.celpip;
						break;

					case 'IELTS':
						payload.firstLanguage.test = languageTest.ielts;
						break;

					case 'TEF':
						payload.firstLanguage.test = languageTest.tef;
						break;

					default:
						repeatQuestion = true;
						break;
				}
				break;

			case questions.firstLanguageSpeaking.id:
				if (validateLanguageScore(payload.firstLanguage.test, languageAbility.speaking, reply))
					payload.firstLanguage.speaking = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.firstLanguageListening.id:
				if (validateLanguageScore(payload.firstLanguage.test, languageAbility.listening, reply))
					payload.firstLanguage.listening = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.firstLanguageReading.id:
				if (validateLanguageScore(payload.firstLanguage.test, languageAbility.reading, reply))
					payload.firstLanguage.reading = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.firstLanguageWriting.id:
				if (validateLanguageScore(payload.firstLanguage.test, languageAbility.writing, reply))
					payload.firstLanguage.writing = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.secondLanguageTest.id:
				payload.secondLanguage = languageObject();

				switch (reply)
				{
					case 'No':
						payload.secondLanguage.test = languageTest.none;
						break;

					case 'CELPIP':
						payload.secondLanguage.test = languageTest.celpip;
						break;

					case 'IELTS':
						payload.secondLanguage.test = languageTest.ielts;
						break;

					case 'TEF':
						payload.secondLanguage.test = languageTest.tef;
						break;

					default:
						repeatQuestion = true;
						break;
				}
				break;

			case questions.secondLanguageSpeaking.id:
				if (validateLanguageScore(payload.secondLanguage.test, languageAbility.speaking, reply))
					payload.secondLanguage.speaking = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.secondLanguageListening.id:
				if (validateLanguageScore(payload.secondLanguage.test, languageAbility.listening, reply))
					payload.secondLanguage.listening = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.secondLanguageReading.id:
				if (validateLanguageScore(payload.secondLanguage.test, languageAbility.reading, reply))
					payload.secondLanguage.reading = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.secondLanguageWriting.id:
				if (validateLanguageScore(payload.secondLanguage.test, languageAbility.writing, reply))
					payload.secondLanguage.writing = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.workExperienceInCanada.id:
				if (isNaN(reply))
					repeatQuestion = true;
				else
					payload.workInCanada = parseFloat(reply);
				break;

			case questions.workExperienceLastTenYears.id:
				if (isNaN(reply))
					repeatQuestion = true;
				else
					payload.workExperience = parseFloat(reply);
				break;

			case questions.certificateQualificationProvince.id:
				payload.certificateFromProvince = (reply === 'Yes');
				break;

			case questions.validJobOffer.id:
				payload.validJobOffer = (reply === 'Yes');
				break;

			case questions.jobOfferNoc.id:
				switch (reply)
				{
					case '00':
						payload.nocJobOffer = nocList._00;
						break;

					case '0':
						payload.nocJobOffer = nocList._0;
						break;

					case 'A':
						payload.nocJobOffer = nocList.A;
						break;

					case 'B':
						payload.nocJobOffer = nocList.B;
						break;

					case 'C':
						payload.nocJobOffer = nocList.C;
						break;

					case 'D':
						payload.nocJobOffer = nocList.D;
						break;

					default:
						repeatQuestion = true;
						break;
				}
				break;

			case questions.nominationCertificate.id:
				payload.nomination = (reply === 'Yes');
				break;

			case questions.spouseEducationLevel.id:
				switch (reply)
				{
					case 'Less than secondary school (high school)':
						payload.spouseEducationLevel = educationLevel.LessThanSecondary;
						break;

					case 'Secondary diploma (high school graduation)':
						payload.spouseEducationLevel = educationLevel.Secondary;

						break;
					case 'One-year degree, diploma or certificate from  a university, college, trade or technical school, or other institute':
						payload.spouseEducationLevel = educationLevel.OneYearDegree;

						break;
					case 'Two-year program at a university, college, trade or technical school, or other institute':
						payload.spouseEducationLevel = educationLevel.TwoYearDegree;

						break;
					case 'Bachelor\'s degree OR  a three or more year program at a university, college, trade or technical school, or other institute':
						payload.spouseEducationLevel = educationLevel.BachelorsDegree;

						break;
					case 'Two or more certificates, diplomas, or degrees. One must be for a program of three or more years':
						payload.spouseEducationLevel = educationLevel.TwoOrMoreDegress;

						break;
					case 'Master\'s degree, OR professional degree needed to practice in a licensed profession (For “professional degree,” the degree program must have been in: medicine, veterinary medicine, dentistry, optometry, law, chiropractic medicine, or pharmacy.)':
						payload.spouseEducationLevel = educationLevel.MastersDegree;

						break;
					case 'Doctoral level university degree (Ph.D.)':
						payload.spouseEducationLevel = educationLevel.DoctoralDegree;

						break;

					default:
						repeatQuestion = true;
						break;
				}
				break;

			case questions.spouseWorkExperienceInCanada.id:
				if (isNaN(reply))
					repeatQuestion = true;
				else
					payload.spouseWorkInCanada = parseFloat(reply);
				break;

			case questions.spouseLanguageTest.id:
				payload.spouseLanguage = languageObject();

				switch (reply)
				{
					case 'No':
						payload.spouseLanguage.test = languageTest.none;
						break;

					case 'CELPIP':
						payload.spouseLanguage.test = languageTest.celpip;
						break;

					case 'IELTS':
						payload.spouseLanguage.test = languageTest.ielts;
						break;

					case 'TEF':
						payload.spouseLanguage.test = languageTest.tef;
						break;

					default:
						repeatQuestion = true;
						break;
				}
				break;

			case questions.spouseLanguageSpeaking.id:
				if (validateLanguageScore(payload.spouseLanguage.test, languageAbility.speaking, reply))
					payload.spouseLanguage.speaking = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.spouseLanguageListening.id:
				if (validateLanguageScore(payload.spouseLanguage.test, languageAbility.listening, reply))
					payload.spouseLanguage.listening = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.spouseLanguageReading.id:
				if (validateLanguageScore(payload.spouseLanguage.test, languageAbility.reading, reply))
					payload.spouseLanguage.reading = parseFloat(reply);
				else
					repeatQuestion = true;
				break;

			case questions.spouseLanguageWriting.id:
				if (validateLanguageScore(payload.spouseLanguage.test, languageAbility.writing, reply))
					payload.spouseLanguage.writing = parseFloat(reply);
				else
					repeatQuestion = true;
				break;
		}

		if (!repeatQuestion)
		{
			// Defines the next question
			switch (payload.question)
			{
				case questions.name.id:
					payload.question = questions.maritalStatus;
					break;

				case questions.maritalStatus.id:
					if (payload.married)
						payload.question = questions.spouseCanadianCitizen;
					else
						payload.question = questions.age;
					break;

				case questions.spouseCanadianCitizen.id:
					if (payload.spouseCanadianCitizen)
						payload.question = questions.age;
					else
						payload.question = questions.spouseCommingAlong;
					break;

				case questions.spouseCommingAlong.id:
					payload.question = questions.age;
					break;

				case questions.age.id:
					payload.question = questions.educationLevel;
					break;

				case questions.educationLevel.id:
					payload.question = questions.canadianDegreeDiplomaCertificate;
					break;

				case questions.canadianDegreeDiplomaCertificate.id:
					if (reply === 'Yes')
						payload.question = questions.canadianEducationLevel;
					else
						payload.question = questions.firstLanguageTest;
					break;

				case questions.canadianEducationLevel.id:
					payload.question = questions.firstLanguageTest;
					break;

				case questions.firstLanguageTest.id:
					payload.question = questions.firstLanguageSpeaking;
					break;

				case questions.firstLanguageSpeaking.id:
					payload.question = questions.firstLanguageListening;
					break;

				case questions.firstLanguageListening.id:
					payload.question = questions.firstLanguageReading;
					break;

				case questions.firstLanguageReading.id:
					payload.question = questions.firstLanguageWriting;
					break;

				case questions.firstLanguageWriting.id:
					if (payload.firstLanguage.test == languageTest.none)
						payload.question = questions.workExperienceInCanada;
					else
						payload.question = questions.secondLanguageTest;
					break;

				case questions.secondLanguageTest.id:
					if (payload.secondLanguage.test == languageTest.none)
						payload.question = questions.workExperienceInCanada;
					else
						payload.question = questions.secondLanguageSpeaking;
					break;

				case questions.secondLanguageSpeaking.id:
					payload.question = questions.secondLanguageListening;
					break;

				case questions.secondLanguageListening.id:
					payload.question = questions.secondLanguageReading;
					break;

				case questions.secondLanguageReading.id:
					payload.question = questions.secondLanguageWriting;
					break;

				case questions.secondLanguageWriting.id:
					payload.question = questions.workExperienceInCanada;
					break;

				case questions.workExperienceInCanada.id:
					payload.question = questions.workExperienceLastTenYears;
					break;

				case questions.workExperienceLastTenYears.id:
					payload.question = questions.certificateQualificationProvince;
					break;

				case questions.certificateQualificationProvince.id:
					payload.question = questions.validJobOffer;
					break;

				case questions.validJobOffer.id:
					if (payload.validJobOffer)
						payload.question = questions.jobOfferNoc;
					else
						payload.question = questions.nominationCertificate;
					break;

				case questions.jobOfferNoc.id:
					payload.question = questions.nominationCertificate;
					break;

				case questions.nominationCertificate.id:
					if (Boolean.parse(payload.married))
					{
						if (Boolean.parse(payload.spouseCanadianCitizen))
							payload.question = questions.calculate;
						else
						{
							if (Boolean.parse(payload.spouseCommingAlong))
								payload.question = questions.spouseEducationLevel;
							else
								payload.question = questions.calculate;
						}
					}
					else
						payload.question = questions.calculate;
					break;

				case questions.spouseEducationLevel.id:
					payload.question = questions.spouseWorkExperienceInCanada;
					break;

				case questions.spouseWorkExperienceInCanada.id:
					payload.question = questions.spouseLanguageTest;
					break;

				case questions.spouseLanguageTest.id:
					if (payload.spouseLanguage.test == languageTest.none)
						payload.question = questions.calculate;
					else
						payload.question = questions.spouseLanguageSpeaking;
					break;

				case questions.spouseLanguageSpeaking.id:
					payload.question = questions.spouseLanguageListening;
					break;

				case questions.spouseLanguageListening.id:
					payload.question = questions.spouseLanguageReading;
					break;

				case questions.spouseLanguageReading.id:
					payload.question = questions.spouseLanguageWriting;
					break;

				case questions.spouseLanguageWriting.id:
					payload.question = questions.calculate;
					break;
			}
		}

		// Fill up the responseJSON for the next question
		responseJSON.response = payload.question.question;
		responseJSON.quickReplies = payload.question.options;

		switch (payload.question.id)
		{
			case questions.maritalStatus.id:
				responseJSON.response = responseJSON.response.replace('{NAME}', payload.name);
				break;

			/*
				
			case questions.educationLevel.id:
				responseJSON.response = "What is your education level?";
				responseJSON.quickReplies = ['Less than secondary school (high school)',
					'Secondary diploma (high school graduation)',
					'One-year degree, diploma or certificate from  a university, college, trade or technical school, or other institute',
					'Two-year program at a university, college, trade or technical school, or other institute',
					'Bachelor\'s degree OR  a three or more year program at a university, college, trade or technical school, or other institute',
					'Two or more certificates, diplomas, or degrees. One must be for a program of three or more years',
					'Master\'s degree, OR professional degree needed to practice in a licensed profession (For “professional degree,” the degree program must have been in: medicine, veterinary medicine, dentistry, optometry, law, chiropractic medicine, or pharmacy.)',
					'Doctoral level university degree (Ph.D.)'];
				break;

			case questions.canadianDegreeDiplomaCertificate.id:
				responseJSON.response = "Have you earned a Canadian degree, diploma or certificate?";
				responseJSON.quickReplies = ['Yes', 'No'];
				break;

			case questions.canadianEducationLevel.id:
				responseJSON.response = "What is your education level in Canada?";
				responseJSON.quickReplies = ['Secondary (high school) or less',
					'One-year or two-year diploma or certificate',
					'Degree, diploma or certificate of three years or longer OR a Master’s, professional or doctoral degree of at least one academic year'];
				break;

			case questions.firstLanguageTest.id:
				responseJSON.response = "Did you take a language test?";
				responseJSON.quickReplies = ['No',
					'CELPIP',
					'IELTS',
					'TEF'];
				break;

			case questions.firstLanguageSpeaking.id:
				switch (parseInt(payload.firstLanguage.test.id))
				{
					case languageTest.none:
						responseJSON.response = "In a scale of 0 to 10, where 0 you cannot speak English at all and 10 you master it, how good do you Speak in English?";
						break;

					case languageTest.celpip:
						responseJSON.response = "What is your Speaking Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Speaking Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Speaking Score on the TEF test?";
						break;

				}
				break;

			case questions.firstLanguageListening.id:
				switch (parseInt(payload.firstLanguage.test))
				{
					case languageTest.none:
						responseJSON.response = "In a scale of 0 to 10, where 0 you cannot understand spoken English at all and 10 you master it, how good do you Understand Spoken English?";
						break;

					case languageTest.celpip:
						responseJSON.response = "What is your Listening Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Listening Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Listening Score on the TEF test?";
						break;

				}
				break;

			case questions.firstLanguageReading.id:
				switch (parseInt(payload.firstLanguage.test))
				{
					case languageTest.none:
						responseJSON.response = "In a scale of 0 to 10, where 0 you cannot read English at all and 10 you master it, how good do you Read in English?";
						break;

					case languageTest.celpip:
						responseJSON.response = "What is your Reading Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Reading Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Reading Score on the TEF test?";
						break;

				}
				break;

			case questions.firstLanguageWriting.id:
				switch (parseInt(payload.firstLanguage.test))
				{
					case languageTest.none:
						responseJSON.response = "In a scale of 0 to 10, where 0 you cannot write in English at all and 10 you master it, how good do you Write in English?";
						break;

					case languageTest.celpip:
						responseJSON.response = "What is your Writing Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Writing Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Writing Score on the TEF test?";
						break;

				}
				break;

			case questions.secondLanguageTest.id:
				responseJSON.response = "Did you take a second language test?";

				if (payload.firstLanguage.test == languageTest.tef)
					responseJSON.quickReplies = ['No',
						'CELPIP',
						'IELTS'];
				else
					responseJSON.quickReplies = ['No',
						'TEF'];
				break;

			case questions.secondLanguageSpeaking.id:
				switch (parseInt(payload.secondLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your Speaking Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Speaking Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Speaking Score on the TEF test?";
						break;

				}
				break;

			case questions.secondLanguageListening.id:
				switch (parseInt(payload.secondLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your Listening Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Listening Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Listening Score on the TEF test?";
						break;

				}
				break;

			case questions.secondLanguageReading.id:
				switch (parseInt(payload.secondLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your Reading Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Reading Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Reading Score on the TEF test?";
						break;

				}
				break;

			case questions.secondLanguageWriting.id:
				switch (parseInt(payload.secondLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your Writing Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your Writing Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Writing Score on the TEF test?";
						break;

				}
				break;

			case questions.workExperienceInCanada.id:
				responseJSON.response = "In the last ten years, how many years of skilled work experience in Canada do you have?";
				break;

			case questions.workExperienceLastTenYears.id:
				responseJSON.response = "How many years of skilled work experience, in total, do you have?";
				break;

			case questions.certificateQualificationProvince.id:
				responseJSON.response = "Do you have a certificate of qualification from a Canadian province or territory?";
				responseJSON.quickReplies = ['Yes', 'No'];
				break;

			case questions.validJobOffer.id:
				responseJSON.response = "Do you have a valid job offer supported by a Labour Market Impact Assessment (if needed)?";
				responseJSON.quickReplies = ['Yes', 'No'];
				break;

			case questions.jobOfferNoc.id:
				responseJSON.response = "Which NOC skill type or level is the job offer?";
				responseJSON.quickReplies = ['00', '0', 'A', 'B', 'C', 'D'];
				break;

			case questions.nominationCertificate.id:
				responseJSON.response = "Do you have a nomination certificate from a province or territory?";
				responseJSON.quickReplies = ['Yes', 'No'];
				break;

			case questions.spouseEducationLevel.id:
				responseJSON.response = "What is your spouse or common-law partner's education level?";
				responseJSON.quickReplies = ['Less than secondary school (high school)',
					'Secondary diploma (high school graduation)',
					'One-year degree, diploma or certificate from  a university, college, trade or technical school, or other institute',
					'Two-year program at a university, college, trade or technical school, or other institute',
					'Bachelor\'s degree OR  a three or more year program at a university, college, trade or technical school, or other institute',
					'Two or more certificates, diplomas, or degrees. One must be for a program of three or more years',
					'Master\'s degree, OR professional degree needed to practice in a licensed profession (For “professional degree,” the degree program must have been in: medicine, veterinary medicine, dentistry, optometry, law, chiropractic medicine, or pharmacy.)',
					'Doctoral level university degree (Ph.D.)'];
				break;

			case questions.spouseWorkExperienceInCanada.id:
				responseJSON.response = "In the last ten years, how many years of skilled work experience in Canada does your spouse/common-law partner have?";
				break;

			case questions.spouseLanguageTest.id:
				responseJSON.response = "Did your your spouse or common-law partner take a language test?";
				responseJSON.quickReplies = ['No',
					'CELPIP',
					'IELTS',
					'TEF'];
				break;

			case questions.spouseLanguageSpeaking.id:
				switch (parseInt(payload.spouseLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your spouse or common-law partner Speaking Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your spouse or common-law partner Speaking Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your Sspouse or common-law partner peaking Score on the TEF test?";
						break;

				}
				break;

			case questions.spouseLanguageListening.id:
				switch (parseInt(payload.spouseLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your spouse or common-law partner Listening Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your spouse or common-law partner Listening Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your spouse or common-law partner Listening Score on the TEF test?";
						break;

				}
				break;

			case questions.spouseLanguageReading.id:
				switch (parseInt(payload.spouseLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your spouse or common-law partner Reading Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your spouse or common-law partner Reading Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your spouse or common-law partner Reading Score on the TEF test?";
						break;

				}
				break;

			case questions.spouseLanguageWriting.id:
				switch (parseInt(payload.spouseLanguage.test))
				{
					case languageTest.celpip:
						responseJSON.response = "What is your spouse or common-law partner Writing Score on the CELIP test?";
						break;

					case languageTest.ielts:
						responseJSON.response = "What is your spouse or common-law partner Writing Score on the IELTS test?";
						break;

					case languageTest.tef:
						responseJSON.response = "What is your spouse or common-law partner Writing Score on the TEF test?";
						break;

				}
				break;*/
		}

		payload.question = payload.question.id;
	}

	responseJSON.customPayload = payload;

    //console.log("responseJSON: ", responseJSON);

	return responseJSON;
}

module.exports = {
	questionFlow: questionFlow
};