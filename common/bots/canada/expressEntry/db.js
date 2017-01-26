var mongo = require('mongodb');

var dataBase;
var collectionName;

mongo.connect(process.env.MONGODB_DATABASE, function(err, db) {
    dataBase = db;

	db.createCollection('chat');
	collectionName = db.collection('chat');
})

function saveChat(payload, score)
{
    saveObject = {
        time: Math.floor(Date.now() / 1000), 

        name: payload.name,
        age: payload.age,
        educationLevel: payload.educationLevel,
        canadianDegreeDiplomaCertificate: payload.canadianDegreeDiplomaCertificate, 
        canadianEducationLevel: payload.canadianEducationLevel, 
        firstLanguageTest: payload.firstLanguageTest, 
        firstLanguageSpeaking: payload.firstLanguageSpeaking, 
        firstLanguageListening: payload.firstLanguageListening, 
        firstLanguageReading: payload.firstLanguageReading, 
        firstLanguageWriting: payload.firstLanguageWriting, 
        secondLanguageTest: payload.secondLanguageTest, 
        secondLanguageSpeaking: payload.secondLanguageSpeaking, 
        secondLanguageListening: payload.secondLanguageListening, 
        secondLanguageReading: payload.secondLanguageReading, 
        secondLanguageWriting: payload.secondLanguageWriting, 
        workExperienceInCanada: payload.workExperienceInCanada, 
        workExperienceLastTenYears: payload.workExperienceLastTenYears, 
        certificateQualificationProvince: payload.certificateQualificationProvince, 
        validJobOffer: payload.validJobOffer, 
        nocJobOffer: payload.nocJobOffer, 
        nominationCertificate: payload.nominationCertificate, 
        married: payload.married, 
        
        spouseCanadianCitizen: payload.spouseCanadianCitizen, 
        spouseCommingAlong: payload.spouseCommingAlong, 
        spouseAge: payload.spouseAge, 
        spouseEducationLevel: payload.spouseEducationLevel, 
        spouseCanadianDegreeDiplomaCertificate: payload.spouseCanadianDegreeDiplomaCertificate, 
        spouseCanadianEducationLevel: payload.spouseCanadianEducationLevel, 
        spouseFirstLanguageTest: payload.spouseFirstLanguageTest, 
        spouseFirstLanguageSpeaking: payload.spouseFirstLanguageSpeaking, 
        spouseFirstLanguageListening: payload.spouseFirstLanguageListening, 
        spouseFirstLanguageReading: payload.spouseFirstLanguageReading, 
        spouseFirstLanguageWriting: payload.spouseFirstLanguageWriting, 
        spouseSecondLanguageTest: payload.spouseSecondLanguageTest, 
        spouseSecondLanguageSpeaking: payload.spouseSecondLanguageSpeaking, 
        spouseSecondLanguageListening: payload.spouseSecondLanguageListening, 
        spouseSecondLanguageReading: payload.spouseSecondLanguageReading, 
        spouseSecondLanguageWriting: payload.spouseSecondLanguageWriting, 
        spouseWorkExperienceInCanada: payload.spouseWorkExperienceInCanada, 
        spouseWorkExperienceLastTenYears: payload.spouseWorkExperienceLastTenYears, 
        spouseCertificateQualificationProvince: payload.spouseCertificateQualificationProvince, 
        spouseValidJobOffer: payload.spouseValidJobOffer, 
        spouseNocJobOffer: payload.spouseNocJobOffer, 
        spouseNominationCertificate: payload.spouseNominationCertificate,
        email: payload.email,
        
        score: score
    }

    collectionName.save(saveObject, function(err) {
        if (err) throw err;
    });
}

module.exports = {
	saveChat: saveChat
};