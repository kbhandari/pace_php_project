<?php

require_once("config.php");


//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: myaccount.php"); die(); }

//Forms posted
if(!empty($_POST))
{
	$errors = array();
	$username = trim($_POST["username"]) . "@pace.edu";
	$password = trim($_POST["password"]);
	
	//Perform some validation

	if($username == "")
	{
		$errors[] = "enter username";
	}
	if($password == "")
	{
		$errors[] = "enter password";
	}

	if(count($errors) == 0)
	{
			//retrieve the records of the user who is trying to login
			$userdetails = fetchUserDetails($username);

			if (empty($userdetails)) {
				$errors[] = "invalid user";
			}
			//See if the user's account is activated
			else if($userdetails["status"] == 'N') {
				$errors[] = "email verification pending";
			} else if($userdetails["status"] == 'I') {
				$errors[] = "account inactive";
			}
			else
			{
				//Hash the password and use the salt from the database to compare the password.
				$entered_pass = generateHash($password,$userdetails["Password"]);
			  	echo $entered_pass . "<br><br>";
			  	echo $userdetails['Password'];

				
				if($entered_pass != $userdetails["Password"]) {
					$errors[] = "invalid password";
  				}
				else {
					//Passwords match! we're good to go'
					//Transfer some db data to the session object
				  	$loggedInUser = new loggedInUser();
					$loggedInUser->email = $userdetails["email"];
					$loggedInUser->user_id = $userdetails["User_ID"];
					$loggedInUser->hash_pw = $userdetails["Password"];
					$loggedInUser->first_name = $userdetails["First_Name"];
				    $loggedInUser->last_name = $userdetails["Last_Name"];
					$loggedInUser->username = $userdetails["email"];
					$loggedInUser->contact_no = $userdetails["contact_no"];
				  	$loggedInUser->member_since = $userdetails["created_on"];
					$loggedInUser->role = new UserRoles($userdetails["Role"]);

					//pass the values of $loggedInUser into the session -
				  	// you can directly pass the values into the array as well.

					$_SESSION["ThisUser"] = $loggedInUser;

					echo print_r($loggedInUser);

				  	//now that a session for this user is created
					//Redirect to this users account page
					header("Location: myaccount.php");
					die();
				}
			}

	}
}

require_once("header.php");

echo "
<body>
<div id='wrapper'>

<div id='content'>

<h2>Login</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";

echo "<pre>";
print_r($errors);
echo "</pre>";

echo "
<div id='regbox'>
<form name='login' action='".$_SERVER['PHP_SELF']."' method='post'>
<p>
<label>Email:</label>
<input type='text' name='username' /><i>@pace.edu</i>
</p>
<p>
<label>Password:</label>
<input type='password' name='password' />
</p>
<p>
<label>&nbsp;</label>
<input type='submit' value='Login' class='submit' />
</p>
</form>
</div>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
