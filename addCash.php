<?php
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "poker";
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    session_start();
    //var_dump($_SESSION["cash"]);
    //echo "<br>";
    $_SESSION["cash"] = $_SESSION["cash"] + $_POST["add"];
    //var_dump($_POST);
    //echo "<br>";
    //var_dump($_SESSION["cash"]);
    $query = "UPDATE users SET Cash='".$_SESSION["cash"]."' WHERE Id='".$_SESSION["userId"]."'";
    //var_dump($query);
    $conn->query($query);
?>