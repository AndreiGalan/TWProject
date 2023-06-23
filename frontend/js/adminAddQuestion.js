import {getCookie} from "./cookie.js";
// JavaScript code for AdminAddQuestion.html
document.getElementById("uploadEquationBtn").addEventListener("click", function() {
    document.getElementById("equationForm").style.display = "block";
    document.getElementById("questionForm").style.display = "none";
});

document.getElementById("addQuestionBtn").addEventListener("click", function() {
    document.getElementById("equationForm").style.display = "none";
    document.getElementById("questionForm").style.display = "block";
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
                    window.location.href = "AdminAddQuestion.html";
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





