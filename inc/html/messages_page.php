<?php
/*
This creates new conversation and returns it as string
*/
require_once '../../inc/init.php';
require_once __ROOT__."/inc/classes/conversation.php";
// Initialise the values
$otherName = "Conversation";

// Localise this user id
$userId = $user->getIdentifier('id');

if(!isset($_GET['conv']))
{
  // Get the latest conversation
  $stmt = $con->prepare("SELECT message_user_id1, message_user_id2 FROM rmessages
                          WHERE message_user_id1 = $userId OR message_user_id2 = $userId
                          ORDER BY message_timestamp DESC
                          LIMIT 1");
  $stmt->execute();
  if(!$stmt->rowCount())
  {
    $conv = "No messages yet. Nobody loves you. Seriously";
  }
  else
  {
    $stmt->bindColumn(1, $id1);
    $stmt->bindColumn(2, $id2);
    $stmt->fetch();

    $conversation = new Conversation ($con, $id1, $id2);
    $conv = $conversation->toString();
    $otherUserId = $id2;
  }
}
else
{
  $otherUserId = htmlentities($_GET['conv']);
  $otherUser = new User($con, $otherUserId);
  $otherName = $otherUser->getName();
  $conversation = new Conversation ($con, $userId, $otherUserId);
  $conv = $conversation->toString();
}

// Get 10 conversations from the user, starting from offset, ordered desc by time
$offset = 0;
if(isset($_GET['offset']))
{
  $offset = htmlentities($_GET['offset']);
}
$allConversations = $user->getAllConversations($offset);

?>