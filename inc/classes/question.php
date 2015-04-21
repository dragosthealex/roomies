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
        $this->answersForThem = explode(",", $questionInfo[1]);
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
  * Function setAnswers($answerForMe, $answersForThem, $importance)
  *
  * Sets the answers and updates the database accordingly.
  *
  * @param - $answerForMe, the answer appliable for the user
  * @param - $answersForThem, the answer appliable to others, array
  * @param - $importance, the importance of the question
  */
  function setAnswers($answerForMe, $answersForThem, $importance)
  {
    $userId = $this->userId;
    $con = $this->con;
    $id = $this->id;

    // $answerForMe and $answersForThem are the int values. We get the text values and
    // assign them to the variables
    $this->answerForMe = $answerForMe;
    $this->answersForThem = $answersForThem;
    $this->importance = $importance;

    // Construct the string
    if(isset($answersForThem[1]))
    {
      $answersForThem = implode(",", $answersForThem);
    }
    else
    {
      $answersForThem = $answersForThem['0'];
    }
    $questionInfo = "$answerForMe:$answersForThem:$importance";

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
  public function toString($otherUser=null, $currentUser=null)
  {
    if (!($otherUser instanceof OtherUser))
    {
      $otherUser = null;
    }
    if (!($currentUser instanceof CurrentUser))
    {
      $currentUser = null;
    }
    if (is_null($otherUser) != is_null($currentUser))
    {
      throw new Exception("Either both or neither required: currentUser and otherUser", 1);
    }
    // Localise stuff
    $con = $this->con;
    $answers = $this->answers;
    $text = $this->text;
    $id = $this->id;
    $answered = isset($this->answerForMe) && $this->answerForMe;

    $answerClass = $answered ? 'answered' : 'unanswered';

    if(!$answered && $this->userId != $_SESSION['user']['id'])
    {
      return '';
    }

    $outputAnswerForm = is_null($otherUser) || (!is_null($currentUser)
                                                && $currentUser->getQuestionAnswer($id) == '');

    if ($outputAnswerForm && !is_null($currentUser))
    {
      $question = new Question($con, $id, $currentUser->getCredential('id'));
      return $question->toString();
    }

    if ($outputAnswerForm)
    {
      $question =
      "
      <div class='question $answerClass'>
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
            <div class='indented-section'>
      ";

      $disabled = ($answered || $this->userId != $_SESSION['user']['id']) ? 'disabled' : '';
      $answerForMe = $this->answerForMe;
      $answersForThem = $this->answersForThem;
      $importance = $this->importance;

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
        $checked = $answerId == $answerForMe ? 'checked' : '';
        $question .=
        "
                <label for='q_{$id}_ans_{$count}' class='cr-label'>
                  <input $disabled $checked type='radio' id='q_{$id}_ans_{$count}' name='q_ans' class='cr' value='{$answerId}' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button r-button'></span>
                  <span class='cr-text'>{$answerText}</span>
                </label>
        ";
        $count ++;
      }
      $question .=
      "
              </div>
              <h4 class='h4'>Answer(s) you&rsquo;ll accept</h4>
              <div class='indented-section'>
      ";

      $count = 1;
      $allAnswersAccepted = $answered;
      foreach ($answers as $answerId => $answer)
      {
        /*
        We set the name for the radio buttons to be unique "accepting_for_q_$questionId_$answerId"
        */
        $answerText = $answer->getText();
        $answerId = $answer->getId();
        $checked = in_array($answerId, $answersForThem);
        $allAnswersAccepted = $allAnswersAccepted && $checked;
        $checked = $checked ? 'checked' : '';
        $question .=
        "
                <label for='q_{$id}_acc_{$count}' class='cr-label'>
                  <input $disabled $checked type='checkbox' id='q_{$id}_acc_{$count}' name='q_acc_{$count}' class='cr' value='{$answerId}' onchange='var i,b=true;for(i=1;i<={$totalCount};i++)b=b&&document.getElementById(\"q_{$id}_acc_\"+i).checked;document.getElementById(\"q_{$id}_imp_0\").checked=(i=document.getElementById(\"q_{$id}_acc_0\")).checked=b;b&&i.onchange();checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button'></span>
                  <span class='cr-text'>{$answerText}</span>
                </label>
        ";
        $count ++;
      }

      $allChecked  = $answered && $allAnswersAccepted ? 'checked' : '';
      $imp0Checked = $answered && $importance == 0    ? 'checked' : '';
      $imp1Checked = $answered && $importance == 1    ? 'checked' : '';
      $imp2Checked = $answered && $importance == 10   ? 'checked' : '';
      $imp3Checked = $answered && $importance == 50   ? 'checked' : '';

      $question .=
      "
                <label for='q_{$id}_acc_0' class='cr-label'>
                  <input $disabled $allChecked type='checkbox' id='q_{$id}_acc_0' class='cr' onchange='var i,e;for(i=1;i<{$count};i++)(((e=document.getElementById(\"q_{$id}_acc_\"+i)).checked=this.checked),(e.disabled=this.checked));document.getElementById(\"q_{$id}_imp_0\").checked=this.checked;checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button'></span>
                  <span class='cr-text'>Any of the above</span>
                </label>
              </div>
              <input type='hidden' name='q_acc' id='q_{$id}_acc'>
              <h4 class='h4'>Importance</h4>
              <input $disabled $imp0Checked type='radio' name='q_imp' id='q_{$id}_imp_0' class='cp-0' value='0'>
              <div class='indented-section'>
                <p class='text'>Irrelevant</p>
                <p class='text cr-text-faded'>(Because you&rsquo;ll accept any answer, this question is marked irrelevant)</p>
              </div>
              <div class='indented-section'>
                <label for='q_{$id}_imp_1' class='cr-label cp-label cp-left'>
                  <input $disabled $imp1Checked type='radio' name='q_imp' id='q_{$id}_imp_1' class='cr' value='1' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button'></span>
                  <span class='cr-text'>A little</span>
                </label
                ><label for='q_{$id}_imp_2' class='cr-label cp-label'>
                  <input $disabled $imp2Checked type='radio' name='q_imp' id='q_{$id}_imp_2' class='cr' value='10' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button'></span>
                  <span class='cr-text'>Somewhat</span>
                </label
                ><label for='q_{$id}_imp_3' class='cr-label cp-label cp-right'>
                  <input $disabled $imp3Checked type='radio' name='q_imp' id='q_{$id}_imp_3' class='cr' value='50' onchange='checkForm(this,{$id},{$totalCount})'>
                  <span class='cr-button'></span>
                  <span class='cr-text'>Very</span>
                </label>
              </div>
              <input $disabled type='button' class='input-button' data-ajax-url='../php/match.process.php?q_no={$id}' data-ajax-post='q_{$id} q_ans q_acc q_imp' data-ajax-hide='q_$id' value='Answer'>
              <input type='button' class='input-button cancel-button' data-toggle='q_$id' value='Cancel'>
            </form>
          </div>
        </div>
      ";
    }
    else
    {
      $theirAnswer = new Answer($con, $this->answerForMe);
      $theirAnswerText = $theirAnswer->getText();
      $yourQuestion = new Question($con, $id, $currentUser->getCredential('id'));
      $yourAnswer = new Answer($con, $yourQuestion->getQuestionInfo()[0]);
      $yourAnswerText = $yourAnswer->getText();
      $yourImportance = $yourQuestion->getQuestionInfo()[2];
      $theirImportance = $this->getQuestionInfo()[2];
      $yourImportance = ($yourImportance==1)?"A little":($yourImportance==10)?"Somewhat":($yourImportance==50)?"Very Important":"Irrelevant";
      $theirImportance = ($theirImportance==1)?"A little":($theirImportance==10)?"Somewhat":($theirImportance==50)?" Very Important":"Irrelevant";


      $question = "
      <div class='question answered'>
        <div class='box-padding'>
          <h3 class='h3'>$text</h3>
          <p class='text'>They answered: $theirAnswerText | $theirImportance</p>
          <p class='text'>You answered: $yourAnswerText | $yourImportance</p>

        </div>
      </div>";
    }
    return $question;
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
      $answersForThem = $this->answersForThem;
      $answerForMe = $this->answerForMe;
      $importance = $this->importance;
      return array("$answerForMe", $answersForThem, "$importance");
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