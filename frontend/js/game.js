import { getDifficulty, getImage, getEquation, getAnswers } from "./gameHelperAPI.js";
import { setQuestionText, setAnswers, setEquation, setImage } from "./gameHelperContent.js";

let questions = [];
let nrQuestion = 0;
const buttonFinish = "<button class=\"next-btn\" type=\"button\">Finish</button>";
const buttonNext = "<button class=\"next-btn\" type=\"button\">Next</button>";

function getQuestions() {
    const difficulty = getDifficulty();

    const requestOptions = {
        method: 'GET'
    };

    //get all questions
    fetch("http://localhost/TWProject/backend/questions/quiz/" + difficulty + "/10", requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else if (response.status === 444) { // no questions found
                alert('No questions found.');
            }
        })
        .then(result => {
            console.log(result);
            //TODO loop through the questions
            questions = result;
            printQuestion(nrQuestion);
        })
        .catch(error => console.log('error', error));
}

function incrementQuestion() {
    console.log("increment");
    const selectedAnswer = document.querySelector('input[name="answer"]:checked');
    if (!selectedAnswer) {
        alert('Select an answer before proceeding.');
        return;
    }
    nrQuestion = nrQuestion + 1;
    printQuestion(nrQuestion);;
}

function finishButtonAction() {
    const selectedAnswer = document.querySelector('input[name="answer"]:checked');
    if (!selectedAnswer) {
        alert('Select an answer before proceeding.');
        return;
    }
    window.location.href='GameOver.html';
}

function printQuestion(nrQuestion) {
    setQuestionText(questions[nrQuestion]);

    const answersPromise = getAnswers(questions[nrQuestion]['id']);
    //delete the previous answers
    if(nrQuestion !== 0){
        console.log("delete");
        const answersHolder = document.querySelector(".form-answers");
        answersHolder.innerHTML = "";
    }
    answersPromise.then(answers => {
        setAnswers(answers);
    });

    //check the type of the question
    if (questions[nrQuestion]['id_picture'] === null) {
        //it is an equation
        //get the equation
        const equationPromise = getEquation(questions[nrQuestion]['id_equation']);

        equationPromise.then(equation => {
            setEquation(equation);
        });

    } else {
        //it is a question with an image
        //get the link of the image
        const imagePromise = getImage(questions[nrQuestion]['id_picture']);

        imagePromise.then(image => {
            setImage(image);
        });
    }

    const form = document.querySelector("form");
    form.innerHTML += (nrQuestion === questions.length - 1) ? buttonFinish : buttonNext;

    // Add event listener to the "Next" button
    const nextButton = form.querySelector(".next-btn");
    nextButton.addEventListener("click", incrementQuestion);



    if (nrQuestion === questions.length - 1) {
        // Remove the "Next" button if it's the last question
        nextButton.remove();
        console.log("finish");
        const finishButton = form.querySelector(".next-btn");
        finishButton.addEventListener("click", finishButtonAction);
    }
}

//main
getQuestions();
