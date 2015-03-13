<?php
// HAVE TO COMPLETE QUESTION TOSTRING WITH PROPER CLASS NAMES
include __ROOT__."/inc/classes/answer.php";
/**
* Question class
*
* Represents a question. Has question text, question answers (with text and values), has importance
*
*/
include_once 'Base.php';
class Question extends Base
{
  // The text body of the question
  private $text;
  // The array containing the answers (strings)
  private $answers = array();
  // The answer chosen by user
  private $answerForMe = '';
  // The answers wanted for others, chosen by user. array
  private $answersForThem = array();
  // The importance chosen by user
  private $importance;
  // The user id;
  private $userId;
  // The answers as string array
  private $questionInfo = '';

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
    $this->id = $id;

    // Get the question answer
    $stmt = $con->prepare("SELECT question_text, question_answers FROM rquestionsmap WHERE question_id = $id");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting the text and answers for question $id", 1);
      }
      $stmt->bindColumn(1, $text);
      $stmt->bindColumn(2, $answerIds);
      $stmt->fetch();

      $this->text = $text;
      $answers = array();
      $answerIds = explode(":", $answerIds);
      
      foreach($answerIds as $answerId)
      {
        $answer = new Answer($con, $answerId);
        if($answer->getError())
        {
          throw new Exception("Error with answer $answerId in question $id", 1);
        }
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
        $stmt = $con->prepare("SELECT question".$id." FROM ruser_qa WHERE answer_user_id = ".$userId);
        if(!$stmt->execute())
        {
          throw new Exception("Error getting questions for user $userId", 1);
        }
        $stmt->bindColumn(1, $questionInfo);
        $stmt->fetch();
      }
      else
      {
        $questionInfo = '';
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
      $this->questionInfo = $questionInfo;
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }

    $stmt = null;
  }

  /**
  * Function getInfo()
  *
  * Returns the info string
  *
  * @return - $info, the string
  */
  public function getInfo()
  {
    return $this->questionInfo;
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
    // Localise stuff
    $con = $this->con;
    $answers = $this->answers;
    $text = $this->text;
    $id = $this->id;

    if(isset($this->answerForMe) && $this->answerForMe)
    {
      // Get the text of the answer for me
      $forMe = new Answer($con, $this->answerForMe);
      $forMe = $forMe->getText();

      $forThem = $this->answersForThem;
      $forThem = isset($forThem[0])?$forThem:explode(",", $forThem);

      // Get the text of the answers for them
      $forThemText = array();
      foreach ($forThem as $answerId)
      {
        $acceptedAnswer = new Answer($con, $answerId);
        array_push($forThemText, $acceptedAnswer->getText());
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
          My Answer:
          <div class='answer answered'>
            <p class='text'>
              $forMe
            </p>
          </div>
        </div>
        <div class='question-answers for-others'>
          Answers I accept:
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
            Importance:
            <p class='text'>$importance</p>
          </div>
        </div>
      ";
      return $question;
    }
    else
    {
      $question = 
      "
        <div class='question'>
          <script>window.checkForm=function(e,i,c,p,q,r,j,z,y){
            p=q=r=!1;e=e.parentNode.parentNode.parentNode;
            for(j=1;j<=c;j++)p=p||document.getElementById('q_'+i+'_ans_'+j).checked;
            y=document.getElementById('q_'+i+'_acc');y.value='';
            for(j=0;j<=c;j++)(z=document.getElementById('q_'+i+'_acc_'+j),j&&z.checked&&(y.value=y.value?y.value.split(',').concat(z.value).join(','):z.value),q=q||z.checked);
            for(j=0;j<=3;j++)r=r||document.getElementById('q_'+i+'_imp_'+j).checked;
            e.children[e.children.length-2].disabled=!(p&&q&&r);
          }</script>
          <div class='box-padding'>
            <h3 class='h3' data-toggle='q_$id'>$text</h3>
            <form id='q_$id' class='hidden'>
      ";
      $count = 1;
      $totalCount = count($answers);
      foreach ($answers as $answer)
      {
        /*
        The answers to different q can have the same id i.e. the answer 'yes' has the id 1 everywhere.
        So we need to set the answers id attribute with a unique count.
        We set the name for the radio buttons to be "q_{$questionId}_ans"
        */
        $answerText = $answer->getText();
        $answerId = $answer->getId();
        $question .=
        "
              <div class='cr-block'>
                <label for='q_{$id}_ans_{$count}' class='cr-label'>
                  <input type='radio' id='q_{$id}_ans_{$count}' name='q_ans' class='cr' value='{$answerId}' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button r-button'></span>
                  <span class='cr-text'>{$answerText}</span>
                </label>
              </div>
        ";      
        $count ++;
      }
      $question .=
      "
              <h4 class='h4'>Answer(s) you&rsquo;ll accept</h4>
      ";
      $count = 1;
      foreach ($answers as $answerId => $answer)
      {
        /*
        We set the name for the radio buttons to be unique "accepting_for_q_$questionId_$answerId"
        */
        $answerText = $answer->getText();
        $answerId = $answer->getId();
        $question .=
         "
              <div class='cr-block'>
                <label for='q_{$id}_acc_{$count}' class='cr-label'>
                  <input type='checkbox' id='q_{$id}_acc_{$count}' name='q_acc_{$count}' class='cr' value='{$answerId}' onchange='var i,b=true;for(i=1;i<={$totalCount};i++)b=b&&document.getElementById(\"q_{$id}_acc_\"+i).checked;document.getElementById(\"q_{$id}_imp_0\").checked=(i=document.getElementById(\"q_{$id}_acc_0\")).checked=b;b&&i.onchange();checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button'></span>
                  <span class='cr-text'>{$answerText}</span>
                </label>
              </div>
         ";
        $count ++;
      }
      $question .=
      "
              <div class='cr-block'>
                <label for='q_{$id}_acc_0' class='cr-label block'>
                  <input type='checkbox' id='q_{$id}_acc_0' class='cr' onchange='var i,e;for(i=1;i<{$count};i++)(((e=document.getElementById(\"q_{$id}_acc_\"+i)).checked=this.checked),(e.disabled=this.checked));document.getElementById(\"q_{$id}_imp_0\").checked=this.checked;checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button'></span>
                  <span class='cr-text'>Any of the above</span>
                </label>
              </div>
              <input type='hidden' name='q_acc' id='q_{$id}_acc'>
              <h4 class='h4'>Importance</h4>
              <input type='radio' name='q_imp' id='q_{$id}_imp_0' class='cp-0' value='0'>
              <div class='cr-block'><div class='cr-label'><div class='cr-text'>Irrelevant</div></div></div>
              <div class='cr-block'>
                <label for='q_{$id}_imp_1' class='cr-label cp-label cp-left'>
                  <input type='radio' name='q_imp' id='q_{$id}_imp_1' class='cr' value='1' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button cp-button'></span>
                  <span class='cr-text'>A little</span>
                </label
                ><label for='q_{$id}_imp_2' class='cr-label cp-label'>
                  <input type='radio' name='q_imp' id='q_{$id}_imp_2' class='cr' value='10' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button cp-button'></span>
                  <span class='cr-text'>Somewhat</span>
                </label
                ><label for='q_{$id}_imp_3' class='cr-label cp-label cp-right'>
                  <input type='radio' name='q_imp' id='q_{$id}_imp_3' class='cr' value='50' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button cp-button'></span>
                  <span class='cr-text'>Very</span>
                </label>
              </div>
              <input disabled type='button' class='input-button' data-ajax-url='../php/match.process.php?q_no={$id}' data-ajax-post='q_{$id} q_ans q_acc q_imp' value='Answer'>
              <input type='button' class='input-button cancel-button' data-toggle='q_$id' value='Cancel'>
            </form>
          </div>
        </div>
      ";
      //Add a submit button after changing into a form
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