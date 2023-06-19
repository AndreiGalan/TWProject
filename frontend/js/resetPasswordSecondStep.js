function resetPasswordSecondStep() {
    let inputCode1 = document.getElementById('input1').value;
    let inputCode2 = document.getElementById('input2').value;
    let inputCode3 = document.getElementById('input3').value;
    let inputCode4 = document.getElementById('input4').value;

    let code = inputCode1 + inputCode2 + inputCode3 + inputCode4;

    let email = getCookie('email');


    let data = {
        email: email,
        resetCode: code
    };


    fetch('http://localhost/TWProject/backend/auth/enter-code', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                response.json().then(json => {
                    let expirationDate = new Date();
                    expirationDate.setTime(expirationDate.getTime() + (24 * 60 * 60 * 1000)); // 24 hours in milliseconds

                    console.log(json.code);
                    document.cookie = "code=" + json.code + ";expires=" + expirationDate.toUTCString() + "; path=/";

                    window.location.href = "http://localhost/TWProject/frontend/html/NewPassword.html";

                }).catch(error => {
                    console.log('Eroare la parsarea răspunsului JSON:', error);
                });
            } else {
                alert('Code is invalid!');
                // Tratați răspunsul în caz de eroare
                console.log('Invalid credentials.');
                console.log(response);
            }


        })
        .catch(error => {
            // Tratați eroarea în cazul în care solicitarea de rețea a eșuat
            console.log('A apărut o eroare de rețea:', error);
        });
}

function resendCode() {
    let resendButton = document.getElementById("resendButton");
    let countdown = 60;

    resendButton.innerHTML = countdown + " sec";
    resendButton.disabled = true;

    let timer = setInterval(function() {
        countdown--;
        resendButton.innerHTML = countdown + " sec";

        if (countdown <= 0) {
            clearInterval(timer);
            resendButton.innerHTML = "Resend Code";
            resendButton.disabled = false;
        }
    }, 1000);
    let email = getCookie('email');

    let data = {
        email: email
    };

    fetch('http://localhost/TWProject/backend/auth/reset-password', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                alert('Code has been sent to your email!');
                response.json().then(json => {
                    console.log(json);
                }
                ).catch(error => {
                    console.log('Eroare la parsarea răspunsului JSON:', error);
                })
            } else {
                console.log('Invalid credentials.');
                console.log(response);
            }
        })
        .catch(error => {
            console.log('A network error occurred:', error);
        });
}

window.addEventListener('DOMContentLoaded', () => {
    resendButton.disabled = false;
});

function getCookie(name) {
    var cookies = document.cookie.split("; ");
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split("=");
        if (cookie[0] === name) {
            return cookie[1];
        }
    }
    return "";
}
