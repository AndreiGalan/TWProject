    /**
     * Set the question text in the HTML for a given question
     * @param question
     */
    export function setQuestionTextForPicture(question) {
        let questionText = "<h2>" + question['question_text'] +"</h2>";

        const questionHolder = document.querySelector(".question-holder");
        questionHolder.innerHTML = questionText;
    }

    /**
     * Set the answers in the HTML
     * @param answers
     */
    export function setAnswers(answers) {

        const answersHolder = document.querySelector(".form-answers");

        for(let i = 0; i < answers.length; i++){
            let input = "<input type=\"radio\" id=\"answer" + i + "\" name=\"answer\" value=\"" + answers[i]['answer_text'] + "\">";
            let span = "<span class=\"alphabet\" > " + String.fromCharCode(65 + i) + "</span>"+ answers[i]['answer_text'];
            let label = "<label for=\"answer" + i + "\">" + input + span + "</label>";
            answersHolder.innerHTML += label;
        }
    }

    /**
     * Set the equation in the HTML
     * @param equationText
     * @param map
     */
    export function setEquation(equationText, map) {

        const equations = equationText.split(";");
        const parsedEquations = [];

        equations.forEach((equation, index) => {
            const parts = equation.trim().split(/\s*=\s*/);
            if(parts.length === 2) {
                parsedEquations.push(equation.replace(/[a-zA-Z]+/g, (match) => map[match]));
            }
        });

        const equationMapped = parsedEquations.join("<br>");

        const equationHolder = document.querySelector(".image-equation");
        equationHolder.innerHTML = equationMapped ;
    }

    /**
     * Set the image in the HTML
     * @param image
     */
    export function setImage(image) {
        const imageHolder = document.querySelector(".image-equation");
        imageHolder.innerHTML = "<img src=\"" + image['download_link'] + "\" alt=\"image\">";
    }

    /**
    * Set the number of hearts in the HTML
    * @param nrHearts
    */
    export function setHearts(nrHearts) {
        const heartsHolder = document.querySelector(".hearts");
        heartsHolder.innerHTML = "";
        const heart = "<div class=\"heart\"> <img src=\"../images/game/heart.png\" alt=\"Heart1\"> </div>";
        for(let i = 0; i < nrHearts; i++){
            heartsHolder.innerHTML += heart;
        }
    }

    /**
    * Set the question counter in the HTML
    * @param nrCurrentQuestion
    * @param nrQuestions
    */
    export function setQuestionCounter(nrCurrentQuestion, nrQuestions) {

        const questionCounterHolder = document.querySelector(".question-counter");
        questionCounterHolder.innerHTML = "<span class = \"question-counter-text\">" + nrCurrentQuestion + "/" + nrQuestions + "</span>";

    }

    /**
     * Set the question text in the HTML for a given question, mapping the "unknowns" to a fruit/vegetable
     * @param questionText
     * @param map
     */
    export function setQuestionTextForEquation(questionText, map) {


        const separator = "::";
        const questionParts = questionText.split(separator);
        const parsedQuestionParts = [];

        questionParts.forEach((part, index) => {
            if (index % 2 === 0) {
                parsedQuestionParts.push(part);
            } else {
                parsedQuestionParts.push(map[part]);
            }
        });

        questionText = parsedQuestionParts.join("");

        const questionHolder = document.querySelector(".question-holder");
        questionHolder.innerHTML = "<h2>" +  questionText + "</h2>";
    }