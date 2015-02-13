<?php
require_once '../../inc/init.php';
require_once '../../inc/classes/conversation.php';
$headers = getallheaders();

if(!isset($_GET['otherId']) || ! isset($headers['Roomies']) || $headers['Roomies'] != 'cactus')
{
  include_once __ROOT__."/inc/html/notfound.php";
  exit();
}
else
{
  $otherUserId = htmlentities($_GET['otherId']);
  $otherUser = new User($con, $otherUserId);
  $otherName = $otherUser->getName();
  $conversation = new Conversation($con, $user->getIdentifier('id'), $otherUserId);
  $conv = $conversation->getAsJSON();

  $offset = 0;
  if(isset($_GET['offset']))
  {
    $offset = htmlentities($_GET['offset']);
  }
  $allConversations = $user->getAllConversationsJSON($offset);

  echo "[$conv, $allConversations]";
}
?>