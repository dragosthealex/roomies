<?php
/*
Contains the functions used by friends system
*/
require_once '../../inc/init.php';
require_once __ROOT__.'/inc/classes/user.php';

if(isset($_POST['action']))
{
  $action = htmlentities($_POST['action']);
  switch ($action)
  {
    // Add friends
    case 1:
      if(isset($_POST['userId'], $_POST['otherUserId']))
      {
        $user = new User($con, $_POST['userId']);
        $otherUser = new User($con, $_POST['otherUserId']);
        $user->addFriend($otherUser);
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
      break;
    
    default:
      break;
  }
}
else
{
  require_once __ROOT__.'/inc/html/notfound.php';
  exit();
}
?>