<?php
// Processes the info received from the details form
require_once '../../inc/init.php';
$response = array();
$response['error'] = '';

// Check the type, and the logged in stuff
if(!isset($_GET['t']) || !LOGGED_IN || !($_GET['t'] == Accommodation::TYPE && OWNER_LOGGED_IN))
{
  include __ROOT__.'/inc/html/notfound.php';
  exit();
}

try
{
  switch ($_GET['t']) 
  {
    case CurrentUser::TYPE:
      if(!LOGGED_IN)
      {
        throw new Exception("You need to be logged in for this.", 1);
      }
      // Update the details. Keys are exactly the table column names
      $params = array();
      foreach ($_POST as $key => $value)
      {
        $params[$key] = htmlentities($value);
      }
      $user2->updateDetails($params);
      if($user2->getError())
      {
        throw new Exception("Error with updating details: " . $user2->getError, 1);
      }
      break;
    default:
      throw new Exception("Something wrong with type", 1);
      break;
  }
}





?>