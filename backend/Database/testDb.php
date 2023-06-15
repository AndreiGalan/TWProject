<?php

include_once "../Models/Cat.php";
include_once "CatDAO.php";

// create cat
$cat = new Cat("Tom", "Persian");

// catDAO
$catDAO = new CatDAO();

// insert cat
//$catDAO->create($cat);

echo "Cat inserted successfully!";

// get all cats
$cats = $catDAO->findAll();

// print all cats
foreach ($cats as $cat) {
    echo $cat->getName() . " " . $cat->getBreed() . "<br>";
}

//$cat1 = new Cat('Whiskers', 'Siamese', 1);
//$cat2 = new Cat('Fluffy', 'Persian', 2);
//$cats = [$cat1, $cat2];
//$json = json_encode($cats);
//echo $json;
