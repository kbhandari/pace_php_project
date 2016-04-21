<?php

  /* This is for error reporting and warning display. This code can be commented when running the final application
  -- used for development only -->*/
    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');


  require_once("db-settings.php"); //Require DB connection
  require_once("functions.php"); // Declare - Define all functions.
  date_default_timezone_set("America/New_York");

require_once("class.user.php");
require_once("class.user_roles.php");

  session_start();


?>
