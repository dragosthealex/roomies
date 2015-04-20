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

function __autoload($class) {
  include_once __ROOT__.'/inc/classes/'.$class.'.php';
}

include_once __ROOT__.'/inc/classes/user.php';
include_once __ROOT__.'/inc/classes/CurrentUser.php';
include_once __ROOT__.'/inc/classes/OtherUser.php';
include_once __ROOT__.'/inc/classes/Owner.php';
// Setting session name
$session_name = 'some_name';

// Set Facebook session
require_once __ROOT__."/inc/lib/fb-php-sdk/autoload.php";
use Facebook\FacebookSession;
FacebookSession::setDefaultApplication('327250234130442', 'ef83e698805b61b311f44d0735728781');

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
define("OWNER_LOGGED_IN", isset($_SESSION['owner']));

// Define whether or not the user has just logged in, for later use.
define("JUST_LOGGED_IN", (isset($_SESSION['justLoggedIn']) && $_SESSION['tempUser']));
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

// If $_SESSION['tempOwner'] is set, send to owner-register
// If $_SESSION['tempUser'] is set, send to confirm
if(isset($_SESSION['tempOwner']) && (!in_array('register-owner', explode('/', $_SERVER['REQUEST_URI']))))
{
  header("Location: $webRoot/register-owner");
}/*
if(isset($_SESSION['tempUser']) && (!in_array('confirm', explode('/', $_SERVER['REQUEST_URI']))))
{
  header("Location: $webRoot/confirm");
}*/

// Inclusion of the db config file
require_once __ROOT__.'/config.inc.php';

// Connection to the db. Catch any error.
// $con is the connection handler, PDO object.
try
{
  $con = new PDO("mysql:host=$database_host;dbname=$database_name", $database_user, $database_pass);
} catch (PDOException $e)
{
  // Exit the script if the database conneciton fails.
  exit('Connection failed: ' . $e->getMessage());
}

// Fix timezones
date_default_timezone_set('Europe/London');
$now = new DateTime();
$mins = $now->getOffset() / 60;
$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;
$offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
$stmt = $con->prepare("SET time_zone = '$offset'");
$stmt->execute();

// If called from the root directory, set $webRoot to "", otherwise to "../"
$webRoot = isset($rootDirectory) ? "." : "..";

// TODO: MAKE REMEMBERME FOR OWNER

if(LOGGED_IN)
{
  if(isset($_GET['logout']))
  {
    $userId = $_SESSION['user']['id'];

    if(isset($_COOKIE['login']))
    {
      $currentCookie = $_COOKIE['login'];
      $stmt = $con->prepare("SELECT user_cookie FROM rusers WHERE user_id = $userId");
      $stmt->execute();
      $stmt->bindColumn(1, $dbCookies);
      $stmt->fetch();

      // Remove cookie from array
      $dbCookies = explode(':', $dbCookies);
      $dbCookies = array_diff($dbCookies, array("$currentCookie"));
      $dbCookies = implode(':', $dbCookies);
      // Put the new array back
      $stmt = $con->prepare("UPDATE rusers SET user_cookie = '$dbCookies' WHERE user_id = '$userId'");
      $stmt->execute();

      // Delete cookie from user
      setcookie('login', '', time()-3600);
    }

    // TEMP. Reset last_online to some time ago (no longer online)
    $stmt = $con->prepare("UPDATE rusers SET last_online = '1970-01-01 00:00:00' WHERE user_id = '$userId'");
    $stmt->execute();

    session_destroy();
    header("Location: $webRoot");
    exit();
  }
  $user = new User($con, $_SESSION['user']['id']);
  // for implementing step by step
  
  $user2 = new CurrentUser($con);
  if($user2->getError())
  {
    echo $user2->getError();
    exit();
  }

  if (!isset($justLongPolling))
  {
    // Le user is online
    $userId = $user2->getCredential('id');
    $now = date('Y-m-d H:i:s');
    $stmt = $con->prepare("UPDATE rusers SET last_online = '$now' WHERE user_id = '$userId'");
    $stmt->execute();
  }
} else if(OWNER_LOGGED_IN)
{
  if(isset($_GET['logout']))
  {
    session_destroy();
    header("Location: $webRoot");
    exit();
  }
  $owner = new Owner($con, 'get', array('id'=>$_SESSION['owner']['id']));
  $ioStatus = "owner";
}
else
{
  include_once __ROOT__."/inc/html/cookie_login.php";
}
?>