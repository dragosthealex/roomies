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

// The local root of the Roomies git repository:
define('__ROOT__', dirname(dirname(__FILE__)));
include __ROOT__.'/inc/classes/user.php';
// Setting session name
$session_name = 'some_name';

// Setting runtime ini cookie params (lifetime, path, domain, ssl or not, httponly or not)
$cookieParams = session_get_cookie_params();
session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'],
                          $cookieParams['domain'], false, true);

// Starting session with a regenerated id
session_name($session_name);
session_start();
session_regenerate_id();

// Define the LOGGED_IN status of the user. True if logged in, false else
define("LOGGED_IN", isset($_SESSION['user']));

// Define whether or not the user has just logged in, for later use.
define("JUST_LOGGED_IN", isset($_SESSION['justLoggedIn']));
if (JUST_LOGGED_IN)
{
  // Don't display next time
  unset($_SESSION['justLoggedIn']);
} // if

// The variable that determines if current user is admin. for now, true by default
define("ADMIN", TRUE);

// If REQUIRE_SESSION is not set, we don't care about the login status
// Cannot use isset() on constants; use defined() and is_bool():
if (defined('REQUIRE_SESSION') && is_bool(REQUIRE_SESSION))
{
    if (REQUIRE_SESSION && !LOGGED_IN)
    {
        header('HTTP/1.1 403 Forbidden', true, 403);
        include __ROOT__.'/inc/html/forbidden.php'; // This file should exit()
    }
    elseif (!REQUIRE_SESSION && LOGGED_IN)
    {
        header('HTTP/1.1 404 Not Found', true, 404);
        include __ROOT__.'/inc/html/notfound.php'; // This file should exit()
    }
}
// Initiate $ioStatus. If user is logged in, 'in', else 'out'.
// It is used for accessing files depending on the login status, e.g.
// header.in.php vs header.out.php. Only used when the user is not required
// to be specifically logged in or out.
$ioStatus = (LOGGED_IN ? "in" : "out");


// Inclusion of the db config file
require_once __ROOT__.'/config.inc.php';

// Connection to the db. Catch any error.
// $con is the connection handler, PDO object.
try
{
  $con = new PDO("mysql:host=$database_host;dbname=$group_dbnames[0]", $database_user, $database_pass);
} catch (PDOException $e)
{
  // Exit the script if the database conneciton fails.
  exit('Connection failed: ' . $e->getMessage());
}
if(LOGGED_IN)
{
  $user = new User($con, $_SESSION['user']['id']);
}
// If called from the root directory, set $webRoot to "", otherwise to "../"
$webRoot = isset($rootDirectory) ? "." : "..";
?>
