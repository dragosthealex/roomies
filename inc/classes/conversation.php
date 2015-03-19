<?php
/**
* Class Conversation
*
* Represents a conversation between two users
*
*/

class Conversation extends Base
{
  // Array containing messages (all details)
  private $messages;
  // id of the first user
  private $id1;
  // id of the second user
  private $id2;
  // The group id (if existing)
  private $group = 0;
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
    "'><a href='/profile/",
    // The username of the sender
    "' class='message-pic' style='background-image:url(",
    // The name of the user's picture
    "), url(../media/img/default.gif)'></a><a class='message-name' href='/profile/",
    // The username of the sender
    "'>",
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
  * @param - $group(int), the id of the group. Default 0
  * @param - $usersInConversation(int array) the users in this conv
  */
  public function __construct($con, $id1, $id2, $offset=0, $group=0)
  {
    // Initialise messages array
    $messages = array();

    try
    {
      $stmt = $con->prepare("SELECT * FROM rmessages

                                     WHERE (   (message_user_id1 = $id1 AND message_user_id2 = $id2)
                                            OR (message_user_id1 = $id2 AND message_user_id2 = $id1)
                                            OR (message_user_id2 = 0))

                                       AND message_group = $group

                                  ORDER BY message_id DESC
                                     LIMIT 50 OFFSET $offset");

      if(!$stmt->execute())
      {
        throw new Exception("Error getting conversation from database", 1);
      }

      // Add all of the messages to the array
      while($messageDetails = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        array_push($messages, $messageDetails);
      }

      // reverse the array cus better with ass in front
      $messages = array_reverse($messages);
      

      // Assign instance variables
      $this->id1 = $id1;
      $this->id2 = $id2;
      $this->con = $con;
      $this->group = $group;
      $this->messages = $messages;
      $stmt = null;
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
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
    $groupId = $this->group;
    $template = Conversation::$template;

    // The array of messages
    $messages = $this->messages;
    $numberOfLoadedMessages = count($messages);

    try
    {
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
      $userOfId1 = new CurrentUser($con);
      if($userOfId1->getError())
      {
        throw new Exception("Error with current user's initialisation in conversation", 1);
      }
      $userOfId1Name = $userOfId1->getName();
      $previousAuthorId = 0;
      foreach ($messages as $message)
      {
        // Replace '\n' with '<br>'
        $message['message_text'] = preg_replace('/\r\n|\r|\n/', '<br>', $message['message_text']);
        $read = ($message['messages_read'])?'read':'unread';
        $sameAuthor = $previousAuthorId == $message['message_user_id1'] ? 'sameAuthor' : '';
        $previousAuthorId = $message['message_user_id1'];

        // Get the name and whether it was sent or received
        if ($message['message_user_id1'] == $id1)
        {
          $id = $id1;
          $image = $userOfId1->getCredential('image');
          $username = $userOfId1->getCredential('username');
          $name = $userOfId1Name;
          $sentOrReceived = 'sent';
        }
        else
        { 
          $id = $message['message_user_id1'];
          $otherUser = new OtherUser($con, $id);
          if($otherUser->getError())
            {
              $this->errorMsg = $otherUser->getError();
              continue;
            }
          $image = $otherUser->getCredential('image');
          $username = $otherUser->getCredential('username');
          $name = $otherUser->getName(1);
          $sentOrReceived = 'received';
        }

        // Add the message into the JSON object
        // TODO: Get proper image. Not just jpg.
        $conv .= ",\"$key\":[
          \"$read $sentOrReceived $sameAuthor\",
            $message[message_id],
          \"$message[message_timestamp]\",
          \"$username\",
          \"$image\",
          \"$username\",
          \"$name\",
          \"$message[message_text]\"
        ]";
      }
      $conv .= "}";

      return $conv;
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
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
    $group = $this->group;
    $template = Conversation::$template;
    // The array of messages
    $messages = $this->messages;
    
    try
    {
      $groupClass = ($group != 0)?'group':'';
      // The conversation as text
      $conv = "<ul class='ul $groupClass conversation' id='main_conversation' data-conv-id='$id2' data-group-id='$group'>";

      // Make the users and localise stuff
      $userOfId1 = new CurrentUser($con);
      if($userOfId1->getError())
      {
        throw new Exception("Error with current user's initialisation in conversation", 1);
      }
      $userOfId1Name = $userOfId1->getName();
      $previousAuthorId = 0;
      foreach ($messages as $message)
      {
        // Replace '\n' with '<br>'
        $message['message_text'] = nl2br($message['message_text'], false);
        $read = ($message['messages_read'])?'read':'unread';
        $sameAuthor = $previousAuthorId == $message['message_user_id1'] ? 'sameAuthor' : '';
        $previousAuthorId = $message['message_user_id1'];

        // Get the name and whether it was sent or received
        if ($message['message_user_id1'] == $id1)
        {
          $id = $id1;
          $image = $userOfId1->getCredential('image');
          $username = $userOfId1->getCredential('username');
          $name = $userOfId1Name;
          $sentOrReceived = 'sent';
        }
        else
        {
          $id = $message['message_user_id1'];
          $otherUser = new OtherUser($con, $id);
          if($otherUser->getError())
          {
            $this->errorMsg = $otherUser->getError();
            continue;
          }
          $image = $otherUser->getCredential('image');
          $username = $otherUser->getCredential('username');
          $name = $otherUser->getName(1);
          $sentOrReceived = 'received';
        }
        // Add the message into the string
        // TODO: Get proper image. Not just jpg.
        $conv .= $template[0].$read.' '.$sentOrReceived.' '.$sameAuthor
                .$template[1].$message['message_id']
                .$template[2].$message['message_timestamp']
                .$template[3].$username
                .$template[4].$image
                .$template[5].$username
                .$template[6].$name
                .$template[7].$message['message_text']
                .$template[8];
      }
      $conv .= "</ul>";

      $stmt = null;
      return $conv;
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
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