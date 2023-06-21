    /**
     * Set the question text in the HTML for a given question
     * @param question
     */
    export function setQuestionText(question) {
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
            let input = "<input type=\"radio\" id=\"answer" + i + "\" name=\"answer\" value=\"answer" + i + "\">";
            let span = "<span class=\"alphabet\" > " + String.fromCharCode(65 + i) + "</span>"+ answers[i]['answer_text'];
            let label = "<label for=\"answer" + i + "\">" + input + span + "</label>";
            answersHolder.innerHTML += label;
        }
    }

    /**
     * Set the equation in the HTML
     * @param equation
     */
    export function setEquation(equation) {
        const equationHolder = document.querySelector(".image-equation");
        equationHolder.innerHTML = equation['equation_text'];
    }

    /**
     * Set the image in the HTML
     * @param image
     */
    export function setImage(image) {
        const imageHolder = document.querySelector(".image-equation");
        imageHolder.innerHTML = "<img src=\"" + image['download_link'] + "\" alt=\"image\">";
    }