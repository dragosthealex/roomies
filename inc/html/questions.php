<?php
/* We assume that we already have the $con variable.
This file is to be included, not used by itself
*/
// Include the question class
include __ROOT__."/inc/classes/question.php";

$stmt = $con->prepare("SELECT value FROM rsiteinfo WHERE info = 'no_questions'");
$stmt->execute();
$stmt->bindColumn(1, $no_questions);
$stmt->fetch();
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
      foreach ($answers as $value => $answer)
      {
        echo "<li class='li'>".$value.". ".$answer."</li>";
      }
      echo "</ul></td></tr>";
    }
    ?>
  </tbody>
</table>