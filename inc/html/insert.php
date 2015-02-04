<?php
/*
We assume that we already have $con.
To be used just included in another file
displays a form that lets us insert a new question in the db
*/
// Check if question submitted
if(isset($_POST['question_text'], $_POST['no_answers']))
{
  $no_answers = htmlentities($_POST['no_answers']);
  $question_text = htmlentities($_POST['question_text']);
  $answers = array();

  if($question_text != htmlentities($_POST['question_text']))
  {
    $errMsg = "Invalid characters ";
  }

  for($value=0; $value<$no_answers; $value++)
  {
    array_push($answers, htmlentities($_POST['answer'.$value]));
    if($answers["$value"] == "")
    {
      $errMsg .= "Invalid answer ";
      break;
    }
  }

  if(!$question_text)
  {
    $errMsg .= "Invalid question ";
  }
  $stmt = $con->prepare("INSERT INTO rquestionsmap (question_text) VALUES ('$question_text')");
  $stmt->execute();
  
  $stmt =$con->prepare("SELECT question_id FROM rquestionsmap ORDER BY question_id DESC LIMIT 1");
  $stmt->execute();
  $stmt->bindColumn(1, $question_id);
  $stmt->fetch();

  $stmt = $con->prepare("ALTER TABLE rusers_qa ADD question".$question_id." VARCHAR(50)");
  $stmt->execute();

  foreach ($$answers as $value => $answer)
  {
    $stmt = $con->prepare("INSERT INTO ranswers (answer_question_id, answer_text) VALUES ($question_id, '$answer')");
    $stmt->execute();
  }

  $stmt = $con->prepare("UPDATE rsiteinfo SET no_questions=no_questions+1");
  $stmt->execute();

  $stmt = null;
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
      <option class="option" value="">1</option>
      <option class="option" value="">2</option>
      <option class="option" value="">3</option>
      <option class="option" value="">4</option>
      <option class="option" value="">5</option>
      <option class="option" value="">6</option>
      <option class="option" value="">7</option>
      <option class="option" value="">8</option>
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
  <input class="input button block" type="submit"></input>
</form>