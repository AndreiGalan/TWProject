document.addEventListener("DOMContentLoaded", function() {
    //get email from url
    let url = window.location.href;
    let email = url.split("email=")[1];

    console.log(email);

    data = {
        email: email
    }

    fetch("http://localhost/TWProject/backend/auth/verify-email", {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        }
    })
        .then(function (response) {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Error verifying email.");
            }
        })
})
