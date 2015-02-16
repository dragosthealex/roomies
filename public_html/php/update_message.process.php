<?php
require_once '../../inc/init.php';
session_write_close();
require_once '../../inc/classes/conversation.php';
$headers = getallheaders();

if(!isset($_GET['otherId']) || ! isset($headers['Roomies']) || $headers['Roomies'] != 'cactus')
{
  include_once __ROOT__."/inc/html/notfound.php";
  exit();
}
else
{
  // Code by Daniel. Just wanted proper long-polling in here, without causing merge errors. :P

  // SOME NOTES:
  // I (Daniel) plan to have a JavaScript function which detects inactivity, to prevent
  // unnecessary long-polling. If the user is inactive when these messages arrive,
  // then they should not be "read", until the user resumes activity. At that point,
  // I will use AJAX to call a separate function. Maybe read_messages.process.php.
  // That takes the message ids from _GET. (Meaning they should be output as data attr).

  if (isset($_GET['type']))
  {
    // Initialise message array
    $messages = array();

    // Get the other user from the id given
    $otherUserId = htmlentities($_GET['otherId']);
    $otherUser = new User($con, $otherUserId);
    $otherUserName = $otherUser->getName();

    // Get the current user id and name
    $userId = $user->getIdentifier('id');
    $userName = $user->getName();

    switch ($_GET['type']) {
      case 'new':
        // Long-polling:
        // Requires the last id:
        if (!isset($_GET['lastId']))
        {
          break;
        }

        $lastId = $_GET['lastId'];

        // Get the unread messages sent by $otherUser to $user.
        $stmt = $con->prepare("SELECT * FROM rmessages
                                       WHERE message_user_id1 = $otherUserId
                                         AND message_user_id2 = $userId
                                         AND message_id > '$lastId'
                                    ORDER BY message_timestamp ASC");
        $stmt->execute();

        $timeSpent = 0;

        // Keep doing this until there is an unread message 
        while ($stmt->rowCount() == 0)
        {
          // If the time spent so far is 60 seconds, stop checking
          if ($timeSpent == 60 || connection_aborted())
          {
            exit();
          }

          // Increment the time spent by 1
          sleep(1);
          $timeSpent += 1;

          // Check again
          $stmt->execute();
        } // while
        // At this point, if it didn't exit then rowCount > 0

        // Push each message into the messages array, to be output afterwards
        while($message = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          array_push($messages, $message);
        } // while
        break;
      case 'old':
        // User scrolled up:
        if (!isset($_GET['offset1']))
        {
          break;
        }

        $offset1 = htmlentities($_GET['offset1']);

        // Get all messages sent between $user and $otherUser, and mark them as read.
        // (Mark as read in case they have 50+ unread messages.)
        $stmt = $con->prepare("SELECT * FROM rmessages
                                       WHERE (message_user_id1 = $userId AND message_user_id2 = $otherUserId)
                                          OR (message_user_id2 = $userId AND message_user_id1 = $otherUserId)
                                    ORDER BY message_timestamp DESC
                                       LIMIT 50
                                      OFFSET $offset1");
        $stmt->execute();

        // Push each message into the messages array, to be output afterwards
        while($message = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          array_push($messages, $message);
        } // while

        $messages = array_reverse($messages);
        break;
    } // switch

    // Get the number of loaded messages
    $numberOfLoadedMessages = count($messages);

    // Output the start of the JSON reply.
    $conv  = "{\"template\": [\"<li class='li message \",
                              \"' data-message-id='\",
                              \"'><a class='message-name'>\",
                              \"</a><p class='text'>\",
                              \"</p></li>\"],
               \"length\": $numberOfLoadedMessages";

    // Add each message to the JSON reply
    foreach ($messages as $key => $message)
    {
      // Replace '\n' with '<br>'
      $message['message_text'] = preg_replace('/\r\n|\r|\n/', '<br>', $message['message_text']);

      $read = ($message['messages_read'])?'read':'unread';

      // Stuff changeable for CSS
      if($message['message_user_id1'] == $userId)
      {
        $conv .= ",
                  \"$key\": [\"\",
                             $message[message_id],
                             \"$userName\",
                             \"$message[message_text]\",
                             \"$message[message_timestamp]\"]";
      }
      else
      {
        $conv .= ",
                  \"$key\": [\"$read\",
                             $message[message_id],
                             \"$otherUserName\",
                             \"$message[message_text]\",
                             \"$message[message_timestamp]\"]";
      }
    }

    // Close the conversation object
    $conv .= "}";

    $stmt = null;

    // Get the conversation list
    $allConversations = $user->getAllConversationsJSON(htmlentities($_GET['offset2']));

    // Output the final JSON object
    exit("[$conv, $allConversations]");
  } // if

  // End code by Daniel (The rest is unchanged)

  $offset = 0;
  if(isset($_GET['offset']))
  {
    $offset = htmlentities($_GET['offset']);
  }

  $otherUserId = htmlentities($_GET['otherId']);
  $otherUser = new User($con, $otherUserId);
  $otherName = $otherUser->getName();
  $conversation = new Conversation ($con, $user->getIdentifier('id'), $otherUserId, $offset);
  $conv = $conversation->getAsJSON();
  
  $allConversations = $user->getAllConversationsJSON($offset);

  echo "[$conv, $allConversations]";
}
?>