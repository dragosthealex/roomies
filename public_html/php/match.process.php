<?php
/*
Gets user  ID of other user through AJAX. Update percentages in table.
*/
require_once "../../inc/init.php";
$headers = getallheaders();

if(!LOGGED_IN || !$_GET['id'] || !isset($headers['roomies']) || $headers['roomies'] != 'cactus'
  || !isset($_GET['q_no']) || !isset($_GET['q_ans']) || !isset($_GET['q_acc']) || !isset($_get['q_imp']))
{
  //include __ROOT__."/inc/html/notfound.php";
  //exit();
}

if(!is_numeric($_GET['id']))
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

$otherUserId = htmlentities($_GET['id']);
$city = $user->getIdentifier('city');
$questionNo = htmlentities($_GET['q_no']);
$myAnswer = htmlentities($_GET['q_ans']);
$myAccepted = htmlentities($_GET['q_acc']);
$myAccepted = explode(",", $questionAccepted);
$myImportance = htmlentities($_GET['q_imp']);

$stmt = $con->prepare("SELECT percentage_user_id1, percentage_user_id2, id1_1, id1_10, id1_50, id2_1, id2_10, id2_50, id1_max, id2_max
                        WHERE percentage_city = '$city' 
                        AND percentage_user_id1 = $id OR percentage_user_id2 = $id");
$stmt->execute();

while($match = $stmt->fetch(PDO::FETCH_ASSOC))
{
  if($match['percentage_user_id1'] == $id)
  {
    $id2 = $match['percentage_user_id2'];
    $otherUser = new User($con, $id2);
    $otherUserQuestionInfo = $otherUser->getQuestionAnswer($questionNo);
    if($otherUserQuestionInfo)
    {
      $otherUserQuestionInfo = explode(":", $otherUserQuestionInfo);
      $otherAnswer = $otherUserQuestionInfo[0];
      $otherAccepted = explode(",", $otherUserQuestionInfo[1]);
      $otherImportance = $otherUserQuestionInfo[2];

      // A new question was answered, so update the max
      $match['id2_max'] += $myImportance;

      // Check if their answer is between my accepted
      if(in_array($otherAnswer, $myAccepted))
      {
        $match['id2_'.$myImportance]++;
      }
      $id2Score = ($match['id2_1'] + $match['id2_10']*10 + $match['id2_50']*50)/$match['id2_max'];

      //////// NOW FOR ME ///////

      $match['id1_max'] += $otherImportance;

      // Check if their answer is between my accepted
      if(in_array($myAnswer, $otherAccepted))
      {
        $match['id2_'.$otherImportance]++;
      }
      $id1Score = ($match['id1_1'] + $match['id1_10']*10 + $match['id1_50']*50)/$match['id1_max'];

      $percentage = sqrt($id2Score * $id1Score)*100;

      $stmt = $con->prepare("UPDATE rpercentages SET id1_1='$match[id1_1]', id1_10='$match[id1_10]',
                              id1_50='$match[id1_50]', id2_1='$match[id2_1]', id2_10='$match[id2_10]', 
                              id2_50='$match[id2_50]', id1_max='$match[id1_max]', id2_max='$match[id2_max]'
                              WHERE percentage_user_id1 = $id AND percentage_user_id2 = $id2");
      $stmt->execute();
    }
  }
  else
  {

  }
}

?>