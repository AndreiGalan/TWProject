function resetPasswordFirstStep() {
    let email = document.getElementById('email').value;

    if (email === "") {
        alert("Please fill in all the fields!");
        return;
    }

    let data = {
        email: email,
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
                response.json().then(json => {
                    let expirationDate = new Date();
                    expirationDate.setTime(expirationDate.getTime() + (24 * 60 * 60 * 1000)); // 24 hours in milliseconds

                    document.cookie = "email=" + json.email + ";expires=" + expirationDate.toUTCString() + "; path=/";

                    window.location.href = "http://localhost/TWProject/frontend/html/PasswordCode.html";

                    alert('Code was sent to your email address!');
                }).catch(error => {
                    console.log('Eroare la parsarea răspunsului JSON:', error);
                });
            } else {
                alert('Invalid email!');
                console.log('Invalid credentials.');
                console.log(response);
            }
        })
        .catch(error => {
            console.log('A network error occurred:', error);
        });

    document.querySelector('form').setAttribute('target', '_self');
}
