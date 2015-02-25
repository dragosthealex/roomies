<?php
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
        <li class='li'>
          <a style='margin: 5px;' href='./?i=$accId'>
            $accName
          </a>
          <div style='background-image: url($webRoot/media/img/acc/$accId); width: 100px; height: 100px; background-size: 100%; float: left; margin-right: 16px;'>
          </div>
          <div>
            $accDescription
          </div>
        </li>
        ";
}
echo "</ul></div>";

?>