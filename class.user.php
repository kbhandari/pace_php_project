<?php

  class loggedInUser {
	  public $email = NULL;
	  public $hash_pw = NULL;
	  public $user_id = NULL;
	  public $role = NULL;


	//Logout
	public function userLogOut()
	{
	  destroySession("ThisUser");
	}
  }

?>