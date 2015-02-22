<?php
// Get friend requests for this user

$id = $user->getIdentifier('id');

// Get the friends requests
$stmt = $con->prepare("SELECT conexion_user_id1 FROM rconexions WHERE conexion_user_id2 = '$id' AND conexion_status = '2'");
$stmt->execute();
$stmt->bindColumn(1, $otherUserId);
echo '<div class="drop-list-wrapper scroll-wrapper"><div class="drop-list-area scroll-area"><ul class="ul"><li class="drop-placeholder" data-placeholder="No friend requests."></li>';
while($stmt->fetch())
{
  $otherUser = new User($con, $otherUserId);
  $otherUsername = $otherUser->getIdentifier('username');
  $percentage = $user->getPercentageWith($otherUser);
  echo 
  "
  <li class='drop-item' id='drop-item-fr-$otherUserId'>
    <div class='drop-item-box'>
      <a class='drop-item-pic' href='/profile/?u=$otherUsername' style='background-image: url(/media/img/anonymous.jpg)'></a>
      <h3 class='drop-item-header'>
        <div class='drop-item-header-right'>
          <a data-ajax-url='../php/friends.process.php?a=3&amp;id=$otherUserId'
             data-ajax-text='Accepting...'
             data-ajax-callback='deleteById drop-item-fr-$otherUserId'
             class='link-button button2'>Accept</a>
          <a data-ajax-url='../php/friends.process.php?a=0&amp;id=$otherUserId'
             data-ajax-text='Ignoring...'
             data-ajax-callback='deleteById drop-item-fr-$otherUserId'
             class='link-button button2'>Ignore</a>
        </div>
        <a href='/profile/$otherUsername' class='link'>$otherUsername</a>
      </h3>
      <p class='drop-item-footer'></p>
      <p class='drop-item-text' style='color:rgba(".(255-255*$percentage/100).",".(255*$percentage/100).",0,1)'>$percentage%</p>
    </div>
  </li>
  ";
}
echo "</ul></div></div><a href='$webRoot/friends/requests' class='drop-footer link'>View all</a>";
?>