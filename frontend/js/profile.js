// when the profile page is loaded, fill in the user's information: first name, last name, username, email, points, and ranking
import {getCookie} from './cookie.js';

let p1 = 1000;
let p2 = 5000;

document.addEventListener('DOMContentLoaded', function() {
    display_info();
    complete_info();
});
function display_info(){
    let token = getCookie('token');

    fetch('http://localhost/TWProject/backend/users/id', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        }
    ).then(response => {
        if(response.ok){
            // get the first name, last name, username, email, points, and ranking of the user

            response.json().then(data => {
                console.log(data);

                document.getElementById('profile_first_name').innerHTML = data.first_name;
                document.getElementById('profile_last_name').innerHTML = data.last_name;
                document.getElementById('profile_username').innerHTML = data.username;
                document.getElementById('profile_email').innerHTML = data.email;
                document.getElementById('profile_points').innerHTML = data.points;
                document.getElementById('profile_ranking').innerHTML = data.ranking;

                // if the user's points are less than 100, they are a beginner
                // if the user's points are between 100 and 500, they are an intermediate
                // if the user's points are more than 500, they are an expert
                if(data.points < p1) {
                    document.getElementById('profile_level').innerHTML = 'Beginner';
                } else if(data.points >= p1 && data.points < p2) {
                    document.getElementById('profile_level').innerHTML = 'Intermediate';
                } else {
                    document.getElementById('profile_level').innerHTML = 'Expert';
                }
            });
        } else if(response.status === 401) { // if the user is not logged in, redirect to the login page
            window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
        } else {
            console.log('Error getting user info');
        }
    }).catch(error => {
        console.log('Error getting user info');
    });
}

// add an event listener to the edit button
document.getElementById('edit-info-button').addEventListener('click', function() {
    complete_info();
});

// when the user clicks on the edit button, the values of the input fields are filled in with the user's information
function complete_info(){
    let token = getCookie('token');

    console.log(token);

    fetch('http://localhost/TWProject/backend/users/id', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        }
    ).then(response => {
        if(response.ok){
            // get the first name, last name, username, email, points, and ranking of the user

            response.json().then(data => {
                // first-name is the id of the input field for the first name
                document.getElementById('first-name').value = data.first_name;
                document.getElementById('last-name').value = data.last_name;
                document.getElementById('username').value = data.username;
                document.getElementById('email').value = data.email;
            });
        } else if(response.status === 401) { // if the user is not logged in, redirect to the login page
            window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
        } else {
            console.log('Error getting user info');
        }
    }).catch(error => {
        console.log('Error getting user info');
    });
}

// add an event listener to the save button
document.getElementById('save-info-button').addEventListener('click', function() {
    save_info();
});

// when they click on the save button, the information is updated(if the information is valid)
function save_info(){
    // get the values from the input fields
    let first_name = document.getElementById('first-name').value;
    let last_name = document.getElementById('last-name').value;
    let username = document.getElementById('username').value;
    let email = document.getElementById('email').value;

    // check if the information is valid
    if(first_name === ''){
        alert('Please enter your first name');
        return;
    } else if(last_name === ''){
        alert('Please enter your last name');
        return;
    } else if(username === ''){
        alert('Please enter your username');
        return;
    } else if(email === ''){
        alert('Please enter your email');
        return;
    }

    // check if the username or email already exist - if they do, the user cannot change their username or email
    let token = getCookie('token');

    fetch('http://localhost/TWProject/backend/users?username=' + username, {
            method: 'GET',
            headers: {'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token}
    }).then(response => {
        if(response.ok){
            response.json().then(data => {
                // if the username already exists and it is not the user's username, the user cannot change their username
                let old_username = document.getElementById('profile_username').innerHTML;
                if(data.username === username && username !== old_username){
                    alert('This username is already taken');
                    return;
                } else {
                    // check if the email already exists
                    verify_email_already_exists(first_name, last_name, username, email);
                }
            });
        } else if(response.status === 404){ // user with this username does not exist
            // check if the email already exists
            verify_email_already_exists(first_name, last_name, username, email);
        } else {
            console.log('Error getting users');
        }
    }).catch(error => {
        console.log('Error getting users: ' + error);
    });
}

// verify if the username already exists and if not, update the information
function verify_email_already_exists(first_name, last_name, username, email){
    let token = getCookie('token');

    fetch('http://localhost/TWProject/backend/users?email=' + email, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token }
    }).then(response => {
        if(response.ok){
            response.json().then(data => {
                // if the email already exists and it is not the user's email, the user cannot change their email
                let old_email = document.getElementById('profile_email').innerHTML;
                if(data.email === email && email !== old_email){
                    alert('This email is already taken');
                    return;
                } else {
                    // if the username and email are valid, update the information
                    update_info(first_name, last_name, username, email);
                }
            });
        } else if(response.status === 404) { // user with this email does not exist
            // if the username and email are valid, update the information
            update_info(first_name, last_name, username, email);
        } else {
            console.log('Error getting users');
        }
    }).catch(error => {
        console.log('Error getting users: ' + error);
    });
}

function update_info(first_name, last_name, username, email){
    let token = getCookie('token');

    let data = {
        firstName: first_name,
        lastName: last_name,
        username: username,
        email: email
    }

    fetch('http://localhost/TWProject/backend/users/id', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
        // send the new information to the backend
        , body: JSON.stringify(data)
    }).then(response => {
        if(response.ok){
            display_info();

            alert('Information updated successfully');

            console.log('Information updated successfully');
            window.location.href = "#";
        } else if(response.status === 401) { // if the user is not logged in, redirect to the login page
            window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
        } else {
            console.log('Error updating user info');

            alert('Error updating user info');
        }
    }).catch(error => {
        console.log('Error updating user info: '+ error);

        alert('Error updating user info');
    });
}

//  --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

// add an event listener to the change password button
document.getElementById('change-password-button').addEventListener('click', function() {
    clear_passwords();
});

// when the user clicks on the change password button, the input fields are cleared
function clear_passwords(){
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-password').value = '';
}

// add an event listener to the change password button
document.getElementById('save-password-button').addEventListener('click', function() {
    save_password();
});

// when the uesr clicks on the save password button, the password is updated(if the information is valid)

function save_password(){
    let old_password = document.getElementById('current-password').value;
    let new_password = document.getElementById('new-password').value;
    let confirm_password = document.getElementById('confirm-password').value;

    // check if the information is valid
    if(old_password === ''){
        alert('Please enter your old password');
        return;
    }
    if(new_password === ''){
        alert('Please enter your new password');
        return;
    }
    if(confirm_password === ''){
        alert('Please confirm your new password');
        return;
    }

    if(new_password !== confirm_password){
        alert('The passwords do not match');
        return;
    }

    // get the user's email
    getEmail().then(email => {
        // check if the old password is correct
        verifyPassword(email, old_password).then(response => {
            if(response === true){
                // if the old password is correct, the password is updated
                update_password(new_password);
            } else {
                alert('The old password is incorrect');
            }
        }).catch(error => {
            console.log('Error:', error);
        })
    }).catch(error => {
        console.log('Error:', error);
    });
}

function update_password(new_password){
    let token = getCookie('token');

    let data = {
        password: new_password
    }

    fetch('http://localhost/TWProject/backend/users/id', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        // send the new password to the backend
        body: JSON.stringify(data)
    }).then(response => {
        if(response.ok){
            alert('Password updated successfully');

            // after the 'ok' button on the alert is pressed, the user is redirected to the profile page
            window.location.href="#";
        } else if(response.status === 401) { // if the user is not logged in, redirect to the login page
            window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
        } else {
            console.log('Error updating password');
            return false;
        }
    }).catch(error => {
        console.log('Error updating password');
        return false;
    })
}

// function that returns the user's information
async function getEmail() {
    try {
        return await getUserEmail(); // Return the email value
    } catch (error) {
        console.log('Error:', error);
        throw error;
    }
}

async function getUserEmail() {
    let token = getCookie('token');

    try {
        let response = await fetch('http://localhost/TWProject/backend/users/id', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        if (response.ok) {
            let data = await response.json();
            return data.email;
        } else if(response.status === 401) { // if the user is not logged in, redirect to the login page
            window.location.href = "http://localhost/TWProject/frontend/html/Home.html";
        } else {
            throw new Error('Error getting user info');
        }
    } catch (error) {
        console.log('Error:', error);
        throw error;
    }
}


// function that verifies if the old password is correct
async function verifyPassword(email, old_password) {
    let data = {
        email: email,
        password: old_password
    };

    try {
        let response = await fetch('http://localhost/TWProject/backend/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data) // Send the email and password to the backend
        });

        return response.ok; // Return true if response.ok is true, otherwise false
    } catch (error) {
        console.log('Error verifying password');
        return false; // Return false in case of an error
    }
}