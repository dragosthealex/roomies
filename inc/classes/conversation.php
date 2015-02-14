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


  /**
  * Constructonr
  *
  * Constructs a conversation, given the two ids
  *
  * @param - $con, the connection handler
  * @param - $id1(int), the id of the first user
  * @param - $id2(int), the id of the other user
  */
  public function __construct($con, $id1, $id2, $offset=0)
  {
    // Initialise messages array
    $messages = array();

    $limit = $offset + 50;

    $stmt = $con->prepare("SELECT * FROM rmessages
                            WHERE (message_user_id1 = $id1 AND message_user_id2 = $id2)
                              OR (message_user_id1 = $id2 AND message_user_id2 = $id1)
                            ORDER BY message_timestamp DESC
                            LIMIT $limit");

    $stmt->execute();
    while($messageDetails = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      array_push($messages, $messageDetails);
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
    // The array of messages
    $messages = $this->messages;
    $numberOfLoadedMessages = count($messages);

    // The conversation as text
    $conv = "{\"template\": [\"<div class='message \",
                         \"'><a class='message-name'>\",
                         \"</a><p class='text'>\",
                         \"</p></div>\"
                         ],
              \"length\": $numberOfLoadedMessages";

    // Make the users and localise stuff
    $user1 = new User($con, $id1);
    $user2 = new User($con, $id2);
    $user1Name = $user1->getName();
    $user2Name = $user2->getName();

    // We need to reverse the array
    $messages = array_reverse($messages);

    foreach ($messages as $key => $message)
    {
      // Replace '\n' with '<br>'
      $message['message_text'] = preg_replace('/\r\n|\r|\n/', '<br>', $message['message_text']);

      $read = ($message['messages_read'])?'read':'unread';

      // Stuff changeable for CSS
      if($message['message_user_id1'] == $this->id1)
      {
        $conv .= ",
                  \"$key\": [\"\",
                         \"$user1Name\",
                         \"$message[message_text]\",
                         \"$message[message_timestamp]\"
                         ]";
      }
      else
      {
        $conv .= ",
                  \"$key\": [\"$read\",
                         \"$user2Name\",
                         \"$message[message_text]\",
                         \"$message[message_timestamp]\"
                         ]";
      }
    }
    $conv .= "}";

    $stmt = null;
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
    // The array of messages
    $messages = $this->messages;

    // The conversation as text
    $conv = "<ul class='ul'>";

    // Make the users and localise stuff
    $user1 = new User($con, $id1);
    $user2 = new User($con, $id2);
    $user1Name = $user1->getName();
    $user2Name = $user2->getName();

    $messages = array_reverse($messages);
    foreach ($messages as $message)
    {
      // Replace '\n' with '<br>'
      $message['message_text'] = nl2br($message['message_text']);
      $conv .= "<li class='li'>";
      $read = ($message['messages_read'])?'read':'unread';

      // Stuff changeable for CSS
      if($message['message_user_id1'] == $this->id1)
      {
        $conv .= "<div class='message'><a class='message-name'>$user1Name</a><p class='text'>$message[message_text]</p></message>";
      }
      else
      {
        $conv .= "<div class='message $read'><a class='message-name'>$user2Name</a><p class='text'>$message[message_text]</p></div>";
      }
      $conv .= "</li>";
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
                            WHERE (message_user_id1 = $id2 AND message_user_id1 = $id2)
                              AND messages_read = 0");
    $stmt->execute();
    $stmt = null;
  }
}
?>