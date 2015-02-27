<?php
/*
This outputs the accommodations as LI elements, containing name, main photo and short desc
*/

echo "<div class='all-accommodations'><ul class='ul'>";
$stmt = $con->prepare("SELECT accommodation_id, accommodation_name, accommodation_description FROM raccommodations LIMIT 50");
$stmt->execute();
$stmt->bindColumn(1, $accId);
$stmt->bindColumn(2, $accName);
$stmt->bindColumn(3, $accDescription);
while($stmt->fetch())
{
  $accDescription = substr($accDescription, 0, 400);
  echo "
        <li class='li' style='overflow: hidden's>
          <a style='margin: 5px;' href='./?i=$accId'>
            $accName
          </a>
          <div class='acc-short-pic' style='background-image: url($webRoot/media/img/acc/$accId);'>
          </div>
          <div class='acc-short-desc'>
            $accDescription
          </div>
        </li>
        ";
}
echo "</ul></div>";

?>