<?php
/*
This creates new conversation and returns it as string
*/
require_once '../../inc/init.php';
require_once __ROOT__."/inc/classes/conversation.php";
require_once __ROOT__."/inc/classes/Group.php";
// Initialise the values
$otherName = "Conversation";
$errorMsg = "";
$groupId = 0;
// Localise this user id
$userId = $user2->getCredential('id');

if(!isset($_GET['conv']))
{

  // Construct condition for checking every group the user is in
  $groupCondition = "message_group='" . implode("' OR message_group='", $myGroups). "'";

  // Get the latest conversation
  $stmt = $con->prepare("SELECT message_user_id1, message_user_id2, message_group FROM rmessages
                          WHERE message_user_id1 = $userId OR message_user_id2 = $userId OR $groupCondition
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
    $stmt->bindColumn(3, $groupId);
    $stmt->fetch();

    // If the group id is 0, it means it's a normal conv, between two users
    // Else, it's a group conversation
    if(!$groupId)
    {
      $otherUserId = $userId == $id1 ? $id2 : $id1;
      $otherUser = new OtherUser($con, $otherUserId);
      $otherName = $otherUser->getName();
      $convToRedirect = $otherUser->getCredential('username');
    }
    else
    {
      $convToRedirect = "group-" . $groupId;
    }

    // Redirect to the page with the latest messages
    header("Location: $webRoot/messages/".$convToRedirect);
    exit();
  }
}
else
{

  if(!isset(explode('-',$_GET['conv'])[1]))
  {
    // It means we have a normal conv
    $otherUserId = htmlentities($_GET['conv']);
    $otherUser = new OtherUser($con, $otherUserId);
    $otherUserId = $otherUser->getCredential('id');
    $otherName = $otherUser->getName();
    $conversation = new Conversation ($con, $userId, $otherUserId);
    $conv = $conversation->toString();
    $title = "$otherName - Messages";
  }
  else
  {
    // It means we have a group conv
    $groupId = explode('-',htmlentities($_GET['conv']))[1];
    if(!$user2->inGroup($groupId))
    {
      header("Location: ./");
      exit();
    }
    $conversation = new Conversation($con, $userId, 0, 0, $groupId);
    $conv = $conversation->toString();

    if($conversation->getError())
    {
      $errorMsg = $conversation->getError();
      $conv = '';
    }

    $thisGroup = new Group($con, 'get', array('id' => "$groupId"));
    $title = $thisGroup->getDetail('name');
    $otherName = $title;
  }
}

// Get 10 conversations from the user, starting from offset, ordered desc by time
$offset = 0;
if(isset($_GET['offset']))
{
  $offset = htmlentities($_GET['offset']);
}
$allConversations = $user->getAllConversations($offset);

?>