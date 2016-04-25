<?php
/**
 * Created by PhpStorm.
 * User: kbhandari
 * Date: 22/03/16
 * Time: 4:56 PM
 */

include_once("config.php");

//Prevent the logged-in user visiting this page if he/she is not an ADMIN or EDITOR
if(isUserLoggedIn()) {
    if (UserRoles::ADMIN == $loggedInUser->role->value) {
        $thisUserName = $_GET['userName'];
        echo $thisUserName;

        $foundUser = fetchUserDetails($thisUserName);
        echo "<pre>";
        print_r($foundUser);
        echo "</pre>";
    } else {
        header("Location: myaccount.php"); die();
    }
} else {
    header("Location: index.php"); die();
}

//Forms posted
if(!empty($_POST)) {
    $errors = array();
    $username = trim($_POST["username"]);
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $contactNo = trim($_POST["contactNo"]);
    $userId = trim($_POST["userId"]);
    $status = trim($_POST["status"]);
    if(isset($_POST["role"])) {
        $role = (new UserRoles($_POST["role"]))->value;
    } else {
        $role = NULL;
    }
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    if ($_POST["Update"]) {
        $user = updateThisRecord($userId, $firstname, $lastname, $username, $status, $contactNo, $role);
        print_r($user);
        if($user <> 1){
            $errors[] = "update error: ". $user;
        }
        if(count($errors) == 0) {
            $successes[] = "update successful";
            header("Location: display.php"); die();
        }
    }

}

echo "
<div id='wrapper'>

<div id='content'>

<h2>Update</h2>

<div id='left-nav'>";
include("left-nav.php");
echo "
</div>

<div id='main'>";

echo "<pre>";
print_r($errors);
print_r($successes);
echo "</pre>";

echo "
<div id='regbox'>
<form name='updateUser' action='".$_SERVER['PHP_SELF']."' method='post'>

<p>
<label>Email:</label>
<input type='text' name='username'";
if(isUserLoggedIn() && UserRoles::ADMIN == $loggedInUser->role->value) {
    echo "readonly=\"readonly\"";
}
echo "value='".$foundUser['UserName']."'/>
</p>
<p>
<label>First Name:</label>
<input type='text' name='firstname' value='".$foundUser['FirstName']."'/>
</p>
<p>
<label>Last Name:</label>
<input type='text' name='lastname' value='".$foundUser['LastName']."'/>
</p>
<p>
<label>Contact No:</label>
<input type='text' name='contactNo' value='".$foundUser['contact_no']."'/>
</p>
<p>
<input type='hidden' name='userId' value='".$foundUser['user_id']."'/>
</p>";

// Only ADMIN can update user roles
if(isUserLoggedIn() && UserRoles::ADMIN == $loggedInUser->role->value) {
    echo "<p>
	<label>Role:</label>
	<input type=\"radio\" name=\"role\" value=\"1\"";
    if ($foundUser['Role'] == UserRoles::ADMIN) {
        echo "checked";
    }
    echo "> ADMIN
	<input type=\"radio\" name=\"role\" value=\"2\"";
    if ($foundUser['Role'] == UserRoles::USER) {
        echo "checked";
    }
    echo "> USER
	</p>";

    echo "<p>
	<label>Status:</label>
	<input type=\"radio\" name=\"status\" value=\"A\"";
    if ($foundUser['status'] == 'A') {
        echo "checked";
    }
    echo "> Active
	<input type=\"radio\" name=\"status\" value=\"I\"";
    if ($foundUser['status'] == 'I') {
        echo "checked";
    }
    echo "> Inactive
	<input type=\"radio\" name=\"status\" value=\"N\"";
    if ($foundUser['status'] == 'N') {
        echo "checked";
    }
    echo "> New
	</p>";
}

echo "<label>&nbsp;<br>
<input type='submit' name= 'Update' value='Update'/>&nbsp;&nbsp;";
echo "</p>

</form>
</div>

</div>
<div id='bottom'></div>
</div>
</body>
</html>";
?>
