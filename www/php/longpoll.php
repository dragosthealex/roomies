<?php
/**
 * File for longpolling.
 * Requires:
 * $_POST['unread'] - a list of ids of other users who haven't read messages
 *                    sent by the current user
 * $_POST['timestamp'] - The datetime (Y-m-d H:i:s) of the latest message sent to the user
 */

$justLongPolling = TRUE;
include '../../inc/init.php';
include __ROOT__.'/inc/classes/conversation.php';
include __ROOT__.'/inc/classes/Request.php';

if (!isset($_POST['unread'],
           $_POST['friends'],
           $_POST['friendRequests'],
           $_POST['lastMessageId'],
           $_SERVER['HTTP_ROOMIES'])
    || $_SERVER['HTTP_ROOMIES'] != 'cactus'
    || !preg_match('/^([a-z]+:([0-9]+(,|))*(;|$))+$/', $_POST['friends']))
{
  include __ROOT__.'/inc/html/notfound.php';
}

session_write_close();

header("Content-type: application/json");

try
{
  $unreadIds = explode(',', htmlentities($_POST['unread']));
  $friendRequestIds = htmlentities($_POST['friendRequests']);
  $friendRequestIds = strlen($friendRequestIds) ? explode(',', $friendRequestIds) : array();
  $noOfRequests = count($friendRequestIds);
  $lastMessageId = htmlentities($_POST['lastMessageId']);
  $friendsTexts = explode(';', $_POST['friends']);
  $friends = array();
  foreach ($friendsTexts as $friendsText)
  {
    $array = explode(':', $friendsText);
    $friends[$array[0]] = strlen($array[1]) ? $array[1] : '0';
  }
  $now = time();
  $lowestOnlineDate = date('Y-m-d H:i:s', $now-180);
  $lowestAwayDate   = date('Y-m-d H:i:s', $now-600);
  $userId = $user->getIdentifier('id');
  $userName = $user->getName();

  $stmts = array(
    // First query: Find new messages for this user
    'newMessages' =>
    $con->prepare("SELECT * FROM rmessages
                           WHERE (message_user_id1 = $userId OR message_user_id2 = $userId)
                             AND message_id > '$lastMessageId'"),

    // Second query: Find where messages are now read
    'readMessage' =>
    $con->prepare("SELECT message_id FROM rmessages
                    WHERE messages_read = 1
                      AND (message_user_id1 = $userId OR message_user_id2 = $userId)
                      AND (message_id = '".implode("' OR message_id = '", $unreadIds)."')"),

    // Third query: Find new friend requests
    'newRequests' =>
    $con->prepare("SELECT conexion_user_id1
                     FROM rconexions
                    WHERE conexion_user_id2 = $userId
                      AND conexion_status = 2
                      AND (conexion_user_id1 != '".implode("' OR conexion_user_id1 != '", $friendRequestIds)."')"),

    // Fourth query: Find deprecated friend requests
    'oldRequests' =>
    $con->prepare("SELECT conexion_user_id1
                     FROM rconexions
                    WHERE conexion_user_id2 = $userId
                      AND conexion_status = 2
                      AND (conexion_user_id1 = '".implode("' OR conexion_user_id1 = '", $friendRequestIds)."')"),

    // Fifth query: Find where there are friends which have changed status
    'friends' =>
    $con->prepare("SELECT user_id
                     FROM rusers
                    WHERE (   (last_online >= '$lowestOnlineDate'
                               AND user_id NOT IN ({$friends['online']}))

                           OR (last_online < '$lowestOnlineDate'
                               AND last_online >= '$lowestAwayDate'
                               AND user_id NOT IN ({$friends['away']}))

                           OR (last_online < '$lowestAwayDate'
                               AND user_id NOT IN ({$friends['offline']}))    )

                      AND (    user_id IN (SELECT conexion_user_id1
                                             FROM rconexions
                                            WHERE conexion_user_id2 = '$userId'
                                              AND conexion_status = 1)
                           OR  user_id IN (SELECT conexion_user_id2
                                             FROM rconexions
                                            WHERE conexion_user_id1 = '$userId'
                                              AND conexion_status = 1))")
  );

  function execute(&$stmts)
  {
    foreach ($stmts as $stmt)
      if (!$stmt->execute())
        return false;
    return true;
  }

  function rowCount(&$stmts, &$noOfRequests)
  {
    // Initialise the row count to 0
    $rowCount = 0;

    // Add the number of new messages
    $rowCount += $stmts['newMessages']->rowCount();

    // Add the number of messages which are now read
    $rowCount += $stmts['readMessage']->rowCount();

    // Add the number of new requests
    $rowCount += $stmts['newRequests']->rowCount();

    // Add the number of requests which are no longer in the database
    $rowCount += $noOfRequests;
    $rowCount -= $stmts['oldRequests']->rowCount();

    // Add the number of users who changed status
    $rowCount += $stmts['friends']->rowCount();

    // Return the row count
    return $rowCount;
  }

  // Try executing. If this fails, something went wrong.
  if (!execute($stmts))
  {
    throw new Exception('Query error!', 1);
  }

  // Longpoll
  for ($i = 0; $i < 30; $i++)
  {
    if (rowCount($stmts, $noOfRequests))
    {
      break;
    }
    sleep(1);
    execute($stmts);
  }

  execute($stmts);

  $response = array(
    'newRequests' => array(
      'template' => Request::$template,
      'content'  => array()
    ),
    'oldRequests' => array(),
    'newMessages' => array(
      'template' => Conversation::$template,
      'content'  => array()
    ),
    'readMessage' => array(),
    'friends' => array(
      'online' => array(),
      'away' => array(),
      'offline' => array()
    )
  );

  $nothingChanged = TRUE;

  $todayDateTime = new DateTime();

  // New messages
  while ($message = $stmts['newMessages']->fetch(PDO::FETCH_ASSOC))
  {
    $nothingChanged = FALSE;
    // Replace '\n' with '<br>'
    $message['message_text'] = nl2br($message['message_text'], false);
    $read = ($message['messages_read'])?'read':'unread';

    $senderId = $message['message_user_id1'];
    $receiverId = $message['message_user_id2'];

    // Get the name and whether it was sent or received
    $sent = $senderId == $userId;
    $otherUser = new User($con, $sent ? $receiverId : $senderId);
    $otherUserId = $otherUser->getIdentifier('id');
    $otherUserName = $otherUser->getName();
    $otherUserUsername = $otherUser->getIdentifier('username');
    $senderName = $sent ? $userName : $otherUserName;
    $sentOrReceived = $sent ? 'sent' : 'received';
    $senderImage = $sent ? $user->getIdentifier('image') : $otherUser->getIdentifier('image');

    $msgDateTime = date_create_from_format('Y-m-d H:i:s', $message['message_timestamp']);
    $diff = $todayDateTime->diff($msgDateTime);
    $diff = (int) $diff->format('%a');
    // If today, output the time and "Today"
    if ($diff == 0)
    {
      $msgDateTimeTitle = 'Today';
      $msgDateTimeText = $msgDateTime->format('H:i');
    }
    // Else, if yesterday, output "yesterday"
    else if ($diff == 1)
    {
      $msgDateTimeTitle = 'Yesterday';
      $msgDateTimeText = 'Yesterday';
    }
    // Else, if within the last 6 days, output the day name
    else if ($diff < 6)
    {
      $msgDateTimeTitle = $msgDateTime->format('l');
      $msgDateTimeText = $msgDateTime->format('D');
    }
    // Else, if the year is still the same, output the date (e.g. 12 February)
    else if ($msgDateTime->format('Y') == $todayDateTime->format('Y'))
    {
      $msgDateTimeTitle = $msgDateTime->format('j F');
      $msgDateTimeText = $msgDateTimeTitle;
    }
    // Else output the date as DD/MM/YYYY
    else
    {
      $msgDateTimeTitle = $msgDateTime->format('d/m/Y');
      $msgDateTimeText = $msgDateTimeTitle;
    }

    array_push(
      $response['newMessages']['content'], 
      array(
        $read.' '.$sentOrReceived,
        $message['message_id'],
        $message['message_timestamp'],
        $senderImage,
        $senderName,
        $message['message_text'],
        $senderId,
        $receiverId,
        $otherUserUsername,
        $otherUserName,
        $msgDateTimeTitle,
        $msgDateTimeText,
        $otherUser->getIdentifier('image')
      )
    );
  }

  // Read message
  while ($row = $stmts['readMessage']->fetch(PDO::FETCH_ASSOC))
  {
    $nothingChanged = FALSE;
    array_push($response['readMessage'], $row['message_id']);
  }

  // New requests
  while ($row = $stmts['newRequests']->fetch(PDO::FETCH_ASSOC))
  {
    $nothingChanged = FALSE;
    $otherUser = new User($con, $row['conexion_user_id1']);
    $otherUserId = $otherUser->getIdentifier('id');
    $otherUsername = $otherUser->getIdentifier('username');
    $percentage = $user->getPercentageWith($otherUser);

    array_push($response['newRequests']['content'], array(
      $otherUserId,
      $otherUserId,
      $otherUsername,
      $otherUser->getIdentifier('image'),
      $otherUserId,
      $otherUserId,
      $otherUserId,
      $otherUserId,
      $otherUsername,
      $otherUsername,
      (160-160*$percentage/100),
      (160*$percentage/100),
      $percentage
    ));
  }

  // Old requests
  $remainingRequests = array();
  while ($row = $stmts['oldRequests']->fetch(PDO::FETCH_ASSOC))
  {
    array_push($remainingRequests, $row['conexion_user_id1']);
  }
  // For each old requests, if it is not in the remaining requests, add it to the oldRequests array
  foreach ($friendRequestIds as $friendRequestId)
  {
    if (!in_array($friendRequestId, $remainingRequests))
    {
      $nothingChanged = FALSE;
      array_push($response['oldRequests'], $friendRequestId);
    }
  }

  // Friends
  while ($row = $stmts['friends']->fetch(PDO::FETCH_ASSOC))
  {
    $otherUserId = $row['user_id'];
    $otherUser = new OtherUser($con, $otherUserId);
    if ($otherUser->getError()) continue;
    $nothingChanged = FALSE;
    $otherUserName = $otherUser->getName(1);
    $otherUserUsername = $otherUser->getCredential('username');
    array_push($response['friends'][$otherUser->getOnlineStatus()],
      array(
        'id' => $otherUserId,
        'name' => $otherUserName,
        'username' => $otherUserUsername
      )
    );
  }
  // Push all the old ids into the array again
  function not_in_arrays(&$friends, $id)
  {
    foreach ($friends as $friendList)
      foreach ($friendList as $friend)
        if ($friend['id'] == $id)
          return FALSE;
    return TRUE;
  }
  foreach ($friends as $onlineStatus => $friendIds)
  {
    foreach (explode(',', $friendIds) as $friendId)
    {
      $friend = new OtherUser($con, $friendId);
      if (!$friend->getError() && not_in_arrays($response['friends'], $friendId))
      {
        $friendName = $friend->getName(1);
        $friendUsername = $friend->getCredential('username');
        array_push($response['friends'][$onlineStatus],
          array(
            'id' => $friendId,
            'name' => $friendName,
            'username' => $friendUsername
          )
        );
      }
    }
  }

  if ($nothingChanged)
  {
    $response = array('nothingChanged' => 1);
  }

  echo json_encode($response);
} catch (Exception $exception)
{
  echo json_encode(array('error' => $exception->getMessage()));
}
?>