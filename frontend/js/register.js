function register() {
    let firstName = document.getElementById('firstName').value;
    let lastName = document.getElementById('lastName').value;
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;
    let passwordReset = document.getElementById('passwordReset').value;
    let email = document.getElementById('email').value;
    let male = document.getElementById("male");
    let female = document.getElementById("female");


    if (password !== passwordReset) {
        alert("Passwords don't match!");
        return;
    }

    if (firstName === "" || lastName === "" || username === "" || password === "" || email === "") {
        alert("Please fill in all the fields!");
        return;
    }

    let gender = male.checked ? "male" : "female";

    let data = {
        firstName: firstName,
        lastName: lastName,
        username: username,
        password: password,
        gender: gender,
        email: email
    };

    let jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2ODY4NzE1NjMsImlzcyI6Imh0dHBzOi8vMTI3LjAuMC4xIiwibmJmIjoxNjg2ODcxNTYzLCJleHAiOjE2ODY4NzUxNjMsInVzZXJOYW1lIjoicG9wZXNjdS5pb24ifQ.sU1BVQfrMjN5rgW4XdvaoUqkhkj1eGRsVXtenpIM-fkQsl1Hht0m4qDUD256xEZsu_qgK4EL0EUIW0egueXHdw";
    let bearer = "Bearer " + jwt;

    fetch('http://localhost/TWProject/backend/users', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'Authorization': bearer
        }
    })
        .then(response => {
            if (response.ok) {
                // Tratați răspunsul în caz de succes
                console.log('Datele au fost trimise cu succes.');
                console.log(data);
            } else {
                // Tratați răspunsul în caz de eroare
                console.log('A apărut o eroare la trimiterea datelor.');
                console.log(data);
            }
        })
        .catch(error => {
            // Tratați eroarea în cazul în care solicitarea de rețea a eșuat
            console.log('A apărut o eroare de rețea:', error);
        });
}
