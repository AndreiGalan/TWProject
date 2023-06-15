// register.js

function register() {
    let firstName = document.getElementById('firstName').value;

    let lastName = document.getElementById('lastName').value;
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;
    let passwordReset = document.getElementById('passwordReset').value;
    let email = document.getElementById('email').value;
    let male = document.getElementById("male");
    let female = document.getElementById("female");
    if(password !== passwordReset) {

        alert("Passwords don't match!");
        return;
    }
    if(firstName === "" || lastName === "" || username === "" || password === "" || email === "") {

        alert("Please fill all the fields!");
        return;
    }
    // put data in body

    let gender = "male";
    if(female != null)
        gender = "female";

    let data = {
        firstName: firstName,
        lastName: lastName,
        username: username,
        password: password,
        email: email,
        gender: gender
    }

    // put JWT in header
    // let jwt = localStorage.getItem('jwt');
    let jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2ODY4NjI4ODksImlzcyI6Imh0dHBzOi8vMTI3LjAuMC4xIiwibmJmIjoxNjg2ODYyODg5LCJleHAiOjE2ODY4NjY0ODksInVzZXJOYW1lIjoicG9wZXNjdS5pb24ifQ.zZPmVkGE5UopMW_Z2qvneD2WVfcnk02j4g_OVbTepRgWyJWAm5IQ6FCZMSOTKKfYarVZrqcKfz9M-rMf6UeiSg";
    let bearer = "Bearer " + jwt;

    fetch('http://localhost/TWProject/backend/users', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'Authorization': bearer
        },
        mode: 'no-cors'
    })
        .then(response => response.text())
        .then(function(response){
            if (response.ok) {
                // Tratați răspunsul în caz de succes
                console.log('Datele au fost trimise cu succes.');
            } else {
                // Tratați răspunsul în caz de eroare
                console.log('A apărut o eroare la trimiterea datelor.');
            }
        })
        .catch(function(error) {
            // Tratați eroarea în cazul în care solicitarea de rețea a eșuat
            console.log('A apărut o eroare de rețea:', error);
        });
}