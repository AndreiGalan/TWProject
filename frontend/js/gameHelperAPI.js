import {getCookie} from "./cookie.js";
export function getAnswers(questionId) {

    const requestOptions = {
        method: 'GET'
    }

    return fetch("http://localhost/TWProject/backend/answers/question/" + questionId, requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else if (response.status === 444){ // no questions found
                alert('No answers found.');
            }
        })
        .then(result => {
            return result;
        })
        .catch(error => console.log('error', error));

}

export function getEquation(idEquation) {
    const requestOptions = {
        method: 'GET'
    }

    return fetch("http://localhost/TWProject/backend/equations/" + idEquation, requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else if (response.status === 444){ // no questions found
                alert('No equation found.');
            }
        })
        .then(result => {
            return result;
        })
        .catch(error => console.log('error', error));
}

export function getImage(imageId) {
    const requestOptions = {
        method: 'GET'
    };

    return fetch("http://localhost/TWProject/backend/pictures/" + imageId, requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else if (response.status === 444){ // no questions found
                alert('No image found.');
            }
        })
        .then(result => {
            return result;
        })
        .catch(error => console.log('error', error));
}

export function getDifficulty() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('difficulty');
}

export function saveScore(points){

    const body = {
        "points": points
    }

    const requestOptions = {
        method: 'PUT',
        body: JSON.stringify(body),
        headers: { 'Content-Type': 'application/json' ,
        'Authorization': 'Bearer ' + getCookie('token')
        }
    }

    fetch("http://localhost/TWProject/backend/users", requestOptions)
        .then(response => {
            if(response.status === 401){//unauthorized
                window.location.href = 'Home.html';
            }
        })
        .catch(error => console.log('error', error));

}