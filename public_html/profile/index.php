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
$loggedIn = $_GET['loggedIn'];
$ownProfile = $_GET['profile'];
if (loggedIn == 0) {
  echo "Logged Out";
} else {
  echo "Logged In";
}

if (profile == 0) {
  echo "Other Profile";
} else {
  echo "My Profile";
  </body>
</html>
