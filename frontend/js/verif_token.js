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

document.addEventListener("DOMContentLoaded", function() {
    let token = getCookie("token");

    console.log(token);
    if (token === "") {
        window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
    }
    else
    {
        window.location.href = "http://localhost/TWProject/frontend/html/HomeLogin.html";
    }
});