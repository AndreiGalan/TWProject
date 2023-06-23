

let pictureSelected = false;
let answerCount = 0;
const createQuestionButton = document.getElementById('submit-button');

function getCookie(name) {
    let cookieArr = document.cookie.split(";");
    for(let i = 0; i < cookieArr.length; i++) {
        let cookiePair = cookieArr[i].split("=");
        if(cookiePair[0] === name) {
            return cookiePair[1];
        }
    }
    return "";
}


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

    const answersContainer = document.getElementById('answersContainer');
    const answers = [];

    let nrCorrectAnswers = 0;

    // Iterate over each answer and retrieve its value
    const answerInputs = answersContainer.querySelectorAll('input[name="answer"]');

    answerInputs.forEach(function (answerInput) {
        let isCorrect = false;
        if(answerInput.checked){
            isCorrect = true;
            nrCorrectAnswers++;
        }
        const answerObj = {
            question_id: 1,
            answer_text: answerInput.value,
            is_correct: isCorrect ? 1 : 0
        }
        answers.push(answerObj);
    });

    if(nrCorrectAnswers === 0){
        alert("Please select at least one correct answer!");
        return;
    }

     const questionText = document.getElementById('questionText').value;
     const difficulty = document.getElementById('questionDifficulty').value;
     const idForPictureOrEquation = document.getElementById('questionId').value;

     if(questionText === '' || difficulty === '' || idForPictureOrEquation === ''){
            alert("Please fill all the fields!");
            return;
     }
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
    console.log(type);
    let questionObj ;
     if(type === 'equation'){
         questionObj = {
            question_text: questionText,
            difficulty: difficultyNr,
            points : points,
            id_equation : idForPictureOrEquation
        };
    }
    else if(type === 'picture'){
         questionObj = {
            question_text: questionText,
            difficulty: difficultyNr,
            points : points,
            id_picture : idForPictureOrEquation
        }
    }

     // Convert the question object to JSON
     const questionJSON = JSON.stringify(questionObj);

     const insertQuestionPromise = insetQuestion(questionJSON);

     insertQuestionPromise.then( response => {
            if(response.ok){
                return response.json();
            }
            else{
                alert('Error adding question!');
                return "";
            }
     }).then(result => {
         if(result === ""){
                return;
         }
         const questionIdFromDb = result['id']; // Replace this with the question ID from the database
         answers.forEach( answer => {
             answer['question_id'] = questionIdFromDb;
         });
         let nr = 0;
         answers.forEach( answer => {
           //call the endpoint to insert the answer
             const token = getCookie("token");
             const options = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(answer)
             }
                fetch('http://localhost/TWProject/backend/answers', options)
                    .then(response => {
                    if(response.ok){
                        nr ++;
                        if(nr === answers.length){
                            alert('Question added successfully!');
                            window.location.href = "Admin.html";
                        }
                    }
                    else{
                        alert('Error adding answers!');
                    }
                });
         });

     });
});


function insetQuestion(questionJSON) {

    const token = getCookie("token");
    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: questionJSON
    }
    console.log(questionJSON);
    return fetch('http://localhost/TWProject/backend/questions/', options);

}

const addPictureEquationButton = document.getElementById('add-picture-equation');
addPictureEquationButton.addEventListener('click', function(e) {
    e.preventDefault();

    if(pictureSelected){
        alert("You have already selected a picture!");
    }
    else{
        //disable Question Type and QuestionDifficulty
        const questionType = document.getElementById('questionType');
        questionType.disabled = true;
        document.getElementById('questionDifficulty').disabled = true;

        document.getElementById('picture-equation-container').style.display = 'block';

        //get all pictures with a fetch request
        const token = getCookie("token");
        if(token === ""){
            window.location.href = "Login.html";
        }
        const options = {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        }


        //IF TYPE SELECTED IS PICTURE THEN WE WILL PRINT THE PICTURES
        if(questionType.value === 'picture') {
            document.getElementById('title-table').innerHTML = "Pictures";
            fetch('http://localhost/TWProject/backend/pictures', options)
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        alert('Error getting pictures!');
                        return "";
                    }
                })
                .then(result => {

                    const bodyTable = document.getElementById('body-table');
                    result.forEach(picture => {
                        bodyTable.innerHTML += "<tr><td><img src=\"" + picture['download_link'] + "\" alt=\"picture\"> </td>" +
                            "<td><button onclick=\"selectPicture(" + picture['id'] + ")\">Select</button></td></tr>";
                    });

                });
        }
        else{
            //IT S EQUATION

            document.getElementById('title-table').innerHTML = "Equations";
            fetch('http://localhost/TWProject/backend/equations', options)
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        alert('Error getting equations!');
                        return "";
                    }
                })
                .then(result => {

                        const bodyTable = document.getElementById('body-table');
                        result.forEach(equation => {
                            bodyTable.innerHTML += "<tr><td>" + equation['equation_text'] + "</td>" +
                                "<td><button onclick=\"selectPicture(" + equation['id'] + ")\">Select</button></td></tr>";
                        });

                });

        }
    }

});

function selectPicture(id){
    document.getElementById('picture-equation-container').style.display = 'none';
    document.getElementById('questionId').value = id;
    pictureSelected = true;
}










