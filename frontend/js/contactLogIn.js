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

function contactLogIn(){
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let message = document.getElementById('message').value;

    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
    }

    if (name === "" || email === "" || message === "") {
        alert("Please fill in all the fields!");
        return;
    }

    let data = {
        name: name,
        email: email,
        message: message
    }

    fetch('http://localhost/TWProject/backend/users/send-email', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            "Authorization": "Bearer " + token
        }
    })
        .then(response => {
            if (response.ok) {
                alert('Message sent!');
                window.location.href = "http://localhost/TWProject/frontend/html/Contact.html";
            } else
            if(response.status === 401){
                window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
            }
            else {
                alert('Message couldn\'t be sent! Please try again!');
            }
        })
        .catch(error => {
            console.log('A network error occurred:', error);
        });
}