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
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }

    fetch("http://localhost/TWProject/backend/questions", {
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
                let question_text = data[i].question_text;
                let difficulty = data[i].difficulty;
                let editButton = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href = 'http://localhost/TWProject/frontend/html/EditQuestion.html?id=" + id + "'\">Edit</button>";
                let deleteButton = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"deleteQuestion(" + id + ")\">Delete</button>";
                // add buttons to table
                let row = "<tr><td>" + id + "</td><td>" + question_text + "</td><td>" + difficulty + "</td><td>" + editButton + deleteButton + "</td></tr>";
                document.getElementById("table-body").innerHTML += row;
            }})
        .catch(function(error) {
            console.log(error);
        });
});

function deleteQuestion(id) {
    console.log(id);
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }
    fetch("http://localhost/TWProject/backend/questions/" + id, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    })
        .then(function(response) {
            if (response.ok) {
                window.location.href = "http://localhost/TWProject/frontend/html/Questions.html";
            }
            else if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                throw new Error("Error deleting question.");
            }
        })
}
