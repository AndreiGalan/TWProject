
let answerCount = 0;

const createQuestionButton = document.getElementById('submit-button');


function addAnswer() {
    const answerText = document.getElementById('answerText').value;
    if (answerText === '') {
        alert('Please enter an answer text');
        return;
    }
    const answersContainer = document.getElementById('answersContainer');
    const newAnswer = "<div><label><input type=\"radio\" name=\"answer\"" +
        " value=\"" + answerText +"\">" + answerText + "</label></div>";
    answersContainer.innerHTML += newAnswer;

    answerCount++;
    document.getElementById('answerText').value = ''; // Clear the answer text input
}

function deleteAnswer() {
    const answersContainer = document.getElementById('answersContainer');
    if (answerCount > 0) {
        answersContainer.removeChild(answersContainer.lastChild);
        answerCount--;
    }
}



 createQuestionButton.addEventListener('click', function(e) {
    //prevent the default form behavior
    e.preventDefault();
     if (answerCount === 0) {
        alert('Please add at least one answer');
         return;
     }

     const questionText = document.getElementById('questionText').value;
     const difficulty = document.getElementById('questionDifficulty').value;
     const idForPictureOrEquation = document.getElementById('questionId').value;


     let difficultyNr = 1;
     let points = 5;
    if(difficulty === 'medium'){
        difficultyNr = 1;
        points = 10;
    }
    else if(difficulty === 'hard'){
        difficultyNr = 2;
        points = 15;
    }

    const type = document.getElementById('questionType').value;

    let questionObj ;
     if(type === 'equation'){
         questionObj = {
            questionText: questionText,
            difficulty: difficultyNr,
            points : points,
            id_equation : idForPictureOrEquation
        };
    }
    else if(type === 'picture'){
         questionObj = {
            questionText: questionText,
            difficulty: difficultyNr,
            points : points,
            id_picture : idForPictureOrEquation
        }
    }

    console.log('Question object:', questionObj);
     // Convert the question object to JSON
     const questionJSON = JSON.stringify(questionObj);
     console.log('Question JSON:', questionJSON);

     //make the request to the server
    const questionIdFromDb = 1; // Replace this with the question ID from the database
    const answersContainer = document.getElementById('answersContainer');
    const answers = [];

    // Iterate over each answer and retrieve its value
    const answerInputs = answersContainer.querySelectorAll('input[name="answer"]');
    answerInputs.forEach(function (answerInput) {
        answers.push(answerInput.value);
        let isCorrect = false;
        console.log('Answer:', answerInput);
        if(answerInput.checked){
            isCorrect = true;
        }
        const answerObj = {
            questionId: questionIdFromDb,
            answerText: answerInput.value,
            isCorrect: isCorrect ? 1 : 0
        }
    });


    // You can perform further actions with the question JSON here
});







