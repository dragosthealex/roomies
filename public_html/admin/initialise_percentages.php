<?php
/*
This script makes the initialisation for every user in the db.
It checks whether there are rows for every two users in the same city. If there aren't,
a new row is inserted, with the percentage initialised to 0 (to be edited) and values for 
both importances to 0.
*/

require_once '../../inc/init.php';

// Find out how many cities we have in db
$stmt = $con->prepare("SELECT filter_value FROM rfiltersmap WHERE map_uni_city != ''");
$stmt->execute();
$no_cities = $stmt->rowCount();

echo "cities: $no_cities <br>";

for($city=1; $city<=$no_cities; $i++)
{
  // Select all users for the current city
  $stmt = $con->prepare("SELECT profile_filter_id FROM rdetails WHERE uni_city = $city");
  $stmt->execute();
  $usersInCity = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

  // We want to check that for every two users we have a row
  for($i=0; $i<count($usersInCity)-1; $i++)
  {
    for($j=$i+1; $j<count($usersInCity); $j++)
    {
      $idOfUser1 = $usersInCity[$i];
      $idOfUser2 = $usersInCity[$j];

      $stmt2 = $con->prepare("SELECT percentage FROM rpercentages
                              WHERE (percentage_user_id1=$idOfUser1 AND percentage_user_id2=$idOfUser2)
                              OR (percentage_user_id1=$idOfUser2 AND percentage_user_id2=$idOfUser1)");
      $stmt2->execute();

      // If this row is not found, insert a new one;
      if(!$stmt2->rowCount())
      {
        $stmt2 = $con->prepare("INSERT INTO rpercentages (percentage_user_id1, percentage_user_id2, percentage_city)
                                VALUES ($idOfUser1, $idOfUser2, '$city')");
        $stmt2->execute();
      }
    }
  }
}
?>