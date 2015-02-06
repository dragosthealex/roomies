<?php
/*
Contains the functions used by friends system
Action can have multiple values:

0 -> remove friend
1 -> add friend
2 -> cancel request
3 -> accept request
...
*/
require_once '../../inc/init.php';
require_once __ROOT__.'/inc/classes/user.php';

if(isset($_POST['action']))
{
  $action = htmlentities($_POST['action']);

  if(isset($_POST['userId'], $_POST['otherUserId']))
  {
    $user = new User($con, $_POST['userId']);
    $otherUser = new User($con, $_POST['otherUserId']);
    $user->addFriend($otherUser, $action);
    $status = $user->friendshipStatus($otherUser);

    if(!$status)
    {
      echo "Error. Friends adding failed.";
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