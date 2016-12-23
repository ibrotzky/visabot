module.exports = function (Bot) {
    Bot.calculate = function (applicant, cb) {
        var ee = new calculator_EE20161119();

        // Determine if calculate as married or single
        ee.married = applicant.married;
        ee.spouseCanadianCitizen = applicant.spouseWorkInCanada;
        ee.spouseCommingAlong = applicant.spouseCommingAlong;

        // Core/Humam Capital Factors
        ee.age = applicant.age;
        ee.educationLevel = applicant.educationLevel;
        ee.firstLanguage.test = applicant.languageTest;
        ee.firstLanguage.speaking = applicant.firstLanguage.speaking;
        ee.firstLanguage.listening = applicant.firstLanguage.listening;
        ee.firstLanguage.reading = applicant.firstLanguage.reading;
        ee.firstLanguage.writting = applicant.firstLanguage.writting;
        ee.workInCanada = applicant.workInCanada;
        ee.workExperience = applicant.workExperience;

        // Spouse Factors
        ee.spouseEducationLevel = applicant.spouse.educationLevel;
        ee.spouseLanguage.test = applicant.languageTest;
        ee.spouseLanguage.speaking = applicant.spouse.speaking;
        ee.spouseLanguage.listening = applicant.spouse.speaking;
        ee.spouseLanguage.reading = applicant.spouse.speaking;
        ee.spouseLanguage.writting = applicant.spouse.writting;
        ee.spouseWorkInCanada = applicant.spouse.workInCanada;

        // Skill Transferability Factors
        ee.certificateFromProvince = applicant.certificateFromProvince;
        ee.nocJobOffer = applicant.nocJobOffer;
        ee.nomination = applicant.nomination;

        var scores = ee.calculate();
        //console.log(scores);
        var response = 'Your score is ' + scores.total;

        cb(null, response);
    };

    Bot.remoteMethod(
        'calculate', {
            http: {
                path: '/calculate',
                verb: 'get'
            },
            accepts: [
                { arg: 'applicant', type: 'object' }
            ],
            returns: {
                arg: 'response',
                type: 'string'
            }
        }
    );
    /*
    /**
 * Enum for Education Level
 * @readonly
 * @enum {number}
 */
    var educationLevel = {
        /** Less than secondary school (high school) */
        LessThanSecondary: 1,
        /** Secondary diploma (high school graduation) */
        Secondary: 2,
        /** One-year degree, diploma or certificate from  a university, college, trade or technical school, or other institute */
        OneYearDegree: 3,
        /** Two-year program at a university, college, trade or technical school, or other institute */
        TwoYearDegree: 4,
        /** Bachelor's degree OR  a three or more year program at a university, college, trade or technical school, or other institute */
        BachelorsDegree: 5,
        /** Two or more certificates, diplomas, or degrees. One must be for a program of three or more years */
        TwoOrMoreDegress: 6,
        /** Master's degree, OR professional degree needed to practice in a licensed profession (For “professional degree,” the degree program must have been in: medicine, veterinary medicine, dentistry, optometry, law, chiropractic medicine, or pharmacy.) */
        MastersDegree: 7,
        /** Doctoral level university degree (Ph.D.) */
        DoctoralDegree: 8
    }

    /**
     * Enum for Education Level aquired in Canada
     * @readonly
     * @enum {number}
     */
    var educationInCanada = {
        /** Secondary (high school) or less */
        SecondaryOrLess: 1,
        /** One-year or two-year diploma or certificate */
        OneOrTwoYearDiplomaOrCertificate: 2,
        /** Degree, diploma or certificate of three years or longer OR a Master’s, professional or doctoral degree of at least one academic year */
        ThreeOrMoreYearsDegree: 3
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
     * Enum for Language Test
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
     * Calculates the score for the Express Entry.
     *
     * @author Bruno Miranda
     */
    function calculator_EE20161119() {
        /** {boolean} Is the principal applicant married? */
        this.married = null;
        /** {boolean} Is the spouse a Canadian Citizen? */
        this.spouseCanadianCitizen = null;
        /** {boolean} Is the spouse coming along? */
        this.spouseCommingAlong = null;

        /** {integer} Age of the principal applicant */
        this.age = null; // integer
        /** {enum} Education Level of the principal applicant
         * 
         * Use the educationLevel enum
         */
        this.educationLevel = null; // enum
        /** {enum} Education the principal applicant aquired in Canada
         * 
         * Use the educationInCanada enum
         */
        this.educationInCanada = null; // enum

        /** {object} Scores for the first language */
        this.firstLanguage = {
            /** {enum} Language test taken
             * 
             * Use the languageTest enum
             */
            test: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 450
             */
            speaking: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 360
             */
            listening: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 300
             */
            reading: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 450
             */
            writting: null
        }

        /** {object} Scores for the second language */
        this.secondLanguage = {
            /** {enum} Language test taken
             * 
             * Use the languageTest enum
             */
            test: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 450
             */
            speaking: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 360
             */
            listening: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 300
             */
            reading: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 450
             */
            writting: null
        }

        /** {number} How many years have the principal applicant worked in Canada? */
        this.workInCanada = null;
        /** {number} How many years of experience the principal applicant have? */
        this.workExperience = null;

        /** {boolean} Does the principal applicant have a Certificate from a Province? */
        this.certificateFromProvince = null;
        /** {enum} Does the principal applicant have a Valid Job Offer?
         * 
         * Use the nocList enum
         */
        this.nocJobOffer = null;
        /** {boolean} Does the principal applicant have a Nomination from a Province? */
        this.nomination = null;

        /** {enum} Education Level of the spouse
         * 
         * Use the educationLevel enum
         */
        this.spouseEducationLevel = null; // enum
        /** {number} How many years have the spouse worked in Canada? */
        this.spouseWorkInCanada = null; // enum

        /** {object} Scores for the language of the spouse */
        this.spouseLanguage = {
            /** {enum} Language test taken
             * 
             * Use the languageTest enum
             */
            test: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 450
             */
            speaking: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 360
             */
            listening: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 300
             */
            reading: null,
            /** None: 0 to 10
             * 
             * CELPIP: 0 to 12
             * 
             * IELTS: 0.0 to 9.0
             * 
             * TEF: 0 to 450
             */
            writting: null
        }

        /** Object returned with the scores for the Express Entry */
        this.scores = {
            coreHumanCapitalFactors: {
                age: null,
                levelOfEducation: null,
                studyInCadada: null,
                officialLanguages: {
                    first: {
                        speaking: 0,
                        listening: 0,
                        reading: 0,
                        writting: 0,
                        total: 0
                    },
                    second: {
                        speaking: 0,
                        listening: 0,
                        reading: 0,
                        writting: 0,
                        total: 0
                    }
                },
                canadianWorkExperience: null,
                subTotal: null
            },
            spouseFactors: {
                levelOfEducation: null,
                officialLanguage: {
                    speaking: 0,
                    listening: 0,
                    reading: 0,
                    writting: 0,
                    total: 0
                },
                canadianWorkExperience: null,
                subTotal: null
            },
            skillTransferabilityFactors: {
                education: {
                    officialLanguageProficiency: 0,
                    canadianWorkExperience: 0,
                    subTotal: 0
                },
                foreignWorkExperience: {
                    officialLanguageProficiency: 0,
                    canadianWorkExperience: 0,
                    subTotal: 0
                },
                certificateOfQualification: 0,
                subTotal: 0
            },
            additionalPoints: {
                studyInCadada: 0,
                jobOffer: 0,
                provincialNomination: 0,
                subTotal: 0
            },
            total: null
        }




        /**
         * Calculates the Express Entry Score.
         * 
         * @returns Returns an object that contains all the scores for each section of the Comprehensive Ranking System.
         * 
         * If there's any missing information that makes it impossible to calculate the score, an array with the missing properties is returned.
         * 
         * @author Bruno Miranda
         */
        this.calculate = function () {
            var valid = validate(this);

            if (valid === true) {
                this.scores.coreHumanCapitalFactors = calculateCoreHumanCapitalFactors(this);
                this.scores.spouseFactors = calculateSpouseFactors(this);
                this.scores.skillTransferabilityFactors = calculateSkillTransferabilityFactors(this);
                this.scores.additionalPoints = calculateAdditionalPoins(this);

                this.scores.total = this.scores.coreHumanCapitalFactors.subTotal +
                    this.scores.spouseFactors.subTotal +
                    this.scores.skillTransferabilityFactors.subTotal +
                    this.scores.additionalPoints.subTotal;

                return this.scores;
            }
            else {
                return valid;
            }
        }

        /**
         * Calculates the score for the Additional Points
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {object} Returns the ammount of points given for the section. 
         *
         * @author Bruno Miranda
         */
        var calculateAdditionalPoins = function (calculator) {
            var additionalPoints = {
                studyInCadada: 0,
                jobOffer: 0,
                provincialNomination: 0,
                subTotal: 0
            }

            additionalPoints.studyInCadada = calculateEducationInCanada(calculator);
            additionalPoints.jobOffer = calculateJobOffer(calculator);
            additionalPoints.provincialNomination = calculateProvincialNomination(calculator);

            additionalPoints.subTotal = additionalPoints.studyInCadada +
                additionalPoints.jobOffer +
                additionalPoints.provincialNomination;

            return additionalPoints;
        }

        /**
         * Calculates the score for the age.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given. 
         *
         * @author Bruno Miranda
         */
        var calculateAge = function (calculator) {
            switch (calculator.age) {
                case 18:
                    return (isSingle(calculator) ? 99 : 90);

                case 19:
                    return (isSingle(calculator) ? 105 : 95);

                case 20:
                case 21:
                case 22:
                case 23:
                case 24:
                case 25:
                case 26:
                case 27:
                case 28:
                case 29:
                    return (isSingle(calculator) ? 110 : 100);

                case 30:
                    return (isSingle(calculator) ? 105 : 95);

                case 31:
                    return (isSingle(calculator) ? 99 : 90);

                case 32:
                    return (isSingle(calculator) ? 94 : 85);

                case 33:
                    return (isSingle(calculator) ? 88 : 80);

                case 34:
                    return (isSingle(calculator) ? 83 : 75);

                case 35:
                    return (isSingle(calculator) ? 77 : 70);

                case 36:
                    return (isSingle(calculator) ? 72 : 65);

                case 37:
                    return (isSingle(calculator) ? 66 : 60);

                case 38:
                    return (isSingle(calculator) ? 61 : 55);

                case 39:
                    return (isSingle(calculator) ? 55 : 50);

                case 40:
                    return (isSingle(calculator) ? 50 : 45);

                case 41:
                    return (isSingle(calculator) ? 39 : 35);

                case 42:
                    return (isSingle(calculator) ? 28 : 25);

                case 43:
                    return (isSingle(calculator) ? 17 : 15);

                case 44:
                    return (isSingle(calculator) ? 6 : 5);
            }

            return 0;
        }

        /**
         * Calculates the CLB levels for the CELPIP test.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {object} language Pass the first or second language object
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * @param {boolean} firstLanguage Indicates if the score is for the first or second language 
         * 
         * @returns {object} Returns the CLB levels for each skill. 
         *
         * @author Bruno Miranda
         */
        var calculateCelpip = function (calculator, language, principalApplicant, firstLanguage) {
            var clbs = {
                speaking: language.speaking,
                listening: language.listening,
                reading: language.reading,
                writting: language.writting
            }

            if (clbs.speaking <= 3)
                clbs.speaking = 0;
            else if (clbs.speaking >= 10)
                clbs.speaking = 10;

            if (clbs.listening <= 3)
                clbs.listening = 0;
            else if (clbs.listening >= 10)
                clbs.listening = 10;

            if (clbs.reading <= 3)
                clbs.reading = 0;
            else if (clbs.reading >= 10)
                clbs.reading = 10;

            if (clbs.writting <= 3)
                clbs.writting = 0;
            else if (clbs.writting >= 10)
                clbs.writting = 10;

            return result;
        }

        /**
         * Calculates the score for the certificate of qualification transferability.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {number} clb5Count Quantity of CLB5 or higher on the laguage skills 
         * @param {number} clb7Count Quantity of CLB7 or higher on the laguage skills 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given.
         *
         * @author Bruno Miranda 
         */
        var calculateCertificateOfQualitication = function (calculator, clb5Count, clb7Count) {
            if (calculator.certificateFromProvince === true) {
                if (clb5Count === 4 && clb7Count < 4)
                    return 25
                else if (clb7Count === 4)
                    return 50;
            }

            return 0;
        }

        /**
         * Calculates the score for the Language test based on the CLB skill.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {object} clbLevel Pass the clb level of the skill
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * @param {boolean} firstLanguage Indicates if the score is for the first or second language 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {object} Returns the ammount of points given for each skill. 
         *
         * @author Bruno Miranda
         */
        var calculateCLB = function (calculator, clbLevel, principalApplicant, firstLanguage) {
            if (principalApplicant) {
                switch (clbLevel) {
                    case 4:
                        return (firstLanguage ? (isSingle(calculator) ? 6 : 6) : (isSingle(calculator) ? 0 : 0));

                    case 5:
                        return (firstLanguage ? (isSingle(calculator) ? 6 : 6) : (isSingle(calculator) ? 1 : 1));

                    case 6:
                        return (firstLanguage ? (isSingle(calculator) ? 9 : 8) : (isSingle(calculator) ? 1 : 1));

                    case 7:
                        return (firstLanguage ? (isSingle(calculator) ? 17 : 16) : (isSingle(calculator) ? 3 : 3));

                    case 8:
                        return (firstLanguage ? (isSingle(calculator) ? 23 : 22) : (isSingle(calculator) ? 3 : 3));

                    case 9:
                        return (firstLanguage ? (isSingle(calculator) ? 31 : 29) : (isSingle(calculator) ? 6 : 6));

                    case 10:
                        return (firstLanguage ? (isSingle(calculator) ? 34 : 32) : (isSingle(calculator) ? 6 : 6));
                }
            }
            else {
                switch (clbLevel) {
                    case 4:
                        return 0;

                    case 5:
                    case 6:
                        return 1;

                    case 7:
                    case 8:
                        return 3;

                    case 9:
                    case 10:
                        return 5;
                }
            }

            return 0;
        }

        /**
         * Calculates the score for the Language test based on the CLB.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {object} clbs Pass the CLB level for each skill
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * @param {boolean} firstLanguage Indicates if the score is for the first or second language 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {object} Returns the ammount of points given for each skill. 
         *
         * @author Bruno Miranda
         */
        var calculateCLBs = function (calculator, clbs, principalApplicant, firstLanguage) {
            var result = {
                speaking: calculateCLB(calculator, clbs.speaking, principalApplicant, firstLanguage),
                listening: calculateCLB(calculator, clbs.listening, principalApplicant, firstLanguage),
                reading: calculateCLB(calculator, clbs.reading, principalApplicant, firstLanguage),
                writting: calculateCLB(calculator, clbs.writting, principalApplicant, firstLanguage),
                total: 0
            }

            result.total = result.speaking + result.listening + result.reading + result.writting;

            return result;
        }

        /**
         * Calculates the score for the Section Core/Human Capital Factors
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {object} Returns the ammount of points given for the section. 
         *
         * @author Bruno Miranda
         */
        var calculateCoreHumanCapitalFactors = function (calculator) {
            var coreHumanCapitalFactors = {
                age: null,
                levelOfEducation: null,
                officialLanguages: {
                    first: {
                        speaking: 0,
                        listening: 0,
                        reading: 0,
                        writting: 0,
                        total: 0
                    },
                    second: {
                        speaking: 0,
                        listening: 0,
                        reading: 0,
                        writting: 0,
                        total: 0
                    }
                },
                canadianWorkExperience: null,
                subTotal: null
            }

            coreHumanCapitalFactors.age = calculateAge(calculator);
            coreHumanCapitalFactors.levelOfEducation = calculateEducation(calculator, true);
            coreHumanCapitalFactors.officialLanguages.first = calculateLanguage(calculator, true, true);

            if (calculator.secondLanguage.test !== null)
                coreHumanCapitalFactors.officialLanguages.second = calculateLanguage(calculator, true, false);

            coreHumanCapitalFactors.canadianWorkExperience = calculateWorkInCanada(calculator);

            coreHumanCapitalFactors.subTotal = coreHumanCapitalFactors.age +
                coreHumanCapitalFactors.levelOfEducation +
                coreHumanCapitalFactors.officialLanguages.first.total +
                coreHumanCapitalFactors.officialLanguages.second.total +
                coreHumanCapitalFactors.canadianWorkExperience;

            return coreHumanCapitalFactors;
        }

        /**
         * Calculates the score for the education of the principal applicant.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given.
         *
         * @author Bruno Miranda 
         */
        var calculateEducation = function (calculator, principalApplicant) {
            if (principalApplicant) {
                switch (calculator.educationLevel) {
                    case educationLevel.Secondary:
                        return (isSingle(calculator) ? 30 : 28);

                    case educationLevel.OneYearDegree:
                        return (isSingle(calculator) ? 90 : 84);

                    case educationLevel.TwoYearDegree:
                        return (isSingle(calculator) ? 98 : 91);

                    case educationLevel.BachelorsDegree:
                        return (isSingle(calculator) ? 120 : 112);

                    case educationLevel.TwoOrMoreDegress:
                        return (isSingle(calculator) ? 128 : 119);

                    case educationLevel.DoctoralDegree:
                        return (isSingle(calculator) ? 135 : 126);

                    case educationLevel.DoctoralDegree:
                        return (isSingle(calculator) ? 150 : 140);
                }
            }
            else {
                switch (calculator.spouseEducationLevel) {
                    case educationLevel.Secondary:
                        return 2;

                    case educationLevel.OneYearDegree:
                        return 6;

                    case educationLevel.TwoYearDegree:
                        return 7;

                    case educationLevel.BachelorsDegree:
                        return 8;

                    case educationLevel.TwoOrMoreDegress:
                        return 9;

                    case educationLevel.DoctoralDegree:
                        return 10;

                    case educationLevel.DoctoralDegree:
                        return 10;
                }
            }
            return 0;
        }

        /**
         * Calculates the score for the education in Canada of the principal applicant.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given. 
         *
         * @author Bruno Miranda
         */
        var calculateEducationInCanada = function (calculator) {
            if (calculator.educationInCanada !== null && calculator.educationInCanada !== undefined && typeof (calculator.educationInCanada) === 'number') {
                switch (calculator.educationInCanada) {
                    case educationInCanada.OneOrTwoYearDiplomaOrCertificate:
                        return 15;

                    case educationInCanada.ThreeOrMoreYearsDegree:
                        return 30;
                }
            }

            return 0;
        }

        /**
         * Calculates the score for the education transferability.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {number} clb7Count Quantity of CLB7 or higher on the laguage skills 
         * @param {number} clb9Count Quantity of CLB9 or higher on the laguage skills 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given.
         *
         * @author Bruno Miranda 
         */
        var calculateEducationTransferability = function (calculator, clb7Count, clb9Count) {
            var educationTransferability = {
                officialLanguageProficiency: 0,
                canadianWorkExperience: 0,
                subTotal: 0
            };

            switch (calculator.educationLevel) {
                case educationLevel.OneYearDegree:
                case educationLevel.BachelorsDegree:
                    if (clb7Count === 4 && clb9Count < 4)
                        educationTransferability.officialLanguageProficiency += 13;
                    else if (clb9Count === 4)
                        educationTransferability.officialLanguageProficiency += 25;

                    if (calculator.workInCanada >= 1 && calculator.workInCanada < 2)
                        educationTransferability.canadianWorkExperience += 13;
                    else if (calculator.workInCanada >= 2)
                        educationTransferability.canadianWorkExperience += 25;

                    break;

                case educationLevel.TwoOrMoreDegress:
                case educationLevel.MastersDegree:
                case educationLevel.DoctoralDegree:
                    if (clb7Count === 4 && clb9Count < 4)
                        educationTransferability.officialLanguageProficiency += 25;
                    else if (clb9Count === 4)
                        educationTransferability.officialLanguageProficiency += 50;

                    if (calculator.workInCanada >= 1 && calculator.workInCanada < 2)
                        educationTransferability.canadianWorkExperience += 25;
                    else if (calculator.workInCanada >= 2)
                        educationTransferability.canadianWorkExperience += 50;

                    break;
            }

            educationTransferability.subTotal = educationTransferability.officialLanguageProficiency +
                educationTransferability.canadianWorkExperience

            if (educationTransferability.subTotal > 50)
                educationTransferability.subTotal = 50;

            return educationTransferability;
        }

        /**
         * Calculates the score for the foreign work experience transferability.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {number} clb7Count Quantity of CLB7 or higher on the laguage skills 
         * @param {number} clb9Count Quantity of CLB9 or higher on the laguage skills 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given.
         *
         * @author Bruno Miranda 
         */
        var calculateForeignWorkExperienceTransferability = function (calculator, clb7Count, clb9Count) {
            var foreignWorkExperience = {
                officialLanguageProficiency: 0,
                canadianWorkExperience: 0,
                subTotal: 0
            };

            switch (true) {
                case calculator.workExperience >= 1 && calculator.workExperience < 3:
                    if (clb7Count === 4 && clb9Count < 4)
                        foreignWorkExperience.officialLanguageProficiency += 13;
                    else if (clb9Count === 4)
                        foreignWorkExperience.officialLanguageProficiency += 25;

                    if (calculator.workInCanada >= 1 && calculator.workInCanada < 2)
                        foreignWorkExperience.canadianWorkExperience += 13;
                    else if (calculator.workInCanada >= 2)
                        foreignWorkExperience.canadianWorkExperience += 25;

                    break;

                case calculator.workExperience >= 3:
                    if (clb7Count === 4 && clb9Count < 4)
                        foreignWorkExperience.officialLanguageProficiency += 25;
                    else if (clb9Count === 4)
                        foreignWorkExperience.officialLanguageProficiency += 50;

                    if (calculator.workInCanada >= 1 && calculator.workInCanada < 2)
                        foreignWorkExperience.canadianWorkExperience += 25;
                    else if (calculator.workInCanada >= 2)
                        foreignWorkExperience.canadianWorkExperience += 50;

                    break;
            }

            foreignWorkExperience.subTotal = foreignWorkExperience.officialLanguageProficiency +
                foreignWorkExperience.canadianWorkExperience

            if (foreignWorkExperience.subTotal > 50)
                foreignWorkExperience.subTotal = 50;

            return foreignWorkExperience;
        }

        /**
         * Calculates the CLB levels for the IELTS test.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {object} language Pass the first or second language object
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * @param {boolean} firstLanguage Indicates if the score is for the first or second language 
         * 
         * @returns {object} Returns the CLB levels for each skill. 
         *
         * @author Bruno Miranda
         */
        var calculateIelts = function (calculator, language, principalApplicant, firstLanguage) {
            var clbs = {
                speaking: 0,
                listening: 0,
                reading: 0,
                writting: 0
            }

            var score;
            var clb;

            score = language.speaking;
            clb = 0;
            switch (true) {
                case score === 4.5:
                    clb = 4;
                    break;

                case score === 5:
                    clb = 5;
                    break;

                case score === 5.5:
                    clb = 6;
                    break;

                case score === 6:
                    clb = 7;
                    break;

                case score === 6.5:
                    clb = 8;
                    break;

                case score === 7:
                    clb = 9;
                    break;

                case score >= 7.5:
                    clb = 10;
                    break;

            }
            clbs.speaking = clb;

            score = language.listening;
            clb = 0;
            switch (true) {
                case score === 4.5:
                    clb = 4;
                    break;

                case score === 5:
                    clb = 5;
                    break;

                case score === 5.5:
                    clb = 6;
                    break;

                case score >= 6 && score < 7.5:
                    clb = 7;
                    break;

                case score === 7.5:
                    clb = 8;
                    break;

                case score === 8:
                    clb = 9;
                    break;

                case score >= 8.5:
                    clb = 10;
                    break;

            }
            clbs.listening = clb;

            score = language.reading;
            clb = 0;
            switch (true) {
                case score === 3.5:
                    clb = 4;
                    break;

                case score >= 4 && score < 5:
                    clb = 5;
                    break;

                case score >= 5 && score < 6:
                    clb = 6;
                    break;

                case score === 6:
                    clb = 7;
                    break;

                case score === 6.5:
                    clb = 8;
                    break;

                case score >= 7 && score < 8:
                    clb = 9;
                    break;

                case score >= 8:
                    clb = 10;
                    break;

            }
            clbs.reading = clb;

            score = language.writting;
            clb = 0;
            switch (true) {
                case score >= 4 && score < 5:
                    clb = 4;
                    break;

                case score === 5:
                    clb = 5;
                    break;

                case score === 5.5:
                    clb = 6;
                    break;

                case score === 6:
                    clb = 7;
                    break;

                case score === 6.5:
                    clb = 8;
                    break;

                case score === 7:
                    clb = 9;
                    break;

                case score >= 7.5:
                    clb = 10;
                    break;

            }
            clbs.writting = clb;

            return clbs;
        }

        /**
         * Calculates the score for the Job Offer
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given. 
         *
         * @author Bruno Miranda
         */
        var calculateJobOffer = function (calculator) {
            switch (calculator.nocJobOffer) {
                case nocList._00:
                    return 200;

                case nocList._0:
                case nocList.A:
                case nocList.B:
                    return 50;

            }

            return 0;
        }

        /**
         * Calculates the score for the language.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * @param {boolean} firstLanguage Indicates if the score is for the first or second language 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {object} Returns the ammount of points given for each skill. 
         *
         * @author Bruno Miranda
         */
        var calculateLanguage = function (calculator, principalApplicant, firstLanguage) {
            var clbs;
            var language;

            if (principalApplicant)
                language = (firstLanguage ? calculator.firstLanguage : calculator.secondLanguage);
            else
                language = calculator.spouseLanguage;

            switch (language.test) {
                case languageTest.none:
                    clbs = language;
                    break;

                case languageTest.celpip:
                    clbs = calculateCelpip(calculator, language, principalApplicant, firstLanguage);
                    break;

                case languageTest.ielts:
                    clbs = calculateIelts(calculator, language, principalApplicant, firstLanguage);
                    break;

                case languageTest.tef:
                    clbs = calculateTef(calculator, language, principalApplicant, firstLanguage);
                    break;
            }

            return calculateCLBs(calculator, clbs, principalApplicant, firstLanguage);
        }

        /**
         * Calculates the score for the Provincial Nomination
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given. 
         *
         * @author Bruno Miranda
         */
        var calculateProvincialNomination = function (calculator) {
            if (calculator.provincialNomination === true)
                return 600;

            return 0;
        }

        /**
         * Calculates the score for the Skill Transferability Factors
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {object} Returns the ammount of points given for the section. 
         *
         * @author Bruno Miranda
         */
        var calculateSkillTransferabilityFactors = function (calculator) {
            var skillTransferabilityFactors = {
                education: {
                    officialLanguageProficiency: 0,
                    canadianWorkExperience: 0,
                    subTotal: 0
                },
                foreignWorkExperience: {
                    officialLanguageProficiency: 0,
                    canadianWorkExperience: 0,
                    subTotal: 0
                },
                certificateOfQualification: 0,
                subTotal: 0
            }

            var clbs;

            switch (calculator.firstLanguage.test) {
                case languageTest.none:
                    clbs = language;
                    break;

                case languageTest.celpip:
                    clbs = calculateCelpip(calculator, calculator.firstLanguage, true, true);
                    break;

                case languageTest.ielts:
                    clbs = calculateIelts(calculator, calculator.firstLanguage, true, true);
                    break;

                case languageTest.tef:
                    clbs = calculateTef(calculator, calculator.firstLanguage, true, true);
                    break;
            }

            var clb5Count = 0;

            if (clbs.speaking >= 5)
                clb5Count += 1;

            if (clbs.listening >= 5)
                clb5Count += 1;

            if (clbs.reading >= 5)
                clb5Count += 1;

            if (clbs.writting >= 5)
                clb5Count += 1;

            var clb7Count = 0;

            if (clbs.speaking >= 7)
                clb7Count += 1;

            if (clbs.listening >= 7)
                clb7Count += 1;

            if (clbs.reading >= 7)
                clb7Count += 1;

            if (clbs.writting >= 7)
                clb7Count += 1;

            var clb9Count = 0;

            if (clbs.speaking >= 9)
                clb9Count += 1;

            if (clbs.listening >= 9)
                clb9Count += 1;

            if (clbs.reading >= 9)
                clb9Count += 1;

            if (clbs.writting >= 9)
                clb9Count += 1;

            skillTransferabilityFactors.education = calculateEducationTransferability(calculator, clb7Count, clb9Count);
            skillTransferabilityFactors.foreignWorkExperience = calculateForeignWorkExperienceTransferability(calculator, clb7Count, clb9Count);
            skillTransferabilityFactors.certificateOfQualification = calculateCertificateOfQualitication(calculator, clb5Count, clb7Count);

            skillTransferabilityFactors.subTotal = skillTransferabilityFactors.education.subTotal +
                skillTransferabilityFactors.foreignWorkExperience.subTotal +
                skillTransferabilityFactors.certificateOfQualification;

            if (skillTransferabilityFactors.subTotal > 100)
                skillTransferabilityFactors.subTotal = 100;

            return skillTransferabilityFactors;
        }

        /**
         * Calculates the score for the Section Spouse Factors
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {object} Returns the ammount of points given for the section. 
         *
         * @author Bruno Miranda
         */
        var calculateSpouseFactors = function (calculator) {
            var spouseFactors = {
                levelOfEducation: 0,
                officialLanguage: {
                    speaking: 0,
                    listening: 0,
                    reading: 0,
                    writting: 0,
                    total: 0
                },
                canadianWorkExperience: 0,
                subTotal: 0
            }

            if (!isSingle(calculator)) {
                spouseFactors.levelOfEducation = calculateEducation(calculator, false);
                spouseFactors.officialLanguage = calculateLanguage(calculator, false);
                spouseFactors.canadianWorkExperience = calculateWorkInCanada(calculator, false);

                spouseFactors.subTotal = spouseFactors.levelOfEducation +
                    spouseFactors.officialLanguage.total +
                    spouseFactors.canadianWorkExperience;
            }

            return spouseFactors;
        }

        /**
         * Calculates the CLB levels for the TEF test.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {object} language Pass the first or second language object
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * @param {boolean} firstLanguage Indicates if the score is for the first or second language 
         * 
         * @returns {object} Returns the CLB levels for each skill. 
         *
         * @author Bruno Miranda
         */
        var calculateTef = function (calculator, language, principalApplicant, firstLanguage) {
            var clbs = {
                speaking: 0,
                listening: 0,
                reading: 0,
                writting: 0
            }

            var score;
            var clb;

            score = language.speaking;
            clb = 0;
            switch (true) {
                case score >= 181 && score <= 225:
                    clb = 4;
                    break;

                case score >= 226 && score <= 270:
                    clb = 5;
                    break;

                case score >= 271 && score <= 309:
                    clb = 6;
                    break;

                case score >= 310 && score <= 348:
                    clb = 7;
                    break;

                case score >= 349 && score <= 370:
                    clb = 8;
                    break;

                case score >= 371 && score <= 392:
                    clb = 9;
                    break;

                case score >= 393 && score <= 415:
                    clb = 10;
                    break;

            }
            clbs.speaking = clb;

            score = language.listening;
            clb = 0;
            switch (true) {
                case score >= 145 && score <= 180:
                    clb = 4;
                    break;

                case score >= 181 && score <= 216:
                    clb = 5;
                    break;

                case score >= 217 && score <= 248:
                    clb = 6;
                    break;

                case score >= 249 && score <= 279:
                    clb = 7;
                    break;

                case score >= 280 && score <= 297:
                    clb = 8;
                    break;

                case score >= 298 && score <= 315:
                    clb = 9;
                    break;

                case score >= 316 && score <= 333:
                    clb = 10;
                    break;

            }
            clbs.listening = clb;

            score = language.reading;
            clb = 0;
            switch (true) {
                case score >= 121 && score <= 150:
                    clb = 4;
                    break;

                case score >= 151 && score <= 180:
                    clb = 5;
                    break;

                case score >= 181 && score <= 206:
                    clb = 6;
                    break;

                case score >= 207 && score <= 232:
                    clb = 7;
                    break;

                case score >= 233 && score <= 247:
                    clb = 8;
                    break;

                case score >= 248 && score <= 262:
                    clb = 9;
                    break;

                case score >= 263 && score <= 277:
                    clb = 10;
                    break;

            }
            clbs.reading = clb;

            score = language.writting;
            clb = 0;
            switch (true) {
                case score >= 181 && score <= 225:
                    clb = 4;
                    break;

                case score >= 226 && score <= 270:
                    clb = 5;
                    break;

                case score >= 271 && score <= 309:
                    clb = 6;
                    break;

                case score >= 310 && score <= 348:
                    clb = 7;
                    break;

                case score >= 349 && score <= 370:
                    clb = 8;
                    break;

                case score >= 371 && score <= 392:
                    clb = 9;
                    break;

                case score >= 393 && score <= 415:
                    clb = 10;
                    break;

            }
            clbs.writting = clb;

            return clbs;
        }

        /**
         * Calculates the score for the work experience in Canada.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * @param {boolean} principalApplicant Indicates if the score is for the principal applicant 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Number} Returns the ammount of points given. 
         *
         * @author Bruno Miranda
         */
        var calculateWorkInCanada = function (calculator, principalApplicant) {
            if (principalApplicant) {
                switch (true) {
                    case calculator.workInCanada >= 1 && calculator.workInCanada < 2:
                        return (isSingle(calculator) ? 40 : 35);

                    case calculator.workInCanada >= 2 && calculator.workInCanada < 3:
                        return (isSingle(calculator) ? 53 : 46);

                    case calculator.workInCanada >= 3 && calculator.workInCanada < 4:
                        return (isSingle(calculator) ? 64 : 56);

                    case calculator.workInCanada >= 4 && calculator.workInCanada < 5:
                        return (isSingle(calculator) ? 72 : 63);

                    case calculator.workInCanada >= 5:
                        return (isSingle(calculator) ? 80 : 70);
                }
            }
            else {
                switch (true) {
                    case calculator.spouseWorkInCanada >= 1 && calculator.spouseWorkInCanada < 2:
                        return 5;

                    case calculator.spouseWorkInCanada >= 2 && calculator.spouseWorkInCanada < 3:
                        return 7;

                    case calculator.spouseWorkInCanada >= 3 && calculator.spouseWorkInCanada < 4:
                        return 8;

                    case calculator.spouseWorkInCanada >= 4 && calculator.spouseWorkInCanada < 5:
                        return 9;

                    case calculator.spouseWorkInCanada >= 5:
                        return 10;
                }
            }

            return 0;
        }


        /**
         * Determine if the points are going to be calculated as single or as married.
         * If the person is married, but the spouse is Canadian or is not comming along,
         * the calculations are done as single.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Boolean} 
         *
         * @author Bruno Miranda
         */
        var isSingle = function (calculator) {
            var single = !calculator.married;

            if (calculator.married && (calculator.spouseCanadianCitizen || !calculator.spouseCommingAlong))
                single = true;

            return single;
        }


        /**
         * Validates the properties to make sure the calculation is possible.
         * 
         * @param {object} calculator Pass the calculator as a parameter 
         * 
         * @this window That's why the calculator is passed as a parameter.
         * 
         * @returns {Boolean}
         *
         * @author Bruno Miranda 
         */
        var validate = function (calculator) {
            var result = [];

            if (calculator.married === null)
                result.push('married');
            else
                if (typeof (calculator.married) !== 'boolean')
                    result.push('married');
                else {
                    if (calculator.spouseCanadianCitizen === null)
                        result.push('spouseCanadianCitizen');
                    else
                        if (typeof (calculator.spouseCanadianCitizen) !== 'boolean')
                            result.push('spouseCanadianCitizen');
                        else {
                            if (!calculator.spouseCanadianCitizen) {
                                if (calculator.spouseCommingAlong === null)
                                    result.push('spouceCommingAlong');
                                else
                                    if (typeof (calculator.spouseCommingAlong) !== 'boolean')
                                        result.push('spouceCommingAlong');
                            }
                        }
                }

            if (calculator.age === null || calculator.age === undefined)
                result.push('age');
            else
                if (typeof (calculator.age) !== 'number')
                    result.push('age');

            if (calculator.firstLanguage.test === null)
                result.push('firstLanguage.test');
            else
                if (typeof (calculator.firstLanguage.test) !== 'number')
                    result.push('firstLanguage.test');

            if (calculator.firstLanguage.speaking === null)
                result.push('firstLanguage.speaking');
            else
                if (typeof (calculator.firstLanguage.speaking) !== 'number')
                    result.push('firstLanguage.speaking');

            if (calculator.firstLanguage.listening === null)
                result.push('firstLanguage.listening');
            else
                if (typeof (calculator.firstLanguage.listening) !== 'number')
                    result.push('firstLanguage.listening');

            if (calculator.firstLanguage.reading === null)
                result.push('firstLanguage.reading');
            else
                if (typeof (calculator.firstLanguage.reading) !== 'number')
                    result.push('firstLanguage.reading');

            if (calculator.firstLanguage.writting === null)
                result.push('firstLanguage.writting');
            else
                if (typeof (calculator.firstLanguage.writting) !== 'number')
                    result.push('firstLanguage.writting');

            if (calculator.secondLanguage.test !== null || calculator.secondLanguage.speaking !== null || calculator.secondLanguage.listening !== null || calculator.secondLanguage.reading !== null || calculator.secondLanguage.speaking !== null) {
                if (calculator.secondLanguage.test === null)
                    result.push('secondLanguage.test');
                else
                    if (typeof (calculator.secondLanguage.test) !== 'number')
                        result.push('secondLanguage.test');

                if (calculator.secondLanguage.speaking === null)
                    result.push('secondLanguage.speaking');
                else
                    if (typeof (calculator.secondLanguage.speaking) !== 'number')
                        result.push('secondLanguage.speaking');

                if (calculator.secondLanguage.listening === null)
                    result.push('secondLanguage.listening');
                else
                    if (typeof (calculator.secondLanguage.listening) !== 'number')
                        result.push('secondLanguage.listening');

                if (calculator.secondLanguage.reading === null)
                    result.push('secondLanguage.reading');
                else
                    if (typeof (calculator.secondLanguage.reading) !== 'number')
                        result.push('secondLanguage.reading');

                if (calculator.secondLanguage.writting === null)
                    result.push('secondLanguage.writting');
                else
                    if (typeof (calculator.secondLanguage.writting) !== 'number')
                        result.push('secondLanguage.writting');
            }

            return (result.length === 0 ? true : result);
        }
    }    
};
