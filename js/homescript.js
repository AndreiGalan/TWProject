function myFunction() {
  var x = document.getElementById("myTopnav");

  if (x.className === "navigation-menu") {
    x.className += " responsive";
  } else {
    x.className = "navigation-menu";
  }
}