<?php
/*
Contains the functions used by friends system
Action can have multiple values:

0 -> remove friend, cancel request
1 -> add friend
3 -> accept request
4 -> block user
5 -> unblock user
...
*/

require_once '../../inc/init.php';

if (!isset($_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] !== 'cactus')
{
  echo 'lol';
  require_once __ROOT__.'/inc/html/notfound.php';
  // Exits
}

require_once __ROOT__.'/inc/classes/user.php';

header('Content-type: application/json');

$response = array();

// No action supplied.
if(!isset($_GET['a']))
{
  $response['error'] = 'Action not supplied.';
}

// No other id supplied.
else if (!isset($_GET['id']))
{
  $response['error'] = 'User id not supplied.';
}

// Action and id both supplied
else
{
  // Apply the action to the user of the other id
  $action = htmlentities($_GET['a']);
  $otherUser = new User($con, $_GET['id']);
  $user->addFriend($otherUser, $action);

  // Check if it worked
  if(!$user->friendshipStatus($otherUser) && $action)
  {
    $response['error'] = 'Operation failed.';
  }
}

echo json_encode($response);
?>