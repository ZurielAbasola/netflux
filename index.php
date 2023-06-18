<?php
require_once("includes/classes/config.php");

if(!isset($_SESSION["userLoggedIn"])) {
    header("Location: register.php");
}
?>