import { getDifficulty, getImage, getEquation, getAnswers, saveScore } from "./gameHelperAPI.js";
import { setQuestionTextForPicture, setAnswers, setEquation, setImage, setHearts, setQuestionCounter, setQuestionTextForEquation } from "./gameHelperContent.js";
import {parseEquations} from "./gameParserEquation.js";
import {getCookie} from "./cookie.js";

let questions = [];
let nrQuestion = 0;
let nrHearts = 3;
let points = 0;
let difficulty;
let answersForCurrentQuestion;
const buttonFinish = "<button class=\"next-btn\" type=\"button\">Finish</button>";
const buttonNext = "<button class=\"next-btn\" type=\"button\">Next</button>";

function getQuestions() {

    difficulty = getDifficulty();
    const token = getCookie("token");

    const requestOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
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
            //global variable questions is initialized
            questions = result;

            //print the first question
            printQuestion(nrQuestion);
        })
        .catch(error => console.log('error', error));
}

function answerIsCorrect(responseValue) {
    //find the answer that has the value equal to responseValue
    const answer = answersForCurrentQuestion.find(answer => answer['answer_text'] === responseValue);
    return answer['is_correct'] === '1';
}

function incrementQuestion() {

    const selectedAnswer = document.querySelector('input[name="answer"]:checked');
    if (!selectedAnswer) {
        alert('Select an answer before proceeding.');
        return;
    }

    if(answerIsCorrect(selectedAnswer.value)){
        points = points + parseInt(questions[nrQuestion]['points']);
    }
    else{
        nrHearts = nrHearts - 1;
    }

    nrQuestion = nrQuestion + 1;
    printQuestion(nrQuestion);
}

function finishButtonAction() {
    const selectedAnswer = document.querySelector('input[name="answer"]:checked');
    if (!selectedAnswer) {
        alert('Select an answer before proceeding.');
        return;
    }

    if(answerIsCorrect(selectedAnswer.value)){
        points = points + parseInt(questions[nrQuestion]['points']);
    }
    else{
        nrHearts = nrHearts - 1;
    }
    localStorage.setItem("points", points);
    localStorage.setItem("difficulty", difficulty);

    saveScore(points);

    window.location.href='GameOver.html';
}

function printQuestion(nrQuestion) {

    if(nrHearts === 0) {//game over
        if(nrQuestion <= 5){
            points = 0;
        }
        localStorage.setItem("points", points);
        localStorage.setItem("difficulty", difficulty);
        window.location.href = 'GameOver.html';
        return;
    }

    setQuestionCounter(nrQuestion + 1, questions.length);

    setHearts(nrHearts);

    const answersPromise = getAnswers(questions[nrQuestion]['id']);
    //delete the previous answers
    if(nrQuestion !== 0){
        const answersHolder = document.querySelector(".form-answers");
        answersHolder.innerHTML = "";
    }
    answersPromise.then(answers => {
        setAnswers(answers);
        answersForCurrentQuestion = answers;
    });

    //check the type of the question
    if (questions[nrQuestion]['id_picture'] === null) {
        //it is an equation
        //get the equation
        const equationPromise = getEquation(questions[nrQuestion]['id_equation']);

        equationPromise.then(equation => {
            const map = parseEquations(equation['equation_text']);
            setEquation( equation['equation_text'] , map);
            setQuestionTextForEquation(questions[nrQuestion]['question_text'],map);

        });

    } else {

        //it is a question with an image
        //get the link of the image
        const imagePromise = getImage(questions[nrQuestion]['id_picture']);
        imagePromise.then(image => {

            setImage(image);
        });
        setQuestionTextForPicture(questions[nrQuestion]);
    }

    if(nrQuestion === questions.length - 1){//last question

        if(nrQuestion !== 0){//delete the Next button and then add the finish button
            const nextButton = document.querySelector(".next-btn");
            nextButton.remove();
        }
        const form = document.querySelector("form");
        form.innerHTML += buttonFinish;
        const finishButton = form.querySelector(".next-btn");
        finishButton.addEventListener("click", finishButtonAction);

    }
    else if( nrQuestion === 0){//add the Next button

        const form = document.querySelector("form");
        form.innerHTML += buttonNext;
        const nextButton = form.querySelector(".next-btn");
        nextButton.addEventListener("click", incrementQuestion);

    }

}

//main
getQuestions();
