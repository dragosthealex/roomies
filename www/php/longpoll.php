<?php
/**
 * File for longpolling.
 * Requires:
 * $_GET['unread'] - a list of ids of other users who haven't read messages
 *                   sent by the current user
 * $_GET['timestamp'] - The datetime (Y-m-d H:i:s) of the latest message sent to the user
 */

include '../../inc/init.php';
include __ROOT__.'/inc/classes/conversation.php';

if (!isset($_GET['unread'], $_GET['friendRequests'], $_GET['lastMessageId'], $_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include __ROOT__.'/inc/html/notfound.php';
}

session_write_close();

header("Content-type: application/json");

try
{
  $unreadIds = explode(',', htmlentities($_GET['unread']));
  $friendRequestIds = explode(',', htmlentities($_GET['friendRequests']));
  $lastMessageId = htmlentities($_GET['lastMessageId']);

  $userId = $user->getIdentifier('id');
  $userName = $user->getName();

  $stmts = array(
    // First query: Find new messages for this user
    $con->prepare("SELECT * FROM rmessages
                           WHERE (message_user_id1 = $userId OR message_user_id2 = $userId)
                             AND message_id > '$lastMessageId'"),

    // Second query: Find where messages are now read
    $con->prepare("SELECT message_id FROM rmessages
                    WHERE messages_read = 1
                      AND (message_user_id1 = $userId)
                      AND (message_id = '".implode("' OR message_id = '", $unreadIds)."')"),

    // Third query: Find new friend requests
    $con->prepare("SELECT conexion_user_id1
                     FROM rconexions
                    WHERE conexion_user_id2 = $userId
                      AND conexion_status = 2
                      AND (conexion_user_id1 != '".implode("' OR conexion_user_id1 != '", $friendRequestIds)."')"),

    // Fourth query: Find deprecated friend requests
    $con->prepare("SELECT conexion_user_id1
                     FROM rconexions
                    WHERE conexion_user_id2 = $userId
                      AND conexion_status != 2
                      AND (conexion_user_id1 = '".implode("' OR conexion_user_id1 = '", $friendRequestIds)."')")
  );

  function execute(&$stmts)
  {
    foreach ($stmts as $stmt) {
      if (!$stmt->execute())
      {
        return false;
      }
    }
    return true;
  }

  function rowCount(&$stmts)
  {
    $rowCount = 0;
    foreach ($stmts as $stmt) {
      $rowCount += $stmt->rowCount();
    }
    return $rowCount;
  }

  if (!execute($stmts))
  {
    throw new Exception('Query error!', 1);
  }

  for ($i = 0; $i < 50; $i++)
  {
    if (rowCount($stmts))
    {
      break;
    }
    sleep(1);
    execute($stmts);
  }

  execute($stmts);

  $response = array(
    'newRequests' => array(
      'template' => array(),
      'content'  => array()
    ),
    'oldRequests' => array(),
    'newMessages' => array(
      'template' => Conversation::$template,
      'content'  => array()
    ),
    'readMessage' => array(),
    'stmts' => $stmts
  );


  // New messages
  while ($message = $stmts[0]->fetch(PDO::FETCH_ASSOC))
  {
    // Replace '\n' with '<br>'
    $message['message_text'] = nl2br($message['message_text']);
    $read = ($message['messages_read'])?'read':'unread';

    // Get the name and whether it was sent or received
    if ($message['message_user_id1'] == $userId)
    {
      $id = $message['message_user_id1'];
      $name = $userName;
      $sentOrReceived = 'sent';
    }
    else
    {
      $id = $message['message_user_id2'];
      $otherUser = new User($con, $message['message_user_id2']);
      $name = $otherUser->getName();
      $sentOrReceived = 'received';
    }

    array_push(
      $response['newMessages']['content'], 
      array(
        $read.' '.$sentOrReceived,
        $message['message_id'],
        $message['message_timestamp'],
        $id.'.jpg',
        $name,
        $message['message_text'],
        $message['message_user_id1'],
        $message['message_user_id2']
      )
    );
  }

  // Read message
  while ($row = $stmts[1]->fetch(PDO::FETCH_ASSOC))
  {
    array_push($response['readMessage'], $row['message_id']);
  }

  // New requests
  while ($row = $stmts[2]->fetch(PDO::FETCH_ASSOC))
  {
    array_push($response['newRequests']['content'], $row['conexion_user_id1']);
  }

  // Old requests
  while ($row = $stmts[3]->fetch(PDO::FETCH_ASSOC))
  {
    array_push($response['oldRequests'], $row['conexion_user_id1']);
  }

  echo json_encode($response);
} catch (Exception $exception)
{
  echo json_encode(array('error' => $exception->getMessage()));
}
?>