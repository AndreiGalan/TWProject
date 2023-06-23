
//--------------------------------
// JavaScript code for Upload System Equation
//--------------------------------
//function get cookie
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
// JavaScript code for AdminAddQuestion.html

const addQuestionBtn = document.getElementById('addQuestionBtn-Menu');
const addQuestionMenu = document.getElementById('addQuestionMenu');
const adminPanel = document.getElementById('adminPanel');
const backBtn = document.getElementById('backBtn');
const equationForm = document.getElementById('equationForm');
const questionForm = document.getElementById('questionForm');
addQuestionBtn.addEventListener('click', function() {
    adminPanel.style.display = 'none';
    addQuestionMenu.style.display = 'block';
});

backBtn.addEventListener('click', function() {
    adminPanel.style.display = 'block';
    addQuestionMenu.style.display = 'none';
    equationForm.style.display = 'none';
});

document.getElementById("addQuestionBtn").addEventListener("click", function() {
    document.getElementById("equationForm").style.display = "none";
    document.getElementById("questionForm").style.display = "block";
});

document.getElementById('uploadEquationBtn').addEventListener('click', function () {
    equationForm.style.display = 'block';
    questionForm.style.display = 'none';
    addQuestionMenu.style.display = 'none';
});

document.getElementById("submitEquationsBtn").addEventListener("click", function() {
    //get the text inserted in the textarea
    const equationText = document.getElementById("equationInput").value;
    if(validateEquation(equationText) === false){
        alert("Invalid equation!");
    }else{
        const token = getCookie("token");
        //insert the equation in the database
        const requestOptions = {
            method: 'POST',
            body: JSON.stringify({equation_text: equationText}),
            headers: { 'Content-Type': 'application/json' ,
                'Authorization': 'Bearer ' + token
            }
        }

        fetch("http://localhost/TWProject/backend/equations", requestOptions)
            .then(response => {
                if (response.ok) {
                    alert("Equation added successfully!");
                    window.location.href = "Admin.html";
                } else {
                    alert("Equation couldn't be added! Please try again!");
                }
            })
            .catch(error => {
                    console.log('A network error occurred:', error);
                }
            );

    }
});

function validateEquation(equationText) {

    //check if the equation is not empty
    if(equationText === ""){
        return false;
    }
    //there are no more than 2 letters next to each other
    const regex = /[a-zA-Z]{2}/;
    if(regex.test(equationText)){
        return false;
    }


}
// -----------------------------------
