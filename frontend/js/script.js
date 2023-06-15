function myFunction() {
  var x = document.getElementById("myTopnav");
  var container = document.querySelector('.container');

  if (x.className === "navigation-menu") {
    x.className += " responsive";
    container.classList.add('active');
  } else {
    x.className = "navigation-menu";
    container.classList.remove('active');
  }
}
