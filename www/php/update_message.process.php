<?php
// ALMOST DEPRECATED
require_once '../../inc/init.php';
session_write_close();
require_once '../../inc/classes/conversation.php';

if(!isset($_GET['type'], $_GET['otherId'], $_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include_once __ROOT__."/inc/html/notfound.php";
  exit();
}
else
{
  // Code by Daniel. Just wanted proper long-polling in here, without causing merge errors. :P

  // SOME NOTES:
  // I (Daniel) plan to have a JavaScript function which detects inactivity, to prevent
  // unnecessary long-polling. If the user is inactive when these messages arrive,
  // then they should not be "read", until the user resumes activity. At that point,
  // I will use AJAX to call a separate function. Maybe read_messages.process.php.
  // That takes the message ids from _GET. (Meaning they should be output as data attr).

  // Get the other user from the id given
  $otherUserId = htmlentities($_GET['otherId']);
  $otherUser = new User($con, $otherUserId);
  $otherUserId = $otherUser->getIdentifier('id');
  $otherUserName = $otherUser->getName();

  // Get the current user id and name
  $userId = $user->getIdentifier('id');
  $userName = $user->getName();

  switch ($_GET['type']) {
    case 'new':
      // Long-polling:
      // Requires the last id:
      if (!isset($_GET['lastId']))
      {
        break;
      }

      $conversation = new Conversation(
        // con, this user, other user, offset
        $con, $userId, $otherUserId, 0,
        // longpoll?, last message id, max time
        true, htmlentities($_GET['lastId']), 60
      );
      break;
    case 'old':
      // User scrolled up:
      // Requires the offset:
      if (!isset($_GET['offset1']))
      {
        break;
      }

      $offset1 = htmlentities($_GET['offset1']);

      $conversation = new Conversation(
        // con, this user, other user, offset
        $con, $userId, $otherUserId, $offset1
      );
      break;
  } // switch

  // Get the conversation
  $conv = $conversation->getAsJSON();

  // Get the conversation list
  $allConversations = $user->getAllConversationsJSON(htmlentities($_GET['offset2']));

  // Output the final JSON object
  echo "[$conv, $allConversations]";

  // End code by Daniel (The rest is unchanged)

  // $offset = 0;
  // if(isset($_GET['offset']))
  // {
  //   $offset = htmlentities($_GET['offset']);
  // }

  // $otherUserId = htmlentities($_GET['otherId']);
  // $otherUser = new User($con, $otherUserId);
  // $otherName = $otherUser->getName();
  // $conversation = new Conversation ($con, $user->getIdentifier('id'), $otherUserId, $offset);
  // $conv = $conversation->getAsJSON();
  
  // $allConversations = $user->getAllConversationsJSON($offset);

  // echo "[$conv, $allConversations]";
}
?>
