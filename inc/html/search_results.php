<?php
// If not get submit, use default search
// DISCLAIEMER : DONE AT 4 AM
$errorMsg = '';
try
{
  if(!count($_GET))
  {
    $id = $user2->getCredential('id');
    $userGender = $user2->getCredential('gender');
    $userLanguage = $user2->getCredential('language');
    $birthday = date_create($user2->getCredential('birthday'));
    $city = $user2->getCredential('uni_city');
    $year = date_format($birthday, 'Y');
    $lowerYear = $year + 2;
    $upperYear = $year - 2;
    $eighteenYears = date('Y') - 18;
    if ($eighteenYears < $lowerYear)
    {
      $lowerYear = $eighteenYears;
    }
    $month = date_format($birthday, 'm');
    $day = date_format($birthday, 'd');
    $lowerBday = $lowerYear . '-' . $month . '-' . $day;
    $upperBday = $upperYear . '-' . $month . '-' . $day;

    $condition = "gender='$userGender' AND language='$userLanguage' AND birthday>='$upperBday' AND birthday<='$lowerBday' AND uni_city='$city'";
  }
  else
  {
    // Dynamically construct the condition array
    $condition = '';
    foreach ($_GET as $key => $value)
    {
      if ($value == 0) continue;
      switch ($key) {
        case 'online_last':
          // TODO
          break;

        case 'upperAge':
          $bYear = date('Y') - $value;
          $month = date('m');
          $day = date('d');
          $upperBday = $bYear . "-$month-$day";
          $condition .= "birthday>='$upperBday' AND ";
          break;

        case 'lowerAge':
          $bYear = date('Y') - $value;
          $month = date('m');
          $day = date('d');
          $lowerBday = $bYear . "-$month-$day";
          $condition .= "birthday<='$lowerBday' AND ";
          break;

        default:
          $condition .= "$key='$value' AND ";
          break;
      }
    }
    $condition .= "1=1";
  }

  // Select the thingies form db
  $stmt = $con->prepare("SELECT percentage, percentage_user_id1, percentage_user_id2 FROM rpercentages
                          WHERE (percentage_user_id1 = ANY (SELECT profile_filter_id FROM rdetails 
                                                        WHERE $condition)
                                AND percentage_user_id2 = $id)
                            OR  (percentage_user_id2 = ANY (SELECT profile_filter_id FROM rdetails 
                                                        WHERE $condition)
                                AND percentage_user_id1 = $id)");

  if(!$stmt->execute())
  {
    throw new Exception("fuck you fabulous bitch! in the ass without lube", 1);
  }
  $stmt->bindColumn(1, $percentage);
  $stmt->bindColumn(2, $id1);
  $stmt->bindColumn(3, $id2);

  // Make matches array
  $matches = array();
  while($stmt->fetch())
  {
    // The other user id is the id that is not ours
    $leOtherUser = new OtherUser($con, ($id1 == $id) ? $id2 : $id1);
    if ($leOtherUser->getError()) continue;
    $match['percentage'] = $percentage;
    echo '<h4 class=h4>'.$leOtherUser->getName($user2->friendShipStatus($leOtherUser)).'</h4><p class=text>'.$match['percentage']."%</p>";
    array_push($matches, $match);
  }
}
catch (Exception $e)
{
  $errorMsg = $e->getMessage();
}
echo $errorMsg;
?>