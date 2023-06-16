function login() {
    let password = document.getElementById('password').value;
    let email = document.getElementById('email').value;

    if (password === "" || email === "") {
        alert("Please fill in all the fields!");
        return;
    }


    let data = {
        email: email,
        password: password
    };

    fetch('http://localhost/TWProject/backend/auth/login', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                    response.json().then(json => {
                        //salvați tokenul în localStorage
                        //localStorage.setItem('token', json.token);
                        // Calculate the expiration time
                        let expirationDate = new Date();
                        expirationDate.setTime(expirationDate.getTime() + (24 * 60 * 60 * 1000)); // 24 hours in milliseconds

                        // Set the cookie
                        document.cookie = "token=" + json.token + ";expires=" + expirationDate.toUTCString() + "; path=/";
                        document.cookie = "id=" + json.id + ";expires=" + expirationDate.toUTCString() + "; path=/";


                        window.location.href = "http://localhost/TWProject/frontend/html/HomeLogin.html";
                    }).catch(error => {
                        console.log('Eroare la parsarea răspunsului JSON:', error);
                    });
            } else {
                alert('Email or password invalid!');
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