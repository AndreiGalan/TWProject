// Definirea funcției getCookie
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
    let url_string = window.location.href;
    let url = new URL(url_string);
    let id_question = url.searchParams.get("id");

    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }

    fetch("http://localhost/TWProject/backend/equations", {
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
            const question_dataPromise = getQuestionData(id_question);
            let question_text = "";
            let difficulty = "";
            let points = "";
            question_dataPromise.then(question_data =>{
                question_text = question_data.question_text;
                difficulty = question_data.difficulty;
                points = question_data.points;

                for (let i = 0; i < data.length; i++) {
                    let id = data[i].id;
                    let equationText = data[i].equation_text;
                    let changeButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"changePicture('"+ id + "','" + id_question +"','"+ question_text + "','" + difficulty + "','" + points + "')\">Change</button>";
                    let row = "<tr><td>" + id + "</td><td class=\"td-picture\">" + equationText + "</td><td>"+ changeButton +"</td></tr>";


                    document.getElementById("table-body").innerHTML += row;
                }})
                .catch(function(error) {
                    console.log(error);
                });
        })
});

function getQuestionData(id){
    let token = getCookie("token");
    let requestOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    };
    return fetch("http://localhost/TWProject/backend/questions/" + id, requestOptions)
        .then(response => {
            if (response.ok) {
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

function changePicture(id, id_question, question_text, difficulty, points){
    let token = getCookie("token");
    if(token === ""){
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }

    let data = {
        "question_text": question_text,
        "difficulty": difficulty,
        "id_equation": id,
        "points": points
    }

    fetch("http://localhost/TWProject/backend/questions/" + id_question, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        },
        body: JSON.stringify(data)
    })
        .then(function(response) {
            if (response.ok) {
                window.location.href = "http://localhost/TWProject/frontend/html/Questions.html";
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error fetching data.");
            }
        })
}
