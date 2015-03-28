<?php
/*
This outputs the accommodations as LI elements, containing name, main photo and short desc
*/
echo "</div>";
echo "<div class='all-accommodations'><ul class='ul'>";
$stmt = $con->prepare("SELECT " . Accommodation::ID_COLUMN . " FROM " . Accommodation::TABLE_NAME . " LIMIT 50");
$stmt->execute();
$stmt->bindColumn(1, $accId);
while($stmt->fetch())
{
  $acc = new Accommodation($con, 'get', array('id' => $accId));
  if($acc->getError())
  {
    continue;
  }
  $acc = json_decode($acc->toJson(),1);
  $accId = $acc['id'];
  $accName = $acc['name'];
  $accNoReviews = count($acc['reviews']);
  $accRating = $acc['rating'];
  $accDescription = $acc['description'];

  include __ROOT__.'/inc/html/accommodation-box.php';
}
echo "</ul></div><div>";
?>