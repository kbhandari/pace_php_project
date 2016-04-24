<?php

/* This is for error reporting and warning display. This code can be commented when running the final application
-- used for development only -->*/
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
date_default_timezone_set("America/New_York");


require_once("db-settings.php"); //Require DB connection
require_once("functions.php"); // Declare - Define all functions.


require_once("class.user.php");
require_once("class.user_roles.php");

session_start();

//loggedInUser can be used globally if constructed
global $loggedInUser;
if(isset($_SESSION["ThisUser"]) && is_object($_SESSION["ThisUser"]))
{
    $loggedInUser = $_SESSION["ThisUser"];
}

echo "SESSION VARIABLES ";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo "LOGGED IN USER DETAILS";
echo "<pre>";
print_r($loggedInUser);
echo "</pre>";

?>
