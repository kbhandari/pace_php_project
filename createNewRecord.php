<?php
/**
 * Created by PhpStorm.
 * User: kbhandari
 */
?>


<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title>Mail Manager</title>
  <!-- Style -- Can also be included as a file usually style.css -->
  <style type="text/css">
  table.table-style-three {
  font-family: verdana, arial, sans-serif;
      font-size: 11px;
      color: #333333;
      border-width: 1px;
      border-color: #3A3A3A;
      border-collapse: collapse;
    }
    table.table-style-three th {
  border-width: 1px;
      padding: 8px;
      border-style: solid;
      border-color: #FFA6A6;
      background-color: #D56A6A;
      color: #ffffff;
    }
    table.table-style-three a {
  color: #ffffff;
  text-decoration: none;
    }

    table.table-style-three tr:hover td {
  cursor: pointer;
}
    table.table-style-three tr:nth-child(even) td{
  background-color: #F7CFCF;
    }
    table.table-style-three td {
  border-width: 1px;
      padding: 8px;
      border-style: solid;
      border-color: #FFA6A6;
      background-color: #ffffff;
    }
</style>

</head>
<body>

  <?php require_once("config.php"); ?>

  <form name="createNewRecord" action="createNewRecord_DBINSERT.php" method="post">
  <!-- Table goes in the document BODY -->
  <table class="table-style-three">
      <div id="errMsg">
          <?php if(!empty($_SESSION['errMsg'])) { echo $_SESSION['errMsg']; } ?>
      </div>
      <?php unset($_SESSION['errMsg']); ?>
      <thead>
      <!-- Display CRUD options in TH format -->
      <tr>
          <th>Email</th>
          <td><input type="text" name="emailaddress" value=""><i>@pace.edu</i></td>
      </tr>
      <tr>
          <th>Password</th>
          <td><input type="password" name="password" value=""></td>
      </tr>
      <tr>
          <th>Confirm Password</th>
          <td><input type="password" name="confirmPassword" value=""></td>
      </tr>
      <tr>
        <th>First Name</th>
        <td><input type="text" name="firstname" value=""></td>
      </tr>
      <tr>
          <th>Middle Name</th>
          <td><input type="text" name="middlename" value=""></td>
      </tr>
      <tr>
        <th>Last Name</th>
        <td><input type="text" name="lastname" value=""></td>
      </tr>
      <tr>
          <th>Contact No</th>
          <td><input type="text" name="contactNo" value=""></td>
      </tr>
      <tr>
        <td><input type="Submit" name="submit" value="Register"></td>
      </tr>
      </thead>
    </table>
  </form>
</body>
</html>




