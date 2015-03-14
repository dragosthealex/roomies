<?php
// If not get submit, use default search
// DISCLAIEMER : DONE AT 4 AM
$errorMsg = '';
try
{
  if(!isset($_GET['submit']) || !$_GET['submit'])
  {
    $id = $user2->getCredential('id');
    $userGender = $user2->getCredential('gender');
    $userLanguage = $user2->getCredential('language');
    $userNationality = $user2->getCredential('country');
    $birthday = $user2->getCredential('birthday');
    $city = $user2->getCredential('uni_city');
    $birthday = explode("-", $birthday);
    $lowerBday = ($birthday[0] - 2) . '-' . $birthday[1] . '-' . $birthday[2];
    $upperBday = ($birthday[0] + 2) . '-' . $birthday[1] . '-' . $birthday[2];

    $condition = "language='$userLanguage' AND country='$userNationality' AND birthday<'$upperBday' AND birthday>'$lowerBday' AND uni_city='$city'";
  }
  else
  {
    // Dynamically construct the condition array
    $condition = '';
    foreach ($_GET as $key => $value)
    {
      if($key != $submit && $key != $upperAge && $key != $lowerAge)
      {
        $condition .= "$key='$value' AND ";
      }
      if($key == 'upperAge')
      {
        $bYear = date('Y') - $value;
        $upperBday = $bYear . '-00-00';
        $condition .= "birthday<'$upperBday' AND ";
      }
      if($key == 'lowerAge')
      {
        $bYear = date('Y') - $value;
        $lowerBday = $bYear . '-00-00';
        $condition .= "birthday>'$lowerBday' AND ";
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
    $match['id'] = ($id1 == $id) ? $id2 : $id1;
    $match['percentage'] = $percentage;
    echo $match['id'] . " " . $match['percentage'] . "<br>";
    array_push($matches, $match);
  }
}
catch (Exception $e)
{
  $errorMsg = $e->getMessage();
}
echo $errorMsg;
?>