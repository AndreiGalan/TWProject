import {getDifficulty,getImage,getEquation,getAnswers} from "./gameHelperAPI.js";
import {setQuestionText, setAnswers, setEquation, setImage} from "./gameHelperContent.js";

let questions = [];
let nrQuestion = 0;
const buttonFinish = "<button class=\"next-btn\" type=\"button\" onclick=\"location.href='GameOver.html'\">Finish</button>";
const buttonNext = "<button class=\"next-btn\" type=\"button\" onclick=\"incrementQuestion()\">Next</button>";
function getQuestions(){

    const difficulty = getDifficulty();

    const requestOptions = {
        method: 'GET'
    };

    //get all questions
    fetch("http://localhost/TWProject/backend/questions/quiz/" + difficulty + "/10", requestOptions)
        .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else if (response.status === 444){ // no questions found
                        alert('No questions found.');
                    }
                }
        )
        .then(result => {
            console.log(result);
            //TODO loop through the questions
            questions = result;
            printQuestion(nrQuestion);
        })
        .catch(error => console.log('error', error));
}

function printQuestion(nrQuestion){

    setQuestionText(questions[nrQuestion]);

    const answersPromise = getAnswers(questions[nrQuestion]['id']);
    answersPromise.then(answers => {
        setAnswers(answers);
    });

    //check the type of the question
    if (questions[nrQuestion]['id_picture'] === null){
        //it is an equation
        //get the equation
        const equationPromise = getEquation(questions[nrQuestion]['id_equation']);

        equationPromise.then(equation => {
            setEquation(equation);
        });

    }
    else{
        //it is a question with an image
        //get the link of the image
        const imagePromise = getImage(questions[nrQuestion]['id_picture']);

        imagePromise.then(image => {
            setImage(image);
        });
    }

    if(nrQuestion === questions.length - 1){
        //it is the last question
        document.querySelector("form").innerHTML += buttonFinish;
    }
    else{
        document.querySelector("form").innerHTML += buttonNext;
    }

}

function incrementQuestion(){
    console.log("increment");
    nrQuestion= nrQuestion + 1;
    printQuestion(nrQuestion);
}

function printToConsole(){
    console.log(questions);
}

//main
getQuestions();