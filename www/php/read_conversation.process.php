<?php
/**
 * File for marking a conversation as read.
 * Requires:
 * $_POST['convId'] - the id of the conversation to mark as read
 * Outputs:
 * (JSON Object) (Array) A list of the message ids marked as read
 */
include '../../inc/init.php';

if (!isset($_POST['convId'], $_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include __ROOT__.'/inc/html/notfound.php';
}

session_write_close();

header("Content-type: application/json");

try
{
  $userId = $user->getIdentifier('id');
  $otherUserId = htmlentities($_POST['convId']);

  $unreadReceived = "message_user_id1 = '$otherUserId' AND message_user_id2 = $userId AND messages_read = 0";

  // Get the previously unread message ids
  $previouslyUnreadMessageIds = array();
  $stmt = $con->prepare("SELECT message_id FROM rmessages WHERE $unreadReceived");
  $stmt->execute();
  while ($message = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    array_push($previouslyUnreadMessageIds, $message['message_id']);
  }

  // Read the messages
  $stmt = $con->prepare("UPDATE rmessages SET messages_read = 1 WHERE $unreadReceived");
  $stmt->execute();

  // Return the array of messages as a json object
  echo json_encode($previouslyUnreadMessageIds);
}
catch (Exception $exception)
{
  echo json_encode(array('error' => $exception->getMessage()));
}
?>