<?php
/*
Process a message and inserts it into db
*/
require_once '../../inc/init.php';
require_once __ROOT__.'/inc/classes/message.php';
require_once __ROOT__.'/inc/classes/conversation.php';

if(!isset($_POST['message']) || !isset($_GET['receiver']) || !isset($_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

header('Content-type: application/json');

$response = array();
$response['error'] = '';

// Get stuff
$id1 = $user->getIdentifier('id');
$id2 = htmlentities($_GET['receiver']);
$messageText = htmlentities(trim($_POST['message']));
$groupId = htmlentities($_GET['gid']);

$user = new CurrentUser($con);
$response['error'] .= ($user->getError())?"<br>".$user->getError():'';

if($groupId && $user->inGroup($groupId))
{
  $id2 = 0;
}
else
{
  // Check if users are friends
  /*
  if($user->friendshipStatus($otherUser) == 1)
  {
    $response['error'] .= "not friends. sorry. loner"
  }
  */
  $otherUser = new OtherUser($con, $id2);
  $response['error'] .= $otherUser->getError();
  $groupId = 0;
}

// If empty message, don't do anything
if ($messageText != '')
{
  // Read all messages in this conv
  $conversation = new Conversation($con, $id1, $id2, $groupId);
  $conversation->readMessages();

  // Insert message in DB
  $message = new Message($con, 'text', array($id1, $id2, $messageText, $groupId));
  $response['error'] .= $message->getError();
}

echo json_encode($response);
?>