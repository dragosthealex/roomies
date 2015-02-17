<?php
/*
Gets user  ID of other user through AJAX. Update percentages in table.
*/
require_once "../../inc/init.php";

if(!LOGGED_IN || !$_GET['id'] || !isset($_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus'
  || !isset($_GET['q_no']) || !isset($_GET['q_ans']) || !isset($_GET['q_acc']) || !isset($_get['q_imp']))
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

if(!is_numeric($_GET['id']))
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

$id = $user->getIdentifier('id');
$otherUserId = htmlentities($_GET['id']);
$city = $user->getIdentifier('city');
$questionNo = htmlentities($_GET['q_no']);
$myAnswer = htmlentities($_GET['q_ans']);
$myAccepted = htmlentities($_GET['q_acc']);
$myAccepted = explode(",", $myAccepted);
$myImportance = htmlentities($_GET['q_imp']);

$stmt = $con->prepare("SELECT percentage_user_id1, percentage_user_id2, id1_1, id1_10, id1_50, id2_1, id2_10, id2_50, id1_max, id2_max
                        FROM rpercentages
                        WHERE percentage_city = '$city' 
                        AND (percentage_user_id1 = $id OR percentage_user_id2 = $id)");
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
      $otherAnswer = $otherUserQuestionInfo[0];
      $otherAccepted = explode(',', $otherUserQuestionInfo[1]);
      $otherImportance = $otherUserQuestionInfo[2];

      // A new question was answered, so update the max
      $match['id2_max'] += $myImportance;

      // Check if their answer is between my accepted
      if(in_array($otherAnswer, $myAccepted) && $myImportance != '0')
      {
        $match['id2_'.$myImportance]++;
      }
      // Calculate the new score that represents how much would I like them
      if($match['id2_max'])
      {
        $id2Score = ($match['id2_1'] + $match['id2_10']*10 + $match['id2_50']*50)/$match['id2_max'] + 0.01;
      }
      //////// NOW FOR ME ///////

      $match['id1_max'] += $otherImportance;

      // Check if their answer is between my accepted
      if(in_array($myAnswer, $otherAccepted) && $otherImportance != '0')
      {
        $match['id1_'.$otherImportance]++;
      }
      if($match['id1_max'])
      {
        $id1Score = ($match['id1_1'] + $match['id1_10']*10 + $match['id1_50']*50)/$match['id1_max'] + 0.01;
      }

      $percentage = sqrt($id2Score * $id1Score)*100;

      $stmt2 = $con->prepare("UPDATE rpercentages SET id1_1='$match[id1_1]', id1_10='$match[id1_10]', percentage='$percentage',
                              id1_50='$match[id1_50]', id2_1='$match[id2_1]', id2_10='$match[id2_10]', 
                              id2_50='$match[id2_50]', id1_max='$match[id1_max]', id2_max='$match[id2_max]'
                              WHERE percentage_user_id1 = $id AND percentage_user_id2 = $id2");
      $stmt2->execute();
    }
  }
  else
  {
    $id2 = $match['percentage_user_id1'];
    $otherUser = new User($con, $id2);
    $otherUserQuestionInfo = $otherUser->getQuestionAnswer($questionNo);

    if($otherUserQuestionInfo)
    {
      $otherAnswer = $otherUserQuestionInfo[0];
      $otherAccepted = explode(',', $otherUserQuestionInfo[1]);
      $otherImportance = $otherUserQuestionInfo[2];

      // A new question was answered, so update the max
      $match['id1_max'] += $myImportance;

      // Check if their answer is between my accepted
      if(in_array($otherAnswer, $myAccepted) && $myImportance != '0')
      {
        $match['id1_'.$myImportance]++;
      }
      // Calculate the new score that represents how much would I like them
      if($match['id1_max'])
      {
        $id1Score = ($match['id1_1'] + $match['id1_10']*10 + $match['id1_50']*50)/$match['id1_max'] + 0.01;
      }
      //////// NOW FOR OTHER USER ///////

      $match['id2_max'] += $otherImportance;

      // Check if their answer is between my accepted
      if(in_array($myAnswer, $otherAccepted) && $otherImportance != '0')
      {
        $match['id2_'.$otherImportance]++;
      }
      if($match['id2_max'])
      {
        $id2Score = ($match['id2_1'] + $match['id2_10']*10 + $match['id2_50']*50)/$match['id2_max'] + 0.01;
      }

      $percentage = sqrt($id2Score * $id1Score)*100;

      $stmt2 = $con->prepare("UPDATE rpercentages SET id1_1='$match[id1_1]', id1_10='$match[id1_10]', percentage='$percentage',
                              id1_50='$match[id1_50]', id2_1='$match[id2_1]', id2_10='$match[id2_10]', 
                              id2_50='$match[id2_50]', id1_max='$match[id1_max]', id2_max='$match[id2_max]'
                              WHERE percentage_user_id1 = $id AND percentage_user_id2 = $id2");
      $stmt2->execute();
    }
  }
}

?>