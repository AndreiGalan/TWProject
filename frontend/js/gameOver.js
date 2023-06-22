//create function replayGame
function replayGame() {
    const difficulty = localStorage.getItem("difficulty");
    localStorage.removeItem("difficulty");
    window.location.href = `Game.html?difficulty=${difficulty}`;
}

function printPoints() {
    console.log('Ponits set');
    const points = localStorage.getItem("points");
    localStorage.removeItem("points");

    const pointsHolder = document.querySelector(".points");
    if(points === '0'){
        pointsHolder.innerHTML = "<p>You didn't answer correctly to the minimum of 5 questions !( <strong> 0 </strong> points )</p>";
    }
    else{
        pointsHolder.innerHTML = "<p>You have reached the minimum correct answers(5) and obtained <strong>" + points +" </strong> points!</p>";
    }
}

printPoints();