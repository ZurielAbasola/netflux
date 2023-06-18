<?php
ob_start(); // Turns on output buffering.
session_start(); // Starts a session.

date_default_timezone_set("America/Edmonton"); // Sets the default timezone.

// Connect to the database.
// If the connection fails, then exit the script and display the error message.
try {
    $con = new PDO("mysql:dbname=netflux;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage(Constants::$firstNameCharacters));
}
?>