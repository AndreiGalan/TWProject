function contact(){
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let message = document.getElementById('message').value;

    if (name === "" || email === "" || message === "") {
        alert("Please fill in all the fields!");
        return;
    }

    let data = {
        name: name,
        email: email,
        message: message
    }

    fetch('http://localhost/TWProject/backend/auth/send-email', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                    alert('Message sent!');
                    window.location.href = "http://localhost/TWProject/frontend/html/ContactLogOut.html";
            } else {
                alert('Message couldn\'t be sent! Please try again!');
            }
        })
        .catch(error => {
            console.log('A network error occurred:', error);
        });
}