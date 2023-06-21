function redirectToGame(difficulty) {
    // Redirect to the Game.html page with the selected difficulty as a query parameter
    window.location.href = `Game.html?difficulty=${difficulty}`;
}