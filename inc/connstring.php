<?php

/**
 * Provides connexion to the database.
 */

$mysqli = new mysqli('localhost', 'root', '', 'studentsonthego');

if(mysqli_connect_error()) {
    echo "Connection failed: " . mysqli_connect_error() . "<br>";
    exit();
}

$mysqli->set_charset("utf-8");

?>