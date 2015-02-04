<?php
// Requires question class from question.php
require_once 'question.php';
/**
* Class represents a user
*
* Has id, username, email, first and last name, birthday, details, and questions
*/
class User()
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
    // Get the basic info for the user from the db
    $stmt = $con->prepare("SELECT user_id, username, user_email FROM rusers 
                            WHERE user_id = '$key' OR username = '$key' OR user_email = '$key'");
    $stmt->execute();
    $stmt->bindColumn(1,$id);
    $stmt->bindColumn(2,$username);
    $user_email->bindColumn(3,$email);
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
    $details[0] = '';
    $details[1] = '';
    $details[2] = '';
    $details[3] = '';
    $details[4] = '';

    $trueDetails = array();

    foreach ($details as $key => $value)
    {
      if($value)
      {
        $stmt = $con->prepare("SELECT $key FROM rfiltersmap WHERE filter_value = $value");
        $stmt->execute();
        $stmt->bindColumn(1,$filter);
        array_push($trueDetails, $filter);
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
  public function setQuestions()
  {
    $con = $this->con;
    $userId = $this->id;

    $stmt = $con->prepare("SELECT value FROM rsiteinfo WHERE info = 'no_questions'");
    $stmt->execute();
    $stmt->bindColumn(1, $no_questions);
    $stmt->fetch();

    $questions = array();
    for($i=1; $i<=$no_questions; $i++)
    {
      // Create a new question with the given id, retreiving the values for the user
      $question = new Question($con, $i, $userId);
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
  public function getQuestion($number)
  {
    $questions = $this->questions;
    if(isset($questions["$number"]))
    {
      return $questions["$number"];
    }
    else
    {
      return $questions;
    }
  }
}

?>