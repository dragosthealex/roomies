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
  $usersFriendsArray = $user2->getFriends('id');
  $usersFriends = count($usersFriendsArray) > 0 ? implode(',', $usersFriendsArray) : 0;
  $friendsTexts = explode(';', $_POST['friends']);
  $friends = array();
  foreach ($friendsTexts as $friendsText)
  {
    $array = explode(':', $friendsText);
    $friends[$array[0]] = strlen($array[1]) ? $array[1] : '0';
  }
  $noOfFriends = 0;
  foreach ($friends as $list)
  {
    $noOfFriends += $list == '0' ? 0 : (substr_count($list, ',') + 1);
  }
  $now = time();
  $lowestOnlineDate = date('Y-m-d H:i:s', $now-180);
  $lowestAwayDate   = date('Y-m-d H:i:s', $now-600);
  $userId = $user2->getCredential('id');
  $userName = $user2->getName();
  $userUsername = $user2->getCredential('username');
  $userImage = $user2->getCredential('image');
  $userGroups = implode("' OR message_group = '", $user2->getCredential('groups'));
  $stmts = array(
    // First query: Find new messages for this user
    'newMessages' =>
    $con->prepare("SELECT * FROM rmessages
                           WHERE (   message_user_id1 = $userId
                                  OR message_user_id2 = $userId
                                  OR message_group = '$userGroups')
                             AND message_id > '$lastMessageId'"),

    // Second query: Find where messages are now read
    'readMessage' =>
    $con->prepare("SELECT message_id FROM rmessages
                    WHERE messages_read = 1
                      AND (   message_user_id1 = $userId
                           OR message_user_id2 = $userId
                           OR message_group = '$userGroups')
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

                      AND user_id IN ($usersFriends)"),

    // Sixth query: Find where friends are no longer friends
    'oldFriends' =>
    $con->prepare("SELECT 0
                     FROM rconexions
                    WHERE (conexion_user_id1 = '$userId'
                           OR conexion_user_id2 = '$userId')
                      AND conexion_status = 1")
  );

  function execute(&$stmts)
  {
    foreach ($stmts as $stmt)
      if (!$stmt->execute())
        return false;
    return true;
  }

  // Try executing. If this fails, something went wrong.
  if (!execute($stmts))
  {
    throw new Exception("Query error!", 1);
  }

  // Longpoll
  for ($i = 0; $i < 30; $i++)
  {
    execute($stmts);
    if ($stmts['newMessages']->rowCount()) break;
    if ($stmts['readMessage']->rowCount()) break;
    if ($stmts['newRequests']->rowCount()) break;
    if ($noOfRequests - $stmts['oldRequests']->rowCount()) break;
    if ($stmts['friends']->rowCount()) break;
    if ($noOfFriends - $stmts['oldFriends']->rowCount()) break;
    sleep(1);
  }

  $response = array(
    'newRequests' => array(
      'template' => Request::$template,
      'content'  => array()
    ),
    'oldRequests' => array(),
    'newMessages' => array(
      'template' => "<li class='li message %{message.class}' data-message-id='%{message.id}' data-message-timestamp='%{message.timestamp}'>"
                   ."<a class='message-pic' href='/profile/%{sender.username}' style='background-image:url(%{sender.image}),url(../media/img/default.gif)'></a>"
                   ."<a class='message-name' href='/profile/%{sender.username}'>%{sender.name}</a>"
                   ."<p class='text'>%{message.text}</p>"
                   ."</li>",
      'notifTemplate' => '',
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
  $stmt = $con->prepare("SELECT message_user_id1
                           FROM rmessages
                          WHERE (   (    (message_user_id1 = ? AND message_user_id2 = ?)
                                     OR  (message_user_id1 = ? AND message_user_id2 = ?)
                                     AND (message_group = 0))
                                 OR message_group = ?)
                            AND (message_id < ?)
                       ORDER BY message_id DESC
                          LIMIT 1");
  $stmt->bindParam(1, $senderId);
  $stmt->bindParam(2, $receiverId);
  $stmt->bindParam(3, $receiverId);
  $stmt->bindParam(4, $senderId);
  $stmt->bindParam(5, $messageGroup);
  $stmt->bindParam(6, $messageId);
  $stmt->bindColumn(1, $previousAuthorId);
  while ($message = $stmts['newMessages']->fetch(PDO::FETCH_ASSOC))
  {

    $nothingChanged = FALSE;

    $messageId = $message['message_id'];
    $messageText = nl2br($message['message_text'], false);
    $messageTimestamp = $message['message_timestamp'];
    $messageGroup = $message['message_group'];

    $senderId = $message['message_user_id1'];
    $receiverId = $message['message_user_id2'];
    // Get read or unread
    $read = ($message['messages_read']) ? 'read' : 'unread';
    // Get sent or received
    $sent = $senderId == $userId;
    $sentOrReceived = $sent ? 'sent' : 'received';
    // Get sameAuthor or not
    $sameAuthor = $stmt->execute() && $stmt->rowCount() && $stmt->fetch()
                  && $previousAuthorId == $senderId ? 'sameAuthor' : '';
    $messageClass = $read.' '.$sentOrReceived.' '.$sameAuthor;

    if ($sent)
    {
      if (!$messageGroup)
      {
        $other = new OtherUser($con, $receiverId);
        $otherName = $other->getName($user2->friendShipStatus($other));
        $otherUsername = $other->getCredential('username');
        $otherImage = $other->getCredential('image');
      }
      $senderName = $userName;
      $senderUsername = $userUsername;
      $senderImage = $userImage;
    }
    else
    {
      $other = new OtherUser($con, $senderId);
      $otherName = $other->getName($user2->friendShipStatus($other));
      $otherUsername = $other->getCredential('username');
      $otherImage = $other->getCredential('image');
      $senderName = $otherName;
      $senderUsername = $otherUsername;
      $senderImage = $otherImage;
    }

    if ($messageGroup)
    {
      $stmt2 = $con->prepare("SELECT group_name FROM rgroups WHERE group_id = '$messageGroup'");
      $stmt2->bindColumn(1, $notifName);
      $stmt2->execute();
      $stmt2->fetch();
      $notifUsername = 'group-'.$messageGroup;
      $notifImage = $senderImage;
    }
    else
    {
      $notifName = $otherName;
      $notifUsername = $otherUsername;
      $notifImage = $otherImage;
    }

    $notifClass = $read.' '.$sentOrReceived;

    $msgDateTime = date_create_from_format('Y-m-d H:i:s', $messageTimestamp);
    $diff = $todayDateTime->diff($msgDateTime);
    $diff = (int) $diff->format('%a');
    // If today, output the time and "Today"
    if ($diff == 0)
    {
      $notifTimestampTitle = 'Today';
      $notifTimestampText = $msgDateTime->format('H:i');
    }
    // Else, if yesterday, output "yesterday"
    else if ($diff == 1)
    {
      $notifTimestampTitle = 'Yesterday';
      $notifTimestampText = 'Yesterday';
    }
    // Else, if within the last 6 days, output the day name
    else if ($diff < 6)
    {
      $notifTimestampTitle = $msgDateTime->format('l');
      $notifTimestampText = $msgDateTime->format('D');
    }
    // Else, if the year is still the same, output the date (e.g. 12 February)
    else if ($msgDateTime->format('Y') == $todayDateTime->format('Y'))
    {
      $notifTimestampTitle = $msgDateTime->format('j F');
      $notifTimestampText = $notifTimestampTitle;
    }
    // Else output the date as DD/MM/YYYY
    else
    {
      $notifTimestampTitle = $msgDateTime->format('d/m/Y');
      $notifTimestampText = $notifTimestampTitle;
    }

    array_push(
      $response['newMessages']['content'], 
      array(
        'message.id'            => $messageId,
        'message.text'          => $messageText,
        'message.timestamp'     => $messageTimestamp,
        'message.group'         => $messageGroup,
        'message.class'         => $messageClass,
        'receiver.id'           => $receiverId,
        'sender.id'             => $senderId,
        'sender.name'           => $senderName,
        'sender.username'       => $senderUsername,
        'sender.image'          => $senderImage,
        'notif.name'            => $notifName,
        'notif.username'        => $notifUsername,
        'notif.image'           => $notifImage,
        'notif.class'           => $notifClass,
        'notif.timestamp.title' => $notifTimestampTitle,
        'notif.timestamp.text'  => $notifTimestampText
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
  $usersFriendsArray = $user2->getFriends('id');
  foreach ($friends as $onlineStatus => $friendIds)
  {
    foreach (explode(',', $friendIds) as $friendId)
    {
      $friend = new OtherUser($con, $friendId);
      if (   !$friend->getError()
          && not_in_arrays($response['friends'], $friendId)
          && in_array($friendId, $usersFriendsArray))
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

  function quickSortFriendList($array)
  {
    if (count($array) < 2) return $array;
    $left = $right = array();
    reset($array);
    $pivot_key = key($array);
    $pivot = array_shift($array);
    foreach($array as $k => $v)
      if(strcmp($v['name'], $pivot['name']) < 0)
        $left[$k] = $v;
      else
        $right[$k] = $v;
    return array_merge(quickSortFriendList($left), array($pivot_key => $pivot), quickSortFriendList($right));
  }
  foreach ($response['friends'] as $onlineStatus => $friendIds)
  {
    $response['friends'][$onlineStatus] = quickSortFriendList($friendIds);
  }

  if ($noOfFriends - $stmts['oldFriends']->rowCount())
  {
    $nothingChanged = FALSE;
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