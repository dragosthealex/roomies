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

// if (!isset($_GET['unread'], $_GET['friendRequests'], $_GET['lastMessageId'], $_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus')
// {
//   include __ROOT__.'/inc/html/notfound.php';
// }

header("Content-type: application/json");

try
{
  $unreadIds = explode(',', htmlentities($_GET['unread']));
  $friendRequestIds = explode(',', htmlentities($_GET['friendRequests']));
  $lastMessageId = htmlentities($_GET['lastMessageId']);

  $userId = $user->getIdentifier('id');

  // First query: Find new messages for this user
  $query = "SELECT * FROM rmessages
                    WHERE (message_user_id1 = $userId OR message_user_id2 = $userId)
                      AND message_id > '$lastMessageId';";

  if (count($unreadIds))
  {
    // Second query: Find where messages are now read
    $query .= "SELECT message_id FROM rmessages
                                WHERE messages_read = 1
                                  AND (message_user_id1 = $userId OR message_user_id2 = $userId)
                                  AND (message_id = '".implode("' OR message_id = '", $unreadIds)."');";
  }

  // Third query: Find new friend requests
  $query .= "SELECT conexion_user_id1
               FROM rconexions
              WHERE conexion_user_id2 = $userId
                AND conexion_status = 2
                AND (conexion_user_id1 != '".implode("' OR conexion_user_id1 != '", $friendRequestIds)."');";

  // Fourth query: Find deprecated friend requests
  $query .= "SELECT conexion_user_id1, conexion_user_id2
               FROM rconexions
              WHERE conexion_user_id2 = $userId
                AND conexion_status != 2
                AND (conexion_user_id1 = '".implode("' OR conexion_user_id1 = '", $friendRequestIds)."');";

  $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
  $query = str_replace('\n', '', $query);

  $stmt = $con->prepare($query);
  if (!$stmt->execute())
  {
    throw new Exception('Query error!', 1);
  }

  for ($timespent = 0; $timespent < 50 && !$stmt->rowCount(); $timespent++)
  {
    sleep(1);
    $stmt->execute();
  }

  $result = array(
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
    'query' => $query
  );

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    // Old requests
    if (isset($row['conexion_user_id2']))
    {
      array_push($oldRequests, $row['conexion_user_id1']);
    }

    // New requests
    else if (isset($row['conexion_user_id1']))
    {
      array_push($result['newRequests']['content'], $row['conexion_user_id1']);
    }

    // New messages
    else if (isset($row['message_user_id1']))
    {
      array_push($result['newMessages']['content'], $row);
    }

    // Read message
    else
    {
      array_push($result['readMessage'], $row['message_id']);
    }
  }

  echo json_encode($result);
} catch (Exception $exception)
{
  echo json_encode(array('error' => $exception));
}
?>