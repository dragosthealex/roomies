<?php
/* We assume that we already have the $con variable.
This file is to be included, not used by itself
*/
// Include the question class
//include __ROOT__."/inc/classes/question.php";

$stmt = $con->prepare("SELECT question_id FROM rquestionsmap");
$stmt->execute();
$no_questions = $stmt->rowCount();
?>
<table class="table questions-admin">
  <thead>
    <tr>
      <th class="th" style="width:100px;">
        Question no.
      </th>
      <th class="th">
        Question text
      </th>
      <th class="th">
        Answers
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
    for($i=1; $i<=$no_questions; $i++)
    {
      $question = new Question($con, $i, 0);
      $text = $question->getText();
      $answers = $question->getAllAnswers();

      echo "<tr><td class='td'>".$i."</td>";
      echo "<td class='td'>".$text."</td>";
      echo "<td class='td'><ul class='ul'>";
      $index = 1;
      foreach ($answers as $answer)
      {
        $answerText = $answer->getText();
        echo "<li class='li'>".$index.". ".$answerText."</li>";
        $index++;
      }
      echo "</ul></td></tr>";
    }
    ?>
  </tbody>
</table>