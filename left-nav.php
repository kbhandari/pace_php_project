<?php

require_once("config.php");

//Links for logged in user
if(isUserLoggedIn()) {
	echo "
	<ul>
	<li><a href='myaccount.php'>Account Home</a></li>
	<li><a href='logout.php'>Logout</a></li>";

	global $loggedInUser;
	if (UserRoles::ADMIN == $loggedInUser->role->value) {
		echo "<li><a href='display.php'>View, Edit or Delete Records</a></li>";
	} elseif (UserRoles::EDITOR == $loggedInUser->role->value) {
		echo "<li><a href='display.php'>View & Edit Records</a> </li>";
	}

	echo "</ul>";
}
//Links for users not logged in
else {
	echo "
	<ul>
	<li><a href='index.php'>Home</a></li>
	<li><a href='login.php'>Login</a></li>
	<li><a href='register.php'>Register</a></li>
	</ul>";
}

?>