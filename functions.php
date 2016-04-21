<?php
/**
 * Created by PhpStorm.
 * User: kbhandari
 * Date: 2/21/16
 */

//Create a new user
function createNewUser($email, $password, $fname, $mname, $lname, $contactno, $role)
{
    global $mysqli, $db_table_prefix;

    if (checkExistingUser($email)) {
        return "Email already registered.";
    }

    $guid = getGUID();
    $date = date("Y-m-d H:i:s");
    $hashedPassword = generateHash($password);
    $status = 'N'; // N = New, A = Active, I = Inactive

    $stmt = $mysqli->prepare(
        "INSERT INTO " . $db_table_prefix . "user_details (
		user_id,
		email,
		password,
		first_name,
		middle_name,
		last_name,
		contact_no,
		created_on,
		status
		)
		VALUES (
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
        ?
		)"
    );
    $stmt->bind_param("sssssssss", $guid, $email, $hashedPassword, $fname, $mname, $lname, $contactno, $date, $status);
    $result = $stmt->execute();
    $stmt->close();
    if($result == 1) {
        return mapNewUserRole($guid, $role);
    } else {
        return $result;
    }

}

function mapNewUserRole($userID, $role)
{
    global $mysqli, $db_table_prefix;

    $stmt = $mysqli->prepare(
        "INSERT INTO " . $db_table_prefix . "User_Role_Map (
		User_ID,
		ROLE_ID
		)
		VALUES (
		?,
		?
		)"
    );
    $stmt->bind_param("ss", $userID, $role->value);
    //print_r($stmt);
    $result = $stmt->execute();
    //print_r($result);
    $stmt->close();
    return $result;
}

//Check if the email is already existing
//function definition and declaration
/**
 * @return boolean
 */
function checkExistingUser($email)
{
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare(
        "SELECT
		email
		FROM " . $db_table_prefix . "user_details WHERE email = ?"
    );
    $stmt->bind_param("s", $email);

    $result = $stmt->execute();

    $isExisting = false;
    while ($stmt->fetch()) {
        $isExisting = true;
        break;
    }

    $stmt->close();
    return ($isExisting);
}

function fetchUserDetails($email)
{
    $row = array();
    global $mysqli,$db_table_prefix;
    $stmt = $mysqli->prepare("SELECT
		UD.User_ID as User_ID,
		email,
		First_Name,
		middle_name,
		Last_Name,
		password,
		created_on,
		URM.Role_ID as Role,
		status
		FROM ".$db_table_prefix."user_details UD, ".$db_table_prefix."User_Role_Map URM
		WHERE
		email = ?
		AND UD.User_ID = URM.User_ID
		LIMIT 1");
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $stmt->bind_result($userID, $email, $firstName, $middleName, $lastName, $password, $createdOn, $role, $status);
    while ($stmt->fetch()){
        $row = array('User_ID' => $userID,
            'email' => $email,
            'First_Name' => $firstName,
            'Middle_Name' => $middleName,
            'Last_Name' => $lastName,
            'Password' => $password,
            'created_on' => $createdOn,
            'Role' => $role,
            'status' => $status);
    }
    $stmt->close();
    return ($row);
}

function activateUser($email)
{
    global $mysqli, $db_table_prefix;

    $status = 'A'; // Active

    $stmt = $mysqli->prepare(
        "UPDATE " . $db_table_prefix . "User_Details
		SET
		status = ?
		WHERE
		email = ?
		LIMIT 1"
    );
    $stmt->bind_param("ss", $status, $email);
    $result = $stmt->execute();
    $stmt->close();

    return $result;

}

function createNewMail($from, $to, $cc, $subject, $content)
{
    global $mysqli, $db_table_prefix;

    if (!checkExistingUser($from)) {
        return "User does not exists in the system";
    }

    $guid = getGUID();
    $date = date("Y-m-d H:i:s");

    $stmt = $mysqli->prepare(
        "INSERT INTO " . $db_table_prefix . "mail_details (
		guid,
		mail_from,
		mail_to,
		subject,
		cc,
		content,
		sent_on
		)
		VALUES (
		?,
		?,
		?,
		?,
		?,
		?,
		?
		)"
    );
    $stmt->bind_param("sssssss", $guid, $from, $to, $subject, $cc, $content, $date);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

function generateHash($plainText, $salt = NULL) {
    if ($salt === NULL) {
        $salt = substr(md5(uniqid(rand(), TRUE)), 0, 25);
    }
    else {
        $salt = substr($salt, 0, 25);
    }

    return $salt . sha1($salt . $plainText);
}

function sendRegistrationEmail($email) {
    // the message
    $msg = "Thanks for signing up at PaceExchange!\n";
    $msg = $msg. "Please verify your email address by clicking below:\n\n";
    $msg = $msg. dirname($_SERVER['HTTP_REFERER']);
    $msg = $msg. "/confirmEmail.php?email=". $email . "&validate=" . generateHash($email, sha1(md5($email)));

    file_put_contents('php://stderr', print_r($msg, TRUE));


    // send email
    mail($email,"Confirm your email address",$msg);
}

function confirmEmail($email, $validateString) {
    $userDetails = fetchUserDetails($email);

    if (empty($userDetails)) {
        return "invalid email";
    }
    //See if the user's account is activated
    else if($userDetails["status"] == 'I')
    {
        return "account inactive";
    }
    else if($userDetails["status"] == 'A')
    {
        return "email already verified";
    }
    else if($userDetails["status"] == 'N') {
        file_put_contents('php://stderr', print_r($validateString, TRUE));
        file_put_contents('php://stderr', print_r(generateHash($validateString, sha1(md5($email))), TRUE));
        if ($validateString == generateHash($email, $validateString)) {
            return activateUser($email);
        } else {
            return "validation string mismatch";
        }
    }
    else {
        return "invalid email status";
    }
}

?>

