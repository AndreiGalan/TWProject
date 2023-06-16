// Definirea funcției getCookie
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

// Utilizarea funcției getCookie în cadrul evenimentului "DOMContentLoaded"
document.addEventListener("DOMContentLoaded", function() {
    let token = getCookie("token");
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Login.html";
    }

    fetch("http://localhost/TWProject/backend/users/ranking", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        }
    })
        .then(function(response) {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Error fetching data.");
            }
        })
        .then(function(data) {
            for (let i = 0; i < data.length; i++) {
                let position = data[i].ranking;
                let username = data[i].username;
                let score = data[i].points;
                let playingSince = data[i].created_at;
                let row = "<tr><td>" + position + "</td><td>" + username + "</td><td>" + score + "</td><td>" + playingSince + "</td></tr>";
                document.getElementById("table-body").innerHTML += row;
            }})
        .catch(function(error) {
            console.log(error);
        });
});
