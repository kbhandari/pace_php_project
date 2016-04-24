<?php

require_once("config.php");


//Prevent the logged-in user visiting the register page if he/she is not an ADMIN
if(isUserLoggedIn() && UserRoles::ADMIN != $loggedInUser->role->value) { header("Location: myaccount.php"); die(); }

print_r($_POST);

//Forms posted
if(!empty($_POST))
{
	$email = $_POST['emailaddress'] . "@pace.edu";
	$password = $_POST['password'];
	$confirmPassword = $_POST['confirmPassword'];
	$fname = $_POST['firstname'];
	$mname = $_POST['middlename'];
	$lname = $_POST['lastname'];
	$contactno = $_POST['contactNo'];
	$role = new UserRoles(UserRoles::USER);

	if($email == "") {
		$errors[] = "enter valid email";
	}
	if($fname == "") {
		$errors[] = "enter valid first name";
	}
	if($lname == "") {
		$errors[] = "enter valid last name";
	}
	if($password =="" && $confirmPassword =="") {
		$errors[] = "enter password";
	} else if($password != $confirmPassword) {
		$errors[] = "password do not match";
	}

	//End data validation
	if(count($errors) == 0) {
		//Creating a variable to hold the "@return boolean value returned by function createNewUser - is boolean 1 with
		//successfull and 0 when there is an error with executing the query .

		$newuser = createNewUser($email, $password, $fname, $mname, $lname, $contactno, $role);

		if ($newuser == 1) {
			sendRegistrationEmail($email);
			$_SESSION['username'] = $email;
			//header("Location: mail_options.php");
			//header("Location: register.php");
			echo "<h1>User created successfully:</h1>";
			echo "<pre>";
			print_r($_POST);
			echo "</pre>";
		} else {
			$errors[] = "Error while creating the user: " . $newuser;
			#$_SESSION['errMsg'] = "Error while creating the user: " . $newuser;
			#header("Location: register.php");
		}
	}
	if(count($errors) == 0) {
		$successes[] = "registration successful";
	}
}

require_once("header.php");
echo "
<body>
<div id='wrapper'>

<div id='content'>

<h2>Register</h2>

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
<form name='newUser' action='".$_SERVER['PHP_SELF']."' method='post'>

<p>
<label>Email:</label>
<input type='text' name='emailaddress' />
</p>
<p>
<label>Password:</label>
<input type='password' name='password' />
</p>
<p>
<label>Confirm Password:</label>
<input type='password' name='confirmPassword' />
</p>
<p>
<label>First Name:</label>
<input type='text' name='firstname' />
</p>
<p>
<label>Last Name:</label>
<input type='text' name='lastname' />
</p>
<p>
<label>Contact No:</label>
<input type='text' name='contactNo' />
</p>";

echo "<label>&nbsp;<br>
<input type='submit' value='Register'/>
</p>

</form>
</div>

</div>
<div id='bottom'></div>
</div>
</body>
</html>";
?>
