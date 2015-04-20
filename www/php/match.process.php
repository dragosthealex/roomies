<?php
/*
Script that updates/inserts a question row for the current user
After this, it updates all the percentages containing this user
Gets the following vars

$_GET['q_no'] // the question number
$_POST['q_ans'] // the answer id
$_POST['imp'] // the importance
$_POST['acc'] // the accepted answers, as a comma sepparated string (to be turned into array)
*/
require_once "../../inc/init.php";

if(!LOGGED_IN || !isset($_GET['q_no'], $_POST['q_ans'], $_POST['q_acc'], $_POST['q_imp'], $_SERVER['HTTP_ROOMIES'])
              || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

header('Content-type: application/json');


$response= array();
$response['error'] = '';

// The currentUser's Id
$id = $user2->getCredential('id');
$city = $user2->getCredential('uni_city');
$questionNo = htmlentities($_GET['q_no']);

$myAnswer = htmlentities($_POST['q_ans']);
$myAccepted = htmlentities($_POST['q_acc']);
$myAccepted = explode(",", $myAccepted);
$myImportance = htmlentities($_POST['q_imp']);


// TODO: FIX BUG WHERE PERCENTAGE IS INCREASED EACH TIME YOU ANSWER THE SAME QEUSTION
// TODO: IMPLEMENT MARGIN

try
{
  // Validate the importance
  if(!in_array($myImportance, array('0', '1', '10', '50')))
  {
    throw new InvalidArgumentException("Error. Wrong importance. Sneaky, sneaky", 1);
  }

  // Make the question string to be inserted/updated
  $questionString = implode(":", array("$myAnswer", implode(",", $myAccepted), "$myImportance"));

  // Check if row with this user id is existing in ruser_qa
  $stmt = $con->prepare("SELECT question$questionNo FROM ruser_qa WHERE EXISTS
                          (SELECT * FROM ruser_qa WHERE answer_user_id = $id) AND answer_user_id = $id;
                         INSERT INTO ruser_qa (answer_user_id, question$questionNo) VALUES('$id', '$questionString')
                           ON DUPLICATE KEY UPDATE question$questionNo='$questionString'");

  if(!$stmt->execute())
  {
    throw new Exception("Error updating question $questionNo in database. I shit you not", 1);
  }
  $stmt->bindColumn(1, $oldQuestion);
  $stmt->fetch();

  // Get the old question
  $oldQuestion = $oldQuestion ? explode(':', $oldQuestion) : array();
  $oldMyAnswer = isset($oldQuestion[0]) ? $oldQuestion[0] : '';
  $oldMyAccepted = isset($oldQuestion[1]) ? explode(',', $oldQuestion[1]) : array();
  $oldMyImportance = isset($oldQuestion[2]) ? $oldQuestion[2] : '';
  
  // If it's the same answers, we don't need to update percentages
  if($oldMyAnswer == $myAnswer && implode(',',$oldMyAccepted) == implode(',', $myAccepted) && $oldMyImportance == $myImportance)
  {
    throw new LogicException("Nothing changed", 1);
  }

  // Recalculate all percentages
  $stmt = $con->prepare("SELECT percentage_user_id1, percentage_user_id2, id1_1, id1_10, id1_50, id2_1, id2_10, id2_50, id1_max, id2_max
                          FROM rpercentages
                          WHERE percentage_city = '$city' 
                          AND (percentage_user_id1 = $id OR percentage_user_id2 = $id)");
  
  if(!$stmt->execute())
  {
    throw new Exception("Error getting matching details from database. Surprise buttsecs", 1);
  }

  while($match = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    // If id1 is the current user, it means that the other user is id2
    if($match['percentage_user_id1'] == $id)
    {
      // Create an OtherUser with id2
      $id2 = $match['percentage_user_id2'];
      $otherUser = new OtherUser($con, $id2);
      // Check if we have any errors
      if($otherUser->getError())
      {
        $response['error'] .= "Error initialising user $id2: ".$otherUser->getError();
        continue;
      }

      // Find if the other user has responded to this question
      $otherUserQuestionInfo = $otherUser->getQuestionAnswer($questionNo);
      $otherAnswer = isset($otherUserQuestionInfo[0])?$otherUserQuestionInfo[0]:0;
      $otherAccepted = isset($otherUserQuestionInfo[1])?explode(',', $otherUserQuestionInfo[1]):'';
      $otherImportance = isset($otherUserQuestionInfo[2])?$otherUserQuestionInfo[2]:0;

      if($otherAnswer && $otherAccepted && $otherImportance)
      {
        // Substract the old importance and add the new one
        $match['id2_max'] = ($match['id2_max'] - $oldMyImportance) + $myImportance;

        // Check if their answer is between my new accepted but not my old accepted
        if(in_array($otherAnswer, $myAccepted) && !in_array($otherAnswer, $oldMyAccepted) && $myImportance != '0')
        {
          $match['id2_'.$myImportance]++;
        }
        // Check if their answar is not in my new accepted but was in my old accepted
        if(!in_array($otherAnswer, $myAccepted) && in_array($otherAnswer, $oldMyAccepted) && $myImportance != '0' && isset($oldMyAccepted[0]))
        {
          $match['id2_'.$myImportance]--;
        }

        // Calculate the new score that represents how much would I like them
        if($match['id2_max'])
        {
          $id2Score = ($match['id2_1'] + $match['id2_10']*10 + $match['id2_50']*50)/$match['id2_max'];
        }


        //////// NOW FOR OTHER ///////

        // If it's an already answered question, don't update other's importance, it's the same
        if(!$oldMyAnswer)
        {
          $match['id1_max'] += $otherImportance;
        }

        // Check if my new answer is between their accepted and my old one is not
        if(in_array($myAnswer, $otherAccepted) && !in_array($oldMyAnswer, $otherAccepted) && $otherImportance != '0')
        {
          $match['id1_'.$otherImportance]++;
        }
        // Check if my new answer is not in their accepted but my old was
        if(!in_array($myAnswer, $otherAccepted) && in_array($oldMyAnswer, $otherAccepted) && $otherImportance != '0' && $oldMyAnswer)
        {
          $match['id1_'.$otherImportance]--;
        }

        // Make sure that neither is 0
        $id2Score = isset($id2Score) && $id2Score ? $id2Score : 0.01;
        $id1Score = isset($id1Score) && $id1Score ? $id1Score : 0.01;
        $percentage = sqrt($id2Score * $id1Score)*100;

        $stmt2 = $con->prepare("UPDATE rpercentages SET id1_1='$match[id1_1]', id1_10='$match[id1_10]', percentage='$percentage',
                                id1_50='$match[id1_50]', id2_1='$match[id2_1]', id2_10='$match[id2_10]', 
                                id2_50='$match[id2_50]', id1_max='$match[id1_max]', id2_max='$match[id2_max]'
                                WHERE percentage_user_id1 = $id AND percentage_user_id2 = $id2");
        
        if(!$stmt2->execute())
        {
          throw new Exception("Error updating matching details with user $id2", 1);
        }
      }
    }
    else
    {
      // Create otherUser with id1
      $id2 = $match['percentage_user_id1'];
      $otherUser = new OtherUser($con, $id2);
       // Check if we have any errors
      if($otherUser->getError())
      {
        $response['error'] .= "Error initialising user $id2: ". $otherUser->getError();
        continue;
      }

      // Find if the other user has responded to this question
      $otherUserQuestionInfo = $otherUser->getQuestionAnswer($questionNo);
      $otherAnswer = isset($otherUserQuestionInfo[0])?$otherUserQuestionInfo[0]:'';
      $otherAccepted = isset($otherUserQuestionInfo[1])?explode(',', $otherUserQuestionInfo[1]):'';
      $otherImportance = isset($otherUserQuestionInfo[2])?$otherUserQuestionInfo[2]:'';
      

      if($otherAnswer && $otherAccepted && $otherImportance)
      {
        // Substract the old importance and add the new one
        $match['id1_max'] = ($match['id1_max'] - $oldMyImportance) + $myImportance;

        // Check if their answer is between my new accepted but not my old accepted
        if(in_array($otherAnswer, $myAccepted) && !in_array($otherAnswer, $oldMyAccepted) && $myImportance != '0')
        {
          $match['id1_'.$myImportance]++;
        }
        // Check if their answar is not in my new accepted but was in my old accepted
        if(!in_array($otherAnswer, $myAccepted) && in_array($otherAnswer, $oldMyAccepted) && $myImportance != '0' && isset($oldMyAccepted[0]))
        {
          $match['id1_'.$myImportance]--;
        }

        // Calculate the new score that represents how much would I like them
        if($match['id1_max'])
        {
          $id1Score = ($match['id1_1'] + $match['id1_10']*10 + $match['id1_50']*50)/$match['id1_max'];
        }
        //////// NOW FOR MY USER ///////

        // If it's an already answered question, don't update other's importance, it's the same
        if(!$oldMyAnswer)
        {
          $match['id2_max'] += $otherImportance;
        }

        // Check if my new answer is between their accepted and my old one is not
        if(in_array($myAnswer, $otherAccepted) && !in_array($oldMyAnswer, $otherAccepted) && $otherImportance != '0')
        {
          $match['id2_'.$otherImportance]++;
        }
        // Check if my new answer is not in their accepted but my old was
        if(!in_array($myAnswer, $otherAccepted) && in_array($oldMyAnswer, $otherAccepted) && $otherImportance != '0' && $oldMyAnswer)
        {
          $match['id2_'.$otherImportance]--;
        }

        if($match['id2_max'])
        {
          $id2Score = ($match['id2_1'] + $match['id2_10']*10 + $match['id2_50']*50)/$match['id2_max'];
        }

        // Make sure that neither is 0
        $id2Score = isset($id2Score) && $id2Score ? $id2Score : 0.01;
        $id1Score = isset($id1Score) && $id1Score ? $id1Score : 0.01;

        $percentage = (double)sqrt((double)$id2Score * (double)$id1Score)*100;

        $response['error'] = "id1 - $id1Score | id2 - $id2Score | perc - $percentage";
        
        
        $stmt2 = $con->prepare("UPDATE rpercentages SET id1_1='$match[id1_1]', id1_10='$match[id1_10]', percentage='$percentage',
                                id1_50='$match[id1_50]', id2_1='$match[id2_1]', id2_10='$match[id2_10]', 
                                id2_50='$match[id2_50]', id1_max='$match[id1_max]', id2_max='$match[id2_max]'
                                WHERE percentage_user_id1 = $id2 AND percentage_user_id2 = $id");

        // throw new Exception("UPDATE rpercentages SET id1_1='$match[id1_1]', id1_10='$match[id1_10]', percentage='$percentage',
        //                 id1_50='$match[id1_50]', id2_1='$match[id2_1]', id2_10='$match[id2_10]', 
        //                 id2_50='$match[id2_50]', id1_max='$match[id1_max]', id2_max='$match[id2_max]'
        //                 WHERE percentage_user_id1 = $id AND percentage_user_id2 = $id2", 1);

        if(!$stmt2->execute())
        {
          throw new Exception("Error updating matching details with user $id2", 1);
        }
      }
    }// else (if $matchPercentage['user_id1'] == $id)
  }// while (fetch)

  echo json_encode($response);
}
catch (LogicException $e)
{
  echo json_encode($response);
}
catch (Exception $e)
{
  $response['error'] .= $e->getMessage();
  echo json_encode($response);
}
?>