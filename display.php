<?php



include_once("config.php");

//Prevent the logged-in user visiting this page if he/she is not an ADMIN or EDITOR
if(isUserLoggedIn()) {
    if (UserRoles::ADMIN == $loggedInUser->role->value) {
        // call to function fetchAllUsers() from functions.php
        $allusers = fetchAllUsers();
    } else {
        header("Location: myaccount.php"); die();
    }
} else {
    header("Location: index.php"); die();
}

?>

<div id='left-nav'>";

<?php include("left-nav.php"); ?>

</div>

<pre><?php //print_r($allusers); ?></pre>

<html>
  <head>
    <title>Display All Users</title>
  </head>

  <body>
    <table>
      <tr><td>UserName</td>
      <td>FirstName</td>
      <td>LastName</td>
          <?php if(isUserLoggedIn() && UserRoles::ADMIN == $loggedInUser->role->value) { ?>
          <td>Role</td>
          <?php } ?>
        <?php //NOTICE THE USE OF PHP IN BETWEEN HTML
            foreach($allusers as $userdetails){
              ?>
    
              <tr>
            <td><a href="updateThisUser.php?userName=<?php print $userdetails['UserName']; ?>"></a></td>
            <td><?php print $userdetails['FirstName']; ?></td>
            <td><?php print $userdetails['LastName']; ?></td>
            <?php if(isUserLoggedIn() && UserRoles::ADMIN == $loggedInUser->role->value) { ?>
                <td><?php $role = new UserRoles($userdetails['Role']); print $role->getName(); ?></td>
            <?php } ?>
          </tr>
    
              <?php } ?>

    </table>
  </body>
</html>
