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

<!--Start building page from here-->
<?php
if ($_GET[]loggedIn == 0 && $profile == 0) {
  
} elseif ($loggedIn == 1 && $profile == 0) {
  
} else {

  </body>
</html>
