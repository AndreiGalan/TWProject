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

    if(password.length < 8){
        alert("Password must have at least 8 characters!");
        return;
    }

    if(!password.match(/[a-z]/g)){
        alert("Password must contain at least one lowercase letter!");
        return;
    }

    if(!password.match(/[A-Z]/g)){
        alert("Password must contain at least one uppercase letter!");
        return;
    }

    if(!password.match(/[0-9]/g)){
        alert("Password must contain at least one digit!");
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

    fetch('http://localhost/TWProject/backend/auth/register', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                // Tratați răspunsul în caz de succes
                console.log('Datele au fost trimise cu succes.');
                console.log(data);

                alert('An email has been sent to your email address. Please verify your account.');

                window.location.href = "http://localhost/TWProject/frontend/html/login.html";

            } else if ( response.status === 409){

                response.json().then(data => {
                    if (data.message === 'username') {
                        alert('Username already exists.');
                    } else if (data.message === 'email') {
                        alert('Email already exists.');
                    } else {
                        alert('An error occurred while sending the data.');
                    }
                    console.log(data);
                });

            }
            else if (response.status === 422){
                alert('Invalid email.');
            }
        })
        .catch(error => {
            // Tratați eroarea în cazul în care solicitarea de rețea a eșuat
            console.log('A apărut o eroare de rețea:', error);
        });
}
