<?php
/**
* class GeneralUser
*
* Abstract class representing a general user, extends Base, has the public credentials
*
*/
require_once __ROOT__.'/inc/classes/Base.php';
abstract class GeneralUser extends Base
{
  // The username
  protected $username;
  // The email
  protected $email;
  // The rank
  protected $rank;
  // The details, an array containing mapped INT values
  protected $details;
  // Array containing all the question objects. Each question has the answers.
  // Also, has the answers answered by user
  protected $questions;
  // The birthday
  protected $birthday;
  // The name
  protected $name;

  /**
  * Function getCredential($key)
  *
  * Returns id, email, username, rank, and any detail
  *
  * @return - $credential(String), the credential returned
  */
  public function getCredential($key)
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
      case 'rank':
        return $this->rank;
      default:
        return isset($this->details[$key])?$this->details[$key]:'Wrong key';
        break;
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
  * Gets the username by default
  *
  * @return - $username
  */
  public function getName()
  {
    return $this->username;
  }

  /*
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
  * Function getName(optional $friendshipStatus)
  *
  * Gets the name or the username of this user, depending on their privacy settings, and on the friendship status
  *
  * @param - $friendshipStatus(int), the friendship status
  * @return - $name(String), the username or name
  */
  public function getName($friendshipStatus = 0)
  {
    // Localise stuff
    $con = $this->con;
    $id = $this->id;

    // Search for privacy
    // TODO

    // Get for the status
    if($friendshipStatus == 1)
    {
      return $this->name;
    }
    else
    {
      return $this->username;
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

  protected function getImagePath()
  {

  }
}





?>