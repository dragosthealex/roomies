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

$headers = getallheaders();
if (!$headers || !isset($headers['Roomies']) || $headers['Roomies'] !== 'cactus')
{
  echo 'lol';
  require_once __ROOT__.'/inc/html/notfound.php';
  // Exits
}

require_once __ROOT__.'/inc/classes/user.php';

if(isset($_GET['a']))
{
  $action = htmlentities($_GET['a']);

  if(isset($_GET['id']))
  {
    $otherUser = new User($con, $_GET['id']);
    $user->addFriend($otherUser, $action);
    $status = $user->friendshipStatus($otherUser);

    if(!$status && $action)
    {
      echo "Error. Operation failed.";
    }
  }
  else
  {
    echo "Error. User ids not passed.";
  }
}
else
{
  require_once __ROOT__.'/inc/html/notfound.php';
  exit();
}
?>