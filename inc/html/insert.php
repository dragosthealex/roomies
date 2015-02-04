<?php
/*
We assume that we already have $con.
To be used just included in another file
displays a form that lets us insert a new question in the db
*/
// Check if question submitted
if(isset($_POST['question_text']))
{
  $question_text = htmlentities($_POST['question_text']);
  $answers = array();

  $errMsg = '';

  if($question_text != addcslashes($_POST['question_text'], "'"))
  {
    $errMsg .= 'Invalid characters';
  }

  if(!$errMsg)
  {
    for($value=0; $value<8; $value++)
    {
      if(isset($_POST['answer'.$value]) && ($_POST['answer'.$value]))
      {
        array_push($answers, addcslashes($_POST['answer'.$value], "'"));
      }
    }
  }

  if(!$question_text)
  {
    $errMsg .= 'Invalid question ';
  }

  if(!$errMsg)
  {
    $stmt = $con->prepare("INSERT INTO rquestionsmap (question_text) VALUES ('$question_text')");
    $stmt->execute();
    
    $stmt =$con->prepare("SELECT question_id FROM rquestionsmap ORDER BY question_id DESC");
    $stmt->execute();
    $stmt->bindColumn(1, $question_id);
    $stmt->fetch();

    $question_to_add = "question".$question_id;

    $stmt = $con->prepare("ALTER TABLE ruser_qa ADD $question_to_add VARCHAR(50)");
    $stmt->execute();

    foreach ($answers as $value => $answer)
    {
      $stmt = $con->prepare("INSERT INTO ranswers (answer_question_id, answer_text) VALUES ($question_id, '$answer')");
      $stmt->execute();
    }
  }

  $stmt = null;

  if(isset($errMsg))
  {
    echo $errMsg;
  }
}
?>
<form class="form" method="POST" action="">
  <p>
    Question text:
  </p>
  <input class="input block" name="question_text" type="text"></input>
  <p>
    Number of answers:
  </p>
    <select class="select has-submit" name="no_answers">
      <option class="option" value="">Select</option>
      <option class="option" value="1">1</option>
      <option class="option" value="2">2</option>
      <option class="option" value="3">3</option>
      <option class="option" value="4">4</option>
      <option class="option" value="5">5</option>
      <option class="option" value="6">6</option>
      <option class="option" value="7">7</option>
      <option class="option" value="8">8</option>
    </select>
  <p>
    Question answers:
  </p>
  <input class="input block" name="answer0" type="text"></input>
  <input class="input block" name="answer1" type="text"></input>
  <input class="input block" name="answer2" type="text"></input>
  <input class="input block" name="answer3" type="text"></input>
  <input class="input block" name="answer4" type="text"></input>
  <input class="input block" name="answer5" type="text"></input>
  <input class="input block" name="answer6" type="text"></input>
  <input class="input block" name="answer7" type="text"></input>
  <input class="input block" name="answer8" type="text"></input>
  <input class="input-button block" type="submit"></input>
</form>