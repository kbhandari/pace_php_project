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
		contact_no,
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
    $stmt->bind_result($userID, $email, $firstName, $middleName, $lastName, $password, $contactNo, $createdOn, $role, $status);
    while ($stmt->fetch()){
        $row = array('User_ID' => $userID,
            'email' => $email,
            'First_Name' => $firstName,
            'Middle_Name' => $middleName,
            'Last_Name' => $lastName,
            'Password' => $password,
            'contact_no' => $contactNo,
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

function updateThisRecord($userId, $fname, $lname, $email, $status, $contactNo, $role = NULL)
{
    global $mysqli, $db_table_prefix;

    $stmt = $mysqli->prepare(
        "UPDATE " . $db_table_prefix . "User_Details
		SET
		First_Name = ?,
		Last_Name = ?,
		Email = ?,
		Status = ?,
		Contact_no = ?
		WHERE
		User_ID = ?
		LIMIT 1"
    );
    $stmt->bind_param("ssssss", $fname, $lname, $email, $status, $contactNo, $userId);
    $result = $stmt->execute();
    $stmt->close();

    if ($role != NULL && $result == 1) {
        echo $role;
        return updateRole($email, $role);
    } else {
        return $result;
    }
}

function updateRole($userId, $role)
{
    global $mysqli, $db_table_prefix;

    $stmt = $mysqli->prepare(
        "UPDATE ".$db_table_prefix."User_Role_Map
		SET
		Role_ID = ?
		WHERE
		User_ID = ?
		LIMIT 1"
    );
    $stmt->bind_param("ss", $role, $userId);
    $result = $stmt->execute();
    $stmt->close();

    return $result;

}


//Destroys a session as part of logout
function destroySession($name)
{
    if(isset($_SESSION[$name]))
    {
        $_SESSION[$name] = NULL;
        unset($_SESSION[$name]);
    }
}

//Check if a user is logged in
function isUserLoggedIn()
{
    global $loggedInUser,$mysqli,$db_table_prefix;
    $stmt = $mysqli->prepare("SELECT
		User_ID,
		Password
		FROM ".$db_table_prefix."User_Details
		WHERE
		User_ID = ?
		AND
		Password = ?
		AND
		status = 'A'
		LIMIT 1");
    $stmt->bind_param("is", $loggedInUser->user_id, $loggedInUser->hash_pw);
    $stmt->execute();
    $stmt->store_result();
    $num_returns = $stmt->num_rows;
    $stmt->close();

    if($loggedInUser == NULL)
    {
        return false;
    }
    else
    {
        if ($num_returns > 0)
        {
            return true;
        }
        else
        {
            destroySession("ThisUser");
            return false;
        }
    }
}

//Retrieve complete user information of all users
function fetchAllUsers()
{
    global $mysqli,$db_table_prefix;
    $stmt = $mysqli->prepare("SELECT
		UD.User_ID as UserID,
		Email,
		FirstName,
		LastName,
		Password,
		Created_On,
		URM.RoleID as Role,
		Status
		FROM ".$db_table_prefix."User_Details UD, ".$db_table_prefix."User_Role_Map URM
		WHERE UD.User_ID = URM.User_ID
		");

    $stmt->execute();
    $stmt->bind_result($UserID, $UserName, $FirstName, $LastName, $Email, $Password, $MemberSince, $Role, $status);
    while ($stmt->fetch()){
        $row[] = array('UserID' => $UserID,
            'UserName' => $Email,
            'FirstName' => $FirstName,
            'LastName' => $LastName,
            'Email' => $Email,
            'Password' => $Password,
            'MemberSince' => $MemberSince,
            'Role' => $Role,
            'Status' => $status);
    }
    $stmt->close();
    return ($row);
}

function createAd($title, $body, $contactInfo, $catId, $subCatId) {
    global $mysqli, $db_table_prefix, $loggedInUser;

    $guid = getGUID();

    $stmt = $mysqli->prepare(
        "INSERT INTO " . $db_table_prefix . "ad (
		ad_id,
		user_id,
		posting_title,
		posting_body,
		contact_info,
		Cat_id,
		sub_Cat_id
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
    $stmt->bind_param("sssssss", $guid, $loggedInUser->user_id, $title, $body, $contactInfo, $catId, $subCatId);
    $result = $stmt->execute();
    $stmt->close();
    if($result == 1) {
        return $guid;
    } else {
        return $result;
    }
}

function createHousingForRentRec($adId, $location, $postal, $ft_2, $rent, $availableOn, $beedroom, $bathroom, $laundry, $parking, $pets)
{
    global $mysqli, $db_table_prefix;

    $guid = getGUID();

    $stmt = $mysqli->prepare(
        "INSERT INTO " . $db_table_prefix . "h_for_rent (
		h_rent_pref_id,
		location,
		postal_code,
		ft_2,
		rent,
		available_on,
		bedroom,
		bathroom,
		laundry,
		parking,
		pets
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
        ?,
        ?,
        ?
		)"
    );
    $stmt->bind_param("sssssssssss", $guid, $location, $postal, $ft_2, $rent, $availableOn, $beedroom, $bathroom, $laundry, $parking, $pets);
    $result = $stmt->execute();
    $stmt->close();
    if($result == 1) {
        return mapPreference($adId, $guid);
    } else {
        return $result;
    }
}

function mapPreference($adId, $prefId)
{
    global $mysqli, $db_table_prefix;

    $stmt = $mysqli->prepare(
        "INSERT INTO " . $db_table_prefix . "ad_pref_map (
		ad_ID,
		pref_ID
		)
		VALUES (
		?,
		?
		)"
    );
    $stmt->bind_param("ss", $adId, $prefId);
    //print_r($stmt);
    $result = $stmt->execute();
    //print_r($result);
    $stmt->close();
    return $result;
}
?>

