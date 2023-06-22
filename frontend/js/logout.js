import { deleteCookie } from './cookie.js';

// function that deletes the token from the cookies and redirects to the login page

// on click on the logout button
document.getElementById('logout').addEventListener('click', logOut);

function logOut(){
    deleteCookie('token');

    // redirect to home page
    window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
}