<?php
/**
* Class Conversation
*
* Represents a conversation between two users
*
*/

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
public function __construct($con, $id1, $id2)
{
  // Initialise messages array
  $messages = array();

  $stmt = $con->prepare("SELECT * FROM rmessages
                          WHERE message_user_id1 = $id1 AND message_user_id2 = $id2
                          ORDER BY message_timestamp DESC
                          LIMIT 50");
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
}

?>