<?php
/*
You are going to make the html for the profile of the current 
logged in user.
The header would already be included.
Please read the gantt chart note for this task (task 10)

<!DOCTYPE>, <html> and <body> already started
*/

// Initialise the session (do not modify this)
define("REQUIRE_SESSION", true);
include '/../inc/init.php';
?>

<!--The header bar-->
<?php include '/../inc/html/header.in.php';?>
<html>
  <body>
  <!--Start building page from here-->
  <p> Hello World </p>
  <?php
  echo "<p>Hello World</p>";
  if ($_GET[LOGGEDIN] == 0) {
  ?>
  <?php  
  } elseif ($_GET[LOGGEDIN] == 1 && $_GET[PROFILE] == 0) {
  ?>
  <?php  
  } else {
  ?>
  </body>
</html>
