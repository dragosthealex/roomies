<?php
/**
* Class Conversation
*
* Represents a conversation between two users
*
*/

class Conversation
{
  // Db connection handler
  private $con;
  // Array containing messages (all details)
  private $messages;
  // id of the first user
  private $id1;
  // id of the second user
  private $id2;
  // Loaded messages
  private $numberOfLoadedMessages = 50;
  // The HTML template to use when outputting messages as a string
  public static $template = array(
    "<li class='li message ",
    // Read/unread and sent/received
    "' data-message-id='",
    // The message id
    "' data-message-timestamp='",
    // The message timestamp
    "'><span class='message-pic' style='background-image:url(/media/img/usr/",
    // The name of the user's picture
    ")'></span><a class='message-name'>",
    // The name of the sender
    "</a><p class='text'>",
    // The message itself
    "</p></li>"
  );


  /**
  * Constructor
  *
  * Constructs a conversation, given the two ids.
  *
  * If told to longpoll, it will longpoll for only the new messages (after
  * the given last id, for an optionally-given time).
  *
  * @param - $con, the connection handler
  * @param - $id1(int), the id of the first user
  * @param - $id2(int), the id of the other user
  * @param - $offset, the number of messages already retrieved. Default 0.
  * @param - $long[P]oll, whether or not to longpoll. Default false.
  * @param - $lastMessageId, the id for which every successive id is 'new'. Default 0.
  * @param - $maxTime, the maximum length of time (in seconds) to longpoll for. Default 40.
  */
  public function __construct($con, $id1, $id2, $offset=0, $longpoll=false, $lastMessageId=0, $maxTime=40)
  {
    // Initialise messages array
    $messages = array();

    // If we're longpolling, do weird things.
    if ($longpoll)
    {
      // Get the unread messages sent by $otherUser to $user, in the order
      // in which they were sent
      $stmt = $con->prepare("SELECT * FROM rmessages
                                     WHERE ((message_user_id1 = $id2
                                       AND   message_user_id2 = $id1)
                                        OR  (message_user_id1 = $id1
                                       AND   message_user_id2 = $id2))
                                       AND message_id > '$lastMessageId'
                                  ORDER BY message_id ASC");
      $stmt->execute();

      // Begin recording the time spent in polling
      $timeSpent = 0;

      // Keep doing this until there is a 'new' message 
      // or until the time reaches the max time.
      while ($stmt->rowCount() == 0)
      {
        // If the time spent so far is the max time, stop checking
        if ($timeSpent == $maxTime || connection_aborted())
        {
          break;
        }

        // Increment the time spent by 1
        sleep(1);
        $timeSpent += 1;

        // Check again
        $stmt->execute();
      } // while
    }

    // Otherwise, just do the usual.
    else
    {
      $stmt = $con->prepare("SELECT * FROM rmessages
                                     WHERE (message_user_id1 = $id1
                                       AND  message_user_id2 = $id2)
                                        OR (message_user_id1 = $id2
                                       AND  message_user_id2 = $id1)
                                  ORDER BY message_id DESC
                                     LIMIT 50 OFFSET $offset");

      $stmt->execute();
    }

    // Add all of the messages to the array
    while($messageDetails = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      array_push($messages, $messageDetails);
    }

    // If we didn't longpoll, reverse the array
    if (!$longpoll)
    {
      $messages = array_reverse($messages);
    }

    // Assign instance variables
    $this->id1 = $id1;
    $this->id2 = $id2;
    $this->con = $con;
    $this->messages = $messages;
    $stmt = null;
  }

  /**
  * Function getAsJSON()
  *
  * Returns the same stuff as before, but in JSON
  *
  * @return - $conv(json), the json
  */
  public function getAsJSON($offset=0)
  {
    // Localise stuff
    $id1 = $this->id1;
    $id2 = $this->id2;
    $con = $this->con;
    $template = Conversation::$template;

    // The array of messages
    $messages = $this->messages;
    $numberOfLoadedMessages = count($messages);

    // The conversation as JSON
    $conv = "{\"template\":[";
    foreach ($template as $key => $templateText)
    {
      if ($key > 0)
      {
        $conv .= ",";
      }
      $conv .= "\"$templateText\"";
    }
    $conv .= "],\"length\":$numberOfLoadedMessages";

    // Make the users and localise stuff
    $user1 = new User($con, $id1);
    $user2 = new User($con, $id2);
    $user1Name = $user1->getName();
    $user2Name = $user2->getName();

    foreach ($messages as $key => $message)
    {
      // Replace '\n' with '<br>'
      $message['message_text'] = preg_replace('/\r\n|\r|\n/', '<br>', $message['message_text']);

      // Class for read/unread messages
      $read = ($message['messages_read'])?'read':'unread';

      // Get the name and whether it was sent or received
      if ($message['message_user_id1'] == $id1)
      {
        $id = $id1;
        $name = $user1Name;
        $sentOrReceived = 'sent';
      }
      else
      {
        $id = $id2;
        $name = $user2Name;
        $sentOrReceived = 'received';
      }

      // Add the message into the JSON object
      // TODO: Get proper image. Not just jpg.
      $conv .= ",\"$key\":[
        \"$read $sentOrReceived\",
          $message[message_id],
        \"$message[message_timestamp]\",
        \"$id.jpg\",
        \"$name\",
        \"$message[message_text]\"
      ]";
    }
    $conv .= "}";

    return $conv;
  }


  
  /**
  * Function toString()
  *
  * Returns conversation as string (html) formatted
  *
  * @return - $conv(string), the string with the conversation
  */
  public function toString()
  {
    // Localise stuff
    $id1 = $this->id1;
    $id2 = $this->id2;
    $con = $this->con;
    $template = Conversation::$template;
    // The array of messages
    $messages = $this->messages;

    // The conversation as text
    $conv = "<ul class='ul conversation' id='main_conversation'>";

    // Make the users and localise stuff
    $user1 = new User($con, $id1);
    $user2 = new User($con, $id2);
    $user1Name = $user1->getName();
    $user2Name = $user2->getName();

    foreach ($messages as $message)
    {
      // Replace '\n' with '<br>'
      $message['message_text'] = nl2br($message['message_text']);
      $read = ($message['messages_read'])?'read':'unread';

      // Get the name and whether it was sent or received
      if ($message['message_user_id1'] == $id1)
      {
        $id = $id1;
        $name = $user1Name;
        $sentOrReceived = 'sent';
      }
      else
      {
        $id = $id2;
        $name = $user2Name;
        $sentOrReceived = 'received';
      }

      // Add the message into the string
      // TODO: Get proper image. Not just jpg.
      $conv .= $template[0].$read.' '.$sentOrReceived
              .$template[1].$message['message_id']
              .$template[2].$message['message_timestamp']
              .$template[3].$id.'.jpg'
              .$template[4].$name
              .$template[5].$message['message_text']
              .$template[6];
    }
    $conv .= "</ul>";

    $stmt = null;
    return $conv;
  }

  /**
  * Function readMessages()
  *
  * Updates all the messages in db, setting read status to true
  *
  */
  public function readMessages()
  {
    // Localise stuff
    $id1 = $this->id1;
    $id2 = $this->id2;
    $con = $this->con;

    // Update db
    $stmt = $con->prepare("UPDATE rmessages SET messages_read = 1
                            WHERE (message_user_id1 = $id2 AND message_user_id2 = $id1)
                              AND messages_read = 0");
    $stmt->execute();
    $stmt = null;
  }
}
?>