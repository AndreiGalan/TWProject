// JavaScript code for index.html
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
document.getElementById("seeAllQuestionsBtn").addEventListener("click", function() {
    window.location.href = "AdminAllQuestions.html";
});

document.getElementById("addQuestionBtn").addEventListener("click", function() {
    window.location.href = "AdminAddQuestion.html";
});

    // const fileInput = document.getElementById('fileInput');
    //
    // const form = document.getElementById('uploadForm');
    // form.addEventListener('submit', function(e) {
    //     e.preventDefault();
    //     const file = fileInput.files[0];
    //     const formData = new FormData(form);
    //
    //     console.log(formData);
    //     const token = getCookie("token");
    //
    //     fetch('http://localhost/TWProject/backend/pictures/create', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'multipart/form-data',
    //             'Authorization': 'Bearer ' + token
    //         },
    //         body: formData
    //     }).then(function(response) {
    //         return response.text();
    //     }).then(function(text) {
    //         console.log(text);
    //     }).catch(function(error) {
    //         console.error(error);
    //     })
    // });

    const uploadButton = document.getElementById('uploadButton');
    const fileInput = document.getElementById('fileInput');
    uploadButton.addEventListener('click', function() {
    const file = fileInput.files[0];
    if (!file) {
    alert('Please select a file.');
    return;
}
    console.log(file);
    const formData = new FormData();
    formData.append('file', file);

    const token = getCookie('token'); // Replace with your access token

    fetch('http://localhost/TWProject/backend/pictures/create', {
    method: 'POST',
    headers: {
    'Authorization': 'Bearer ' + token
},
    body: formData
})
    .then(response => response.json())
    .then(data => {
    console.log(data);
    // Process the response
})
    .catch(error => {
    console.error(error);
    // Handle the error
});
});