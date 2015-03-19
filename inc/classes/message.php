<?php
/**
* Class Message
*
* Represents a message sent by an user
*
*/

class Message extends Base
{
  // The text of the message
  private $text;
  // The time and date of the message as dd-mm-yyyy-hh-mm-ss
  private $timeStamp;
  // The unique id of the message
  private $author;
  // The group id
  private $groupId=0;

  /**
  *
  * Constructor
  *
  * Constructs a message, given its id OR its text
  *
  * @param - $key(String), can be either 'id' or 'text', and determines if the message is 
  *         just retrieved from db, or if a new message is inserted
  * @param - $values(mixed), the id or the text and userIds, as array, of the message
  */
  public function __construct($con, $key, $values)
  {
    try
    {
      if($key == 'id')
      {
        // Localise the id
        $message_id = $values;

        // Get the message values from db
        $stmt = $con->prepare("SELECT * FROM rmessages WHERE message_id = $message_id");
        $stmt->execute();
        $messageDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Assign instance variables
        $this->text = $messageDetails['message_text'];
        $this->author = $messageDetails['message_user_id1'];
        $this->con = $con;
        $this->timeStamp = $messageDetails['message_timestamp'];
      }
      else if($key == 'text')
      {
        // Localise the text
        $message_text = $values[2];
        $message_user_id1 = $values[0];
        $message_user_id2 = $values[1];
        $groupId = $values[3];

        // Insert new message in db
        $stmt = $con->prepare("INSERT INTO rmessages (message_text, message_user_id1, message_user_id2, message_group)
                                VALUES (\"$message_text\", $message_user_id1, $message_user_id2, $groupId)");
        $stmt->execute();

        // Assign instance variables
        $this->text = $message_text;
        $this->author = $message_user_id1;
        $this->con = $con;
        $this->timeStamp = date('Y-m-d H:i:s');
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }

  /**
  * Function getDetails($key)
  *
  * Returns id,author,text or timestamp, depending on the values of $key
  *
  * @param - $key(String), can be 'id', 'author', 'text' or 'timestamp'
  * @return - $detail(mixed), the detail returned
  */
  public function getDetails($key)
  {
    switch ($key)
    {
      case 'id':
        return $this->id;
        break;
      case 'author':
        return $this->author;
        break;
      case 'text':
        return $this->text;
        break;
      case 'timestamp':
        return $this->timeStamp;
        break;
      default:
        return $this->text;
        break;
    }
  }
}


?>