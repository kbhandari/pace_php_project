<?php
/**
 * Created by PhpStorm.
 * User: kbhandari
 */
require_once("config.php");

// Assigning $_POST values to individual variables for reuse.
$email = $_POST['emailaddress'] . "@pace.edu";
$password = $_POST['password'];
$fname = $_POST['firstname'];
$mname = $_POST['middlename'];
$lname = $_POST['lastname'];
$contactno = $_POST['contactNo'];
$role = new UserRoles(UserRoles::USER);

//Creating a variable to hold the "@return boolean value returned by function createNewUser - is boolean 1 with
//successfull and 0 when there is an error with executing the query .

$newuser = createNewUser($email, $password, $fname, $mname, $lname, $contactno, $role);

if ($newuser == 1) {
    sendRegistrationEmail($email);
    $_SESSION['username'] = $email;
    //header("Location: mail_options.php");
    //header("Location: createNewRecord.php");
    echo "<h1>User created successfully:</h1>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    $_SESSION['errMsg'] = "Error while creating the user: " . $newuser;
    header("Location: createNewRecord.php");
}

?>
