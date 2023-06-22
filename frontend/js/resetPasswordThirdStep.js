function ResetPasswordThirdStep(){
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('password2').value;

    if (password === "" || confirmPassword === "") {
        alert("Please fill in all the fields!");
        return;
    }

    if (password !== confirmPassword) {
        alert("Passwords don't match!");
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

    let email = getCookie('email');


    let data = {
        email: email,
        code: getCookie('code'),
        password: password
    };

    fetch('http://localhost/TWProject/backend/auth/change-password', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                alert('Password changed successfully!');
                deleteCookie('email');
                deleteCookie('code');
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            } else if(response.status === 409){
                alert('Password cannot be the same as the old one!');
            }
            else {
                alert('An error occurred while sending the data.');
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
        })
        .catch(error => {
            // Tratați eroarea în cazul în care solicitarea de rețea a eșuat
            console.log('A apărut o eroare de rețea:', error);
        });
}

function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

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
