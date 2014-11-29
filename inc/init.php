<?php
/*
Start the session. Define LOGGED_IN. Set $ioStatus. Set $user. Include config.
Connect to database. If connection fails, output error message. If
REQUIRE_SESSION is true, if not logged in, output 400 forbidden. Else, if
REQUIRE_SESSION is false and logged in, output 404 not found.
e.g.
if (REQUIRE_SESSION)
  if(!LOGGED_IN)
    //400
else if (!REQUIRE_SESSION && LOGGED_IN)
  //404
*/

// Setting session name
$session_name = 'some_name';

// Setting runtime ini cookie params (lifetime, path, domain, ssl or not, httponly or not)
$cookieParams = session_get_cookie_params();
session_set_cookie_params($cookieParams['lifetime'], cookieParams['path']
                          $cookieParams['domain'], false, true);

// Starting session with a regenerated id
session_name($session_name);
session_start();
session_regenerate_id();

// Define the LOGGED_IN status of the user. True if logged in, false else
define("LOGGED_IN", isset($_SESSION['user']));
/* 
Initiates $ioStatus. If user is logged in, 'in', else 'out'.
It is used for accessing files depending on the login status. e.g.
header.in.php vs header.out.php
*/
$ioStatus = (LOGGED_IN ? "in" : "out");

// If REQUIRE_SESSION is not set, we don't care about the login status
if(isset(REQUIRE_SESSION))
{
  if(REQUIRE_SESSION && !LOGGED_IN)
  {
    header('Location: /errors/400.php');
  }
  else if(!REQUIRE_SESSION && LOGGED_IN)
  {
    header('Location: /errors/404.php');
  }
}

// Inclusion of the db config file
require_once 'config.inc.php';

// Connection to the db. Catch any error.
// $con is the connection handler, PDO object.
try
{
  $con = new PDO("mysql:host=$database_host;dbname=$group_dbnames[0]", $database_user, $database_pass);
} catch (PDOException $e)
{
  echo 'Connection failed: ' . $e->getMessage();
}

?>