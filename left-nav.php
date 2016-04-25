<?php

require_once("config.php");

//Links for logged in user
if(isUserLoggedIn()) {
	echo "
	<ul>
	<li><a href='myaccount.php'>Account Home</a></li>
	<li><a href='post.php'>Post</a></li>
	<li><a href='logout.php'>Logout</a></li>";

	global $loggedInUser;
	if (UserRoles::ADMIN == $loggedInUser->role->value) {
		echo "<li><a href='display.php'>Update User</a></li>";
		echo "<li><a href='display.php'>Suspend Post</a></li>";
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
	<li><a href='post.php'>Post</a></li>
	</ul>";
}

?>