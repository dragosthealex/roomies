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
                          ORDER BY message_id DESC
                          LIMIT 1");
  $stmt->execute();
  if(!$stmt->rowCount())
  {
    $conv = '<ul class="ul conversation" id="main_conversation" data-conv-id="$otherUserId"><li class="ph ph-last ph-message" data-placeholder="No messages."></li></ul>';
  }
  else
  {
    $stmt->bindColumn(1, $id1);
    $stmt->bindColumn(2, $id2);
    $stmt->fetch();

    $otherUserId = $userId == $id1 ? $id2 : $id1;
    $otherUser = new User($con, $otherUserId);
    $otherName = $otherUser->getName();

    // Redirect to the page with the latest messages
    header("Location: $webRoot/messages/".$otherUser->getIdentifier('username'));
    exit();

    $conversation = new Conversation ($con, $userId, $otherUserId);
    $conv = $conversation->toString();
    $title = "$otherName - Messages";
  }
}
else
{
  $otherUserId = htmlentities($_GET['conv']);
  $otherUser = new User($con, $otherUserId);
  $otherUserId = $otherUser->getIdentifier('id');
  $otherName = $otherUser->getName();
  $conversation = new Conversation ($con, $userId, $otherUserId);
  $conv = $conversation->toString();
  $title = "$otherName - Messages";
  // Read all messages in this conv
  $conversation = new Conversation($con, $user->getIdentifier('id'), $otherUserId);
  $conversation->readMessages();
}

// Get 10 conversations from the user, starting from offset, ordered desc by time
$offset = 0;
if(isset($_GET['offset']))
{
  $offset = htmlentities($_GET['offset']);
}
$allConversations = $user->getAllConversations($offset);

?>