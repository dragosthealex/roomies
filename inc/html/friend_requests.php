<?php
// Get friend requests for this user

$id = $user->getIdentifier('id');

// Get the friends requests
$stmt = $con->prepare("SELECT conexion_user_id1 FROM rconexions WHERE conexion_user_id2 = '$id' AND conexion_status = '2'");
$stmt->execute();
$stmt->bindColumn(1, $otherUserId);
if($stmt->rowCount())
{
  echo "<ul class='ul'>";
}
else
{
  echo "No friend requests yet. Loner.";
}

while($stmt->fetch())
{
  $otherUser = new User($con, $otherUserId);
  $otherUsername = $otherUser->getIdentifier('username');
  $percentage = $user->getPercentageWith($otherUser);
  echo 
  "
  <li class='box'>
    <div class='box-padding'>
      <p class='text'>
        <a class='link' href='profile/?u=$otherUsername'>
          <img alt='' class='profile-picture' src='media/img/anonymous.jpg'>
          $otherUsername
        </a>
        <button class='input-button button2'>
          Accept
        </button>
        <button class='input-button button2'>
          Reject
        </button>
      </p>
      <p class='text'>
        <span style='font-size:1.5em;line-height:0;color:rgba(0,160,0,1)'>
          $percentage%
        </span>
      </p>
    </div>
  </li>
  ";
}
if($stmt->rowCount())
{
  echo "</ul>";
}
?>