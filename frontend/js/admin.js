// JavaScript code for index.html
document.getElementById("seeAllQuestionsBtn").addEventListener("click", function() {
    window.location.href = "AdminAllQuestions.html";
});

document.getElementById("addQuestionBtn").addEventListener("click", function() {
    window.location.href = "AdminAddQuestion.html";
});

    let fileInput = document.getElementById('fileInput');
    fileInput.addEventListener('change', function(e) {
        const file = fileInput.files[0];
        if(file){
            const reader = new FileReader();

            reader.onload = function(e) {
                const fileContent = e.target.result;
                console.log(fileContent);
            }
            reader.readAsText(file);
            console.log(file);
        }
    });