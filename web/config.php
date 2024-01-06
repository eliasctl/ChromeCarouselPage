<?php
session_start();
require('fonctions.php');
require('assets/assets.php');

$local_linux = true;
if ($local_DB === true) {
    $conn = mysqli_connect('localhost', 'root', 'root', 'ChromeCarouselPage');
} else {
    $conn = mysqli_connect('localhost', 'root', 'root', 'ChromeCarouselPage');
}

if ($conn === false) {
    die("ERREUR : Unable to connect to the database. " . mysqli_connect_error());
}
if (isset($page)) {
    echo "<title>Chrome Carousel Page | " . $page . "</title>";
} else {
    echo "<title>Chrome Carousel Page</title>";
}
?>

<link rel="stylesheet" href="assets/style.css" />
<meta charset="utf-8" />