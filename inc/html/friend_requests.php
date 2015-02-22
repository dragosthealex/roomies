<?php
// Get friend requests for this user

$id = $user->getIdentifier('id');

// Get the friends requests
$stmt = $con->prepare("SELECT conexion_user_id1 FROM rconexions WHERE conexion_user_id2 = '$id' AND conexion_status = '2'");
$stmt->execute();
$stmt->bindColumn(1, $otherUserId);
echo '<div class="drop-list-wrapper scroll-wrapper"><div class="drop-list-area scroll-area">';
if($stmt->rowCount())
{
  echo "<ul class='ul'>";
}
else
{
  echo "<p class='drop-placeholder'>No friend requests.</p>";
}

while($stmt->fetch())
{
  $otherUser = new User($con, $otherUserId);
  $otherUsername = $otherUser->getIdentifier('username');
  $percentage = $user->getPercentageWith($otherUser);
  echo 
  "
  <li class='drop-item'>
    <div class='drop-item-box'>
      <a class='drop-item-pic' href='/profile/?u=$otherUsername' style='background-image: url(/media/img/anonymous.jpg)'></a>
      <a class='drop-item-header link' href='/profile/$otherUsername'>$otherUsername</a>
      <p class='drop-item-text'>
        <button class='input-button button2' style='margin:0'>Accept</button>
        <button class='input-button button2' style='margin:0'>Ignore</button>
      </p>
      <p class='drop-item-footer' style='color:rgba(0,160,0,1)'>$percentage%</p>
    </div>
  </li>
  ";
}
if($stmt->rowCount())
{
  echo "</ul>";
}
echo '</div></div>';
if($stmt->rowCount())
{
  echo "<a href='$webRoot/friends/requests' class='drop-footer link'>View all</a>";
}
?>