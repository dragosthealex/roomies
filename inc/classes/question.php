<?php
// HAVE TO COMPLETE QUESTION TOSTRING WITH PROPER CLASS NAMES
include __ROOT__."/inc/classes/answer.php";
/**
* Question class
*
* Represents a question. Has question text, question answers (with text and values), has importance
*
*/
class Question
{
  // The db handler
  private $con;
  // The id of question
  private $id;
  // The text body of the question
  private $text;
  // The array containing the answers (strings)
  private $answers;
  // The answer chosen by user
  private $answerForMe;
  // The answers wanted for others, chosen by user. array
  private $answersForThem;
  // The importance chosen by user
  private $importance;
  // The user id;
  private $userId;

  /**
  * Constructor
  *
  * Constructs a new question, given the text. Takes rest of the values from the db
  * 
  * @param - $con, the database connection handler
  * @param - $id, the id of the question
  * @param - $user, the user whose questions are treated
  */
  public function __construct($con, $id, $userId)
  {
    $this->con = $con;
    $this->userId = $userId;

    // Get the question answer
    $stmt = $con->prepare("SELECT question_text, question_answers FROM rquestionsmap WHERE question_id = $id");
    $stmt->execute();
    $stmt->bindColumn(1, $text);
    $stmt->bindColumn(2, $answerIds);
    $stmt->fetch();

    $this->text = $text;
    $answers = array();
    $answerIds = explode(":", $answerIds);
    
    foreach($answerIds as $answerId)
    {
      $answer = new Answer($answerId);
      array_push($answers, $answer);
    }

    // The answeres are OBJECTS. have ids and texts
    $this->answers = $answers;

    /*
    The question info will be stored as a string containin three int values delimited by a colon (:).
    The first will be the answer that applies to the user.
    The second will be an array of answers that user would like others to have
    The third will be the importance of the question
    */

    if($userId)
    {
      $stmt = $con->prepare("SELECT question".$id." FROM ruser_qa WHERE qa_user_id = ".$userId);
      $stmt->execute();
      $stmt->bindColumn(1, $questionInfo);
      $stmt->fetch();
    }
    else
    {
      $questionInfo = 0;
    }
    // Check if the question was answered
    if($questionInfo)
    {
      $questionInfo = explode(":", $questionInfo);
      $this->answerForMe = $questionInfo[0];
      // Explode the accepted answers for them into an array;
      $answersForThem = $questionInfo[1];
      $answersForThem = explode(",", $answersForThem);
      $this->answersForThem = $answersForThem;
      $this->importance = $questionInfo[2];
    }

    $stmt = null;
  }

  /**
  * Function setAnswers($forMe, $forThem, $importance)
  *
  * Sets the answers and updates the database accordingly.
  *
  * @param - $forMe, the answer appliable for the user
  * @param - $forThem, the answer appliable to others, array
  * @param - $importance, the importance of the question
  */
  function setAnswers($forMe, $forThem, $importance)
  {
    $userId = $this->userId;
    $con = $this->con;
    $id = $this->id;

    // $forMe and $forThem are the int values. We get the text values and
    // assign them to the variables
    $this->answerForMe = $forMe;
    $this->answersForThem = $forThem;
    $this->importance = $importance;

    // Construct the string
    if(isset($forThem[1]))
    {
      $forThem = implode(",", $forThem);
    }
    else
    {
      $forThem = $forThem['0'];
    }
    $questionInfo = "$forMe:$forThem:$importance";

    // Update the database
    $stmt = $con->prepare("UPDATE ruser_qa SET question".$id."='$questionInfo'");
    $stmt->execute();
    $stmt = null;
  }

  /**
  * Function toString()
  *
  * Returns the question, properly formated. TO BE COMPLETED WITH CSS CLASS NAMES
  *
  * @return - $question, the question formated as html elements
  */
  public function toString()
  {
    $answers = $this->answers;
    $text = $this->text;
    $id = $this->id;

    if(isset($this->answersForMe) && $this->answerForMe)
    {
      // Get the text of the answer for me
      $forMeId = $this->answerForMe;
      $forMe = new Answer($forMeId)->getText();

      $answers = $this->answers;

      $forThem = $this->answersForThem;
      $forThem = explode(",", $forThem);

      // Get the text of the answers for them
      $forThemText = array();
      foreach ($forThem as $answerId)
      {
        $acceptedAnswerText = new Answer($answerId)->getText();
        array_push($forThemText, $acceptedAnswerText);
      }

      $importance = $this->importance;

      $question = 
      "
      <div class='question'>
        <div class='question-text'>
          <p class='text'>
            $text
          </p>
        </div>
        <div class='question-answers for-me'>
      ";
      $question .=
      "
        <div class='answer answered'>
          <p class='text'>
            $forMe
          </p>
        </div>
      ";      
      $question .=
      "
        </div>
        <div class='question-answers for-others'>
      ";
      foreach ($forThemText as $answer)
      {
        $question .=
        "
          <div class='answer answered'>
            <p class='text'>
              $answer
            </p>
          </div>
        ";
      }
      $question .=
      "
        </div>
        <div class='importance answered'>
          <p class='text'>$importance</p>
        </div>
      </div>
      ";
    }
    else
    {
      $question = 
      "
      <div class='question'>
        <div class='question-text'>
          <p class='text'>
            $text
          </p>
        </div>
        <div class='question-answers for-me'>
      ";
      foreach ($answers as $answer)
      {
        $answerText = $answer->getText();
        $answerId = $answer->getId();
        $question .=
        "
          <div class='answer'>
            <p class='text'>
              $answerText
            </p>
            <input class='answer-radio' name='question$id' type='radio' value='$answerId'></input>
          </div>
        ";      
      }
      $question .=
      "
        </div>
        <div class='question-answers for-others'>
      ";
      foreach ($answers as $answerId => $answer)
      {
        $answerText = $answer->getText();
        $answerId = $answer->getId();
        $question .=
        "
          <div class='answer'>
            <p class='text'>
              $answerText
            </p>
            <input class='answer-checkbox' name='question$id' type='checkbox' value='$answerId'></input>
          </div>
        ";
      }
      $question .=
      "
        </div>
        <div class='importance'>
          <p class='text'>Irellevant</p>
          <input class='importance-radio' name='importance_questions_$id' type='radio' value='0'></input>
          <p class='text'>Not too important</p>
          <input class='importance-radio' name='importance_questions_$id' type='radio' value='1'></input>
          <p class='text'>Somewhat important</p>
          <input class='importance-radio' name='importance_questions_$id' type='radio' value='2'></input>
          <p class='text'>Very important</p>
          <input class='importance-radio' name='importance_questions_$id' type='radio' value='3'></input>
        </div>
      </div>
      ";
      return $question;
    }
  }

  /**
  * Function getQuestionInfo()
  *
  * Gets the question informations as an array, containing the mapped values.
  * Used for the matching algorithm
  * 
  * @return - $questionInfo, the array with question info. If the question is not answered, returns 0 (false)
  */
  public function getQuestionInfo()
  {
    if(isset($this->answersForThem, $this->answerForMe, $this->importance))
    {
      $forThem = $this->answersForThem;
      $forMe = $this->answerForMe;
      $importance = $this->importance;
      return array("$forMe", $forThem, "$importance");
    }
    else
    {
      return 0;
    }
  }

  /**
  * Function getAllAnswers()
  *
  * Returns an array with the answers, in their order
  *
  * @return - $answers, the array containing each answer
  */
  public function getAllAnswers()
  {
    return $this->answers;
  }

  /**
  * Function getText()
  *
  * Returns the question text
  *
  * @return - $text, the question text
  */
  public function getText()
  {
    return $this->text;
  }
}
?>