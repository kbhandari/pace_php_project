<?php
/**
 * Created by PhpStorm.
 * User: kbhandari
 * Date: 20/04/16
 * Time: 5:27 PM
 */

require_once("config.php");

// Assigning $_POST values to individual variables for reuse.
$email = $_GET['email'];
$validateString = $_GET['validate'];

$confirmEmail = confirmEmail($email, $validateString);

if ($confirmEmail == 1) {
    echo "<h1>Email validated successfully:</h1>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo "<h1>Error while validating email</h1>";
    echo "<pre>";
    print_r($confirmEmail);
    echo "</pre>";
}
