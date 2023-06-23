

function getCookie(name) {
    let cookies = document.cookie.split("; ");
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].split("=");
        if (cookie[0] === name) {
            return cookie[1];
        }
    }
    return "";
}



// Utilizarea funcției getCookie în cadrul evenimentului "DOMContentLoaded"
document.addEventListener("DOMContentLoaded", function() {
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }
    //get id from url
    let url_string = window.location.href;
    let url = new URL(url_string);
    let id_question = url.searchParams.get("id");

    fetch("http://localhost/TWProject/backend/questions/" + id_question, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    })
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error fetching data.");
            }
        })
        .then(function(data) {
            console.log(data);
            let id = data.id;
            let question_text = data.question_text;
            let difficulty = data.difficulty;
            let id_picture = data.id_picture;
            let id_equation = data.id_equation;
            let points = data.points;
            let saveButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"saveQuestion(" + id + "," + id_picture + "," + id_equation + "," + points + ")\">Save</button>";
            let row = "<tr id=" + id + "><td class=\"question_text\">" + question_text + "</td><td class=\"difficulty\">" + difficulty + "</td><td >" + saveButton + "</td></tr>";
            document.getElementById("table-body-text").innerHTML += row;
            document.getElementById(id).getElementsByClassName("question_text")[0].setAttribute("contenteditable", "true");
            document.getElementById(id).getElementsByClassName("difficulty")[0].setAttribute("contenteditable", "true");
        })

    fetch("http://localhost/TWProject/backend/questions/" + id_question, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    })
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error fetching data.");
            }
        })
        .then(function(data) {
            console.log(data);
            let id = data.id;
            let id_picture = data.id_picture;
            let id_equation = data.id_equation;
            let row;
            if(id_picture !== null){
                let changeButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href = 'http://localhost/TWProject/frontend/html/ChangePicture.html?id=" + id + "'\">Change</button>";
                const imagePromise = getImage(id_picture);
                imagePromise.then(image => {
                    row = "<tr id=" + id + "><td class=\"td-picture\">" + "<img src=\"" + image['download_link'] + "\" alt=\"image\" class=\"picture\">"+ "</td><td>" + changeButton + "</td></tr>";
                    document.getElementById("table-body-photo").innerHTML += row;
                });
            }
            else if(id_equation !== null){
                let changeButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href = 'http://localhost/TWProject/frontend/html/ChangeEquation.html?id=" + id + "'\">Change</button>";
                const equationPromise = getEquation(id_equation);

                equationPromise.then(equation => {
                    row = "<tr id=" + id + "><td class=\"td-equation\">" + equation['equation_text'] + "</td><td>" + changeButton + "</td></tr>";
                    document.getElementById("table-body-photo").innerHTML += row;
                });
            }
            // let saveButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"saveQuestion(" + id + "," + id_picture + "," + id_equation + "," + points + ")\">Save</button>";

        })


    fetch("http://localhost/TWProject/backend/answers/question/" + id_question, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    })
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error fetching data.");
            }
        })
        .then(function(data) {
            for (let i = 0; i < data.length; i++) {
                console.log(data[i].id);
                let id = data[i].id;
                let answer_text = data[i].answer_text;
                let is_correct = data[i].is_correct;
                // let editButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href = 'http://localhost/TWProject/frontend/html/EditQuestion.html?id=" + id + "'\">Edit</button>";
                let deleteButton = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"deleteAnswer(" + id + "," + id_question + ")\">Delete</button>";
                let saveButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"saveAnswer(" + id + "," + id_question + ")\">Save</button>";
                // add unique id to each row
                let row = "<tr id=" + id + "><td>" + id + "</td><td class=\"answer-text\">" + answer_text + "</td><td class=\"id-correct\">" + is_correct + "</td><td >" + saveButton + deleteButton + "</td></tr>";
                document.getElementById("table-body").innerHTML += row;

                let answerTextElements = document.getElementsByClassName("answer-text");
                for (let i = 0; i < answerTextElements.length; i++) {
                    answerTextElements[i].contentEditable = true;
                }
                let idCorrectElements = document.getElementsByClassName("id-correct");
                for (let i = 0; i < idCorrectElements.length; i++) {
                    idCorrectElements[i].contentEditable = true;
                }

            }})
        .catch(function(error) {
            console.log(error);
        });
});

function saveQuestion(id, id_picture, id_equation, points) {
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }
    let questionText = document.getElementById(id).getElementsByClassName("question_text")[0].innerHTML;
    let difficulty = document.getElementById(id).getElementsByClassName("difficulty")[0].innerHTML;

    let data = {
        "question_text": questionText,
        "difficulty": difficulty,
        "id_picture": id_picture,
        "id_equation": id_equation,
        "points": points
    }

    fetch("http://localhost/TWProject/backend/questions/" + id, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        },
        body: JSON.stringify(data)
    })
        .then(function(response) {
            if (response.ok) {
                window.location.href = "http://localhost/TWProject/frontend/html/EditQuestion.html?id=" + id;
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error fetching data.");
            }
        })
}

function saveAnswer(id, id_question) {
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }
    let answerText = document.getElementById(id).getElementsByClassName("answer-text")[0].innerHTML;
    let isCorrect = document.getElementById(id).getElementsByClassName("id-correct")[0].innerHTML;

    let data = {
        "answer_text": answerText,
        "is_correct": isCorrect,
        "question_id": id_question
    }

    fetch("http://localhost/TWProject/backend/answers/" + id, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        },
        body: JSON.stringify(data)
    })
        .then(function(response) {
            if (response.ok) {
                window.location.href = "http://localhost/TWProject/frontend/html/EditQuestion.html?id=" + id_question;
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error updating question.");
            }
        })
}


function deleteAnswer(id, id_question) {
    console.log(id_question);
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }
    fetch("http://localhost/TWProject/backend/answers/" + id, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    })
        .then(function(response) {
            if (response.ok) {
                window.location.href = "http://localhost/TWProject/frontend/html/EditQuestion.html?id=" + id_question;
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error deleting question.");
            }
        })
}

function addAnswer() {
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }

    let url_string = window.location.href;
    let url = new URL(url_string);
    let id_question = url.searchParams.get("id");

    let answerText = prompt("Enter the answer text:");
    let isCorrect = prompt("Is the answer correct? (true/false):");


    let data = {
        "answer_text": answerText,
        "is_correct": isCorrect,
        "question_id": id_question
    };

    fetch("http://localhost/TWProject/backend/answers", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        },
        body: JSON.stringify(data)
    })
        .then(function(response) {
            if (response.ok) {
                window.location.href = "http://localhost/TWProject/frontend/html/EditQuestion.html?id=" + id_question;
                return response.text(); // Primim răspunsul ca text
            } else if (response.status === 401) {
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            } else {
                throw new Error("Error fetching data.");
            }
        })
        .then(function(responseText) {
            // Manipulăm răspunsul ca text și actualizăm interfața utilizatorului în consecință
            console.log(responseText);
            // Dacă este necesar, puteți efectua alte acțiuni aici în funcție de răspunsul serverului
        })
        .catch(function(error) {
            console.log(error);
        });
}

function getImage(imageId) {
    const token = getCookie("token");
    const requestOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    };

    return fetch("http://localhost/TWProject/backend/pictures/" + imageId, requestOptions)
        .then(response => {
            if (response.ok) {
                console.log(response);
                return response.json();
            } else if (response.status === 444){ // no questions found
                alert('No image found.');
            }
        })
        .then(result => {
            return result;
        })
        .catch(error => console.log('error', error));
}

function getEquation(equationId){
    let token = getCookie("token");
    let requestOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    };
    return fetch("http://localhost/TWProject/backend/equations/" + equationId, requestOptions)
        .then(response => {
            if (response.ok) {
                console.log(response);
                return response.json();
            } else if (response.status === 444){ // no questions found
                alert('No equation found.');
            }
        })
        .then(result => {
            return result;
        })
        .catch(error => console.log('error', error));
}

