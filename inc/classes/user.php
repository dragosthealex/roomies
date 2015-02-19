<?php
// Requires question class from question.php
require_once 'question.php';
/**
* Class represents a user
*
* Has id, username, email, first and last name, birthday, details, and questions
*/
class User
{
  // The db connection handler
  private $con;
  // The user id (unique)
  private $id;
  // The username (unique)
  private $username;
  // The email of the user (unique)
  private $email;
  // The first name
  private $firstName;
  // The last name
  private $lastName;
  // The birthday as dd-mm-yyyy
  private $birthday;
  // Associative rray containing the details (mapped int values).
  private $details;
  // Array containing all the question objects. Each question has the answers.
  // Also, has the answers answered by user
  private $questions;

  /**
  * Constructor
  *
  * Assigns the id, username, email, first name, last name and birthday of the user
  *
  * @param - $con, the connection to db
  * @param - $key, the key that is used for getting the user. Can be id, email or username
  */
  public function __construct($con, $key)
  {
    // Filter the key
    $key = htmlentities($key);
    // Get the basic info for the user from the db
    $stmt = $con->prepare("SELECT user_id, username, user_email FROM rusers
                            WHERE user_id = '$key' OR username = '$key' OR user_email = '$key'");
    $stmt->execute();
    $stmt->bindColumn(1,$id);
    $stmt->bindColumn(2,$username);
    $stmt->bindColumn(3,$email);
    $stmt->fetch();

    $this->id = $id;
    $this->username = $username;
    $this->email = $email;
    $this->con = $con;

    // Get the rest of the details as mapped ints from the db
    $stmt = $con->prepare("SELECT * FROM rdetails WHERE profile_filter_id =".$this->id);
    $stmt->execute();
    $details = $stmt->fetch(PDO::FETCH_ASSOC);

    // Assign the unmapped details
    $this->firstName = $details['first_name'];
    $this->lastName = $details['last_name'];
    $this->birthday = $details['birthday'];

    $this->details = $details;

    $stmt = null;
  }

  /**
  * Function getDetails()
  *
  * Gets the details of the current user, and returns them as an array.
  *
  * @return - $details, the associative array containing the details with true values (unmapped).
  */
  public function getDetails()
  {
    $con = $this->con;
    $details = $this->details;

    // We do not need the first five, because they are already unmapped
    $details['profile_filter_id'] = '';
    $details['first_name'] = '';
    $details['last_name'] = '';
    $details['completed'] = '';
    $details['birthday'] = '';

    $trueDetails = array();

    foreach ($details as $key => $value)
    {
      if($value)
      {
        $stmt = $con->prepare("SELECT map_".$key." FROM rfiltersmap WHERE filter_value = $value");
        $stmt->execute();
        $stmt->bindColumn(1,$filter);
        $stmt->fetch();
        array_push($trueDetails, ucwords($filter));
      }
    }

    return $trueDetails;
  }

  /**
  * Function getName()
  *
  * Gets the name, formated as <first name> <last name>.
  *
  * @return - $name, the name.
  */
  public function getName()
  {
    $name = $this->firstName." ".$this->lastName;
    return $name;
  }

  /**
  * Function getFirstName()
  *
  * Gets the first name
  *
  * @return - $name, the name.
  */
  public function getFirstName()
  {
    $name = $this->firstName;
    return $name;
  }

  /**
  * Function getBirthday($format)
  *
  * Gets the birthday, formated either as dd-mm-yyyy or as age, approximated in years
  *
  * @param - $format, can be 'birthday' or 'age'
  */
  public function getBirthday($format)
  {
    if($format == "birthday")
    {
      return $this->birthday;
    }
    else
    {
      $bday = $this->birthday;
      $bday = explode("-", $bday);
      // The no. of years that the user should be by the end of this year
      $age = date('Y') - $bday[2];
      // If the user did not have his birthday yet this year, substract one
      if($bday[1] > date('m') || ($bday[1] < date('m') && $bday[0] > date('d')))
      {
        $age--;
      }
      return $age;
    }
  }

  /**
  * Function getIdentifier($key)
  *
  * Gets either id, email or username, depending on the given $key parameter
  *
  * @param - $key, can be either 'id', 'email', 'username'
  * @return - $identifier, the desired identifier (id, email or username)
  */
  public function getIdentifier($key)
  {
    switch ($key)
    {
      case 'id':
        return $this->id;
        break;
      case 'username':
        return $this->username;
        break;
      case 'email':
        return $this->email;
        break;
      case 'city':
        return $this->details['uni_city'];
        break;
      default:
        return "Wrong key";
        break;
    }
  }

  /**
  * Function setQuestions()
  *
  * Fills the $questions instance variable with the question objects from
  * the database. Automatically sets the answers too. To be used just when needed,
  * because it should be pretty heavy.
  *
  */
  private function setQuestions()
  {
    $con = $this->con;
    $userId = $this->id;

    //return new Question($con, 1, $userId);
    $stmt = $con->prepare("SELECT question_id FROM rquestionsmap");
    $stmt->execute();
    $no_questions = $stmt->rowCount();

    $questions = array();
    for($i=0; $i<$no_questions; $i++)
    {
      // Create a new question with the given id, retreiving the values for the user
      $question = new Question($con, $i+1, $userId);
      array_push($questions, $question);
    }

    // Assign the array of objects.
    $this->questions = $questions;
  }

  /**
  * Function getQuestion($number)
  *
  * If $number is a valid question id, returns that question object.
  * If $number is either null, or invalid, returns the whole questions array of objects
  *
  * @param - $number, if a valid number, represents the question id
  * @return - $question(s) either one question or all questions in array
  */
  public function getQuestion($number=-1)
  {
    if(!isset($this->questions))
    {
      $this->setQuestions();
    }

    $questions = $this->questions;
    if(isset($questions[$number]))
    {
      $number--;
      return $questions[$number];
    }
    else
    {
      return $questions;
    }
  }

  /**
  * Function getQuestionInfo($questionNo)
  *
  * Returns the question info string from the table, without setting all questions
  *
  * @return - $answer, the string
  */
  public function getQuestionAnswer($questionNo)
  {
    $con = $this->con;
    $userId = $this->id;
    $question = new Question($con, $questionNo, $userId);
    return $question->getInfo();
  }

  /**
  * Function detilsString()
  *
  * Returns the details formated for output
  *
  * @return - $details, the string containing html formatted output
  */

  /**
  * Function addFriend($otherUser)
  *
  * Modifies the friendship status between this user and $other user, depending on $action parameter
  * Action can be:
  * 0 -> remove friend
  * 1 -> add friend
  * 2 -> cancel request
  * 3 -> accept request
  * 4 -> block user
  * 5 -> unblock user
  *
  * @param - $otherUser, the other user object
  * @param - $action, the action that determines the processing
  */
  public function addFriend($otherUser, $action)
  {
    $otherUserId = $otherUser->getIdentifier('id');
    $con = $this->con;
    $thisUserId = $this->id;
    $status = $this->friendshipStatus($otherUser);

    switch ($action)
    {
      case 1:
        if(!$status)
        {
          $stmt = $con->prepare("INSERT INTO rconexions (conexion_user_id1, conexion_user_id2, conexion_status)
                                  VALUES ($thisUserId, $otherUserId, 2)");
          $stmt->execute();
          $stmt = null;
        }
        else
        {
          echo "Error. Apparently you are already friends.";
        }
        break;
      case 0:
      case 2:
        // if($status)
        // {
          $stmt = $con->prepare("DELETE FROM rconexions
                                  WHERE (conexion_user_id2 = $thisUserId AND conexion_user_id1 = $otherUserId)
                                  OR (conexion_user_id1 = $thisUserId AND conexion_user_id2 = $otherUserId)");
          $stmt-> execute();
          $stmt = null;
        // }
        // else
        // {
        //   echo "You are not friends. Stalker!";
        // }
        break;
      case 3:
        if($status == 3)
        {
          $stmt = $con->prepare("UPDATE rconexions SET conexion_status=1
                                  WHERE conexion_user_id2 = $thisUserId AND conexion_user_id1 = $otherUserId");
          $stmt->execute();
          $stmt = null;
        }
        else
        {
          echo "Error. Weird status in database.";
        }
        break;
      default:
        # code...
        break;
    }
  }

  /**
  * Function friendshipStatus($otherUser)
  *
  * Returns 0 if not friends, 1 if friends, 2 if request pending, 3 if request received
  *
  * @param - $otherUser, the user that we check the friendship with
  * @return - $status, the status of the connexion
  */
  public function friendshipStatus($otherUser)
  {
    $otherUserId = $otherUser->getIdentifier('id');
    $con = $this->con;
    $thisUserId = $this->id;

    $stmt = $con->prepare("SELECT conexion_status, conexion_user_id1 FROM rconexions
      WHERE (conexion_user_id1 = $thisUserId AND conexion_user_id2 = $otherUserId)
      OR (conexion_user_id1 = $otherUserId AND conexion_user_id2 = $thisUserId)");
    $stmt->execute();
    // If no row is found, means no friends
    if(!$stmt->rowCount())
    {
      return 0;
    }
    $stmt->bindColumn(1, $status);
    $stmt->bindColumn(2, $id1);
    $stmt->fetch();

    // Check if they are friends
    if($status == 1)
    {
      return 1;
    }

    // Check if this user already sent request
    if($id1 == $thisUserId)
    {
      return 2;
    }

    return 3;
  }

  /**
  * Function getPercentageWith($otherUser)
  *
  * Returns the percetange match between this user and $otherUser
  *
  * @param - $otherUser(User), the other user for percentage
  * @return - $percentage(int), the percentage rounded
  */
  public function getPercentageWith($otherUser)
  {
    $con = $this->con;

    // Localise stuff
    $thisUserId = $this->id;
    $otherUserId = $otherUser->getIdentifier('id');
    $city = $this->getIdentifier('city');

    // Get the percentage from db
    $stmt = $con->prepare("SELECT percentage FROM rpercentages
                            WHERE (percentage_user_id1=$thisUserId AND percentage_user_id2=$otherUserId)
                              OR  (percentage_user_id1=$otherUserId AND percentage_user_id2=$thisUserId)
                              AND percentage_city=$city");
    $stmt-> execute();
    $stmt->bindColumn(1, $percentage);
    $stmt->fetch();

    return $percentage;
  }

  /**
  * Function getNewMessages()
  *
  * Returns all the messages that have not been read yet, into an array
  *
  * @return - $messages(array), the unread messages
  *
  */
  public function getNotifMessages($offset)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    $return = $this->getConv($offset);
    $apparitionArray = $return['apparitionArray'];
    $messagePartners = $return['messagePartners'];
    $unreadArray = $return['unreadArray'];


    $messages = '';

    for($index=0; $index<count($messagePartners) && $index<10; $index++)
    {
      $id2 = $messagePartners[$index];
      $stmt = $con->prepare("SELECT message_text, message_timestamp FROM rmessages
                              WHERE (message_user_id1 = $id2 AND message_user_id2 = $userId)
                                OR (message_user_id1 = $userId AND message_user_id2 = $id2)
                              ORDER BY message_timestamp DESC
                              LIMIT 1");
      $stmt->execute();
      $stmt->bindColumn(1, $text);
      $stmt->bindColumn(2, $timestamp);
      $stmt->fetch();

      // Get the number of unread messages from this user
      $noNewMessages = (isset($unreadArray[$id2]) && $unreadArray[$id2])?"({$unreadArray[$id2]})":"";
      $addReadClass = ($noNewMessages)?"read":"";

      // Get name
      $otherUser = new User($con, $id2);
      $otherUserName = $otherUser->getName();
      $otherUserUsername = $otherUser->getIdentifier('username');

      $firstLine = explode("<br>", $text)[0];

      $messages .=
      "
      <li class='li drop-item drop-wide $addReadClass' title='At $timestamp'>
          <span class='message-icon' style='background-image: url('/media/img/anonymous.jpg')'></span>
          <p class='drop-text'><span class='drop-text-span'>$otherUserName $noNewMessages</span></p>
          <p class='drop-text'><span class='drop-text-span'>$firstLine</span></p>
      </li>
      ";
    }

    return $messages;
  }

    /**
  * Function getAllConversationsJSON()
  *
  * Returns a JSON of conversations
  *
  * @return - $conversations(string), contain conversations
  */
  public function getAllConversationsJSON($offset)
  {
    // Localise con
    $con = $this->con;

    $return = $this->getConv($offset);
    $apparitionArray = $return['apparitionArray'];
    $messagePartners = $return['messagePartners'];
    $unreadArray = $return['unreadArray'];

    $noOfMessagePartners = count($messagePartners);

    $conversations = "{\"template\": [\"<li class='li'><p><a href='/messages/\",
                                  \"'>\",
                                  \"</a></p></li>\"
                                 ],
                       \"length\": $noOfMessagePartners";

    foreach ($messagePartners as $key => $otherUserId)
    {
      $otherUser = new User($con, $otherUserId);
      $otherUserName = $otherUser->getName();
      $noNewMessages = (isset($unreadArray[$otherUserId]) && $unreadArray[$otherUserId])?"({$unreadArray[$otherUserId]})":"";

      $conversations .=", \"$key\": [\"$otherUserId\", \"$otherUserName $noNewMessages\"]";
    }
    $conversations .= "}";

    return $conversations;
  }


  /**
  * Function getAllConversations()
  *
  * Returns an string of conversations
  *
  * @param - $conversations(string), contain conversations
  */
  public function getAllConversations($offset)
  {
    // Localise con
    $con = $this->con;

    $return = $this->getConv($offset);
    $apparitionArray = $return['apparitionArray'];
    $messagePartners = $return['messagePartners'];
    $unreadArray = $return['unreadArray'];

    $conversations = "";
    foreach ($messagePartners as $otherUserId)
    {
      $otherUser = new User($con, $otherUserId);
      $otherUserName = $otherUser->getName();
      $otherUserUsername = $otherUser->getIdentifier('username');
      $noNewMessages = (isset($unreadArray[$otherUserId]) && $unreadArray[$otherUserId])?"({$unreadArray[$otherUserId]})":"";

      $conversations .=
      "
      <li data-id='$otherUserId' class='li'>
        <p>
          <a href='/messages/$otherUserUsername'>$otherUserName $noNewMessages</a>
        </p>
      </li>
      ";
    }
    return $conversations;
  }


// Gets the conversations
private function getConv($offset)
{
      // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // The limit used for getting the conversations
    $limit = $offset + 10;

    // The users that this user has a conversation with
    $messagePartners = array();
    // The array that remembers how many messages we have from a user
    // aka the number of apparitions of a certain user id in our select
    $apparitionArray = array();
    // The array that remembers how many unread messages we have from a user
    $unreadArray = array();


    $stmt = $con->prepare("SELECT message_user_id1, message_user_id2, messages_read FROM rmessages
                            WHERE message_user_id2 = $userId
                              OR message_user_id1 = $userId
                            ORDER BY message_timestamp DESC");
    $stmt->execute();
    $stmt->bindColumn(1, $id1);
    $stmt->bindColumn(2, $id2);
    $stmt->bindColumn(3, $read);

    // Check if we have any message at all
    if(!$stmt->rowCount())
    {
      return "";
    }

    while ($stmt->fetch())
    {
      if($id1 == $userId)
      {
        if(!in_array($id2, $messagePartners))
        {
          array_push($messagePartners, $id2);
          $apparitionArray[$id2] = 1;        }
        else
        {
          $apparitionArray[$id2] ++;
        }
        // Wait 'till we reach 10 conversations
        if(count($messagePartners) == $limit)
        {
          break;
        }
      }
      else
      {
        if(!in_array($id1, $messagePartners))
        {
          array_push($messagePartners, $id1);
          $apparitionArray[$id1] = 1;
          $unreadArray[$id1] = 0;
        }
        else
        {
          $apparitionArray[$id1] ++;
        }
        // Count if the message is unread
        ($read)?:$unreadArray[$id1]++;
        // Wait 'till we reach 10 conversations
        if(count($messagePartners) == $limit)
        {
          break;
        }
      }
    }

    $return['messagePartners'] = $messagePartners;
    $return['unreadArray'] = $unreadArray;
    $return['apparitionArray'] = $apparitionArray;

    return $return;
  }
}// class

?>