<?php
// Get friend requests for this user

$id = $user->getIdentifier('id');

// Get the friends requests
$stmt = $con->prepare("SELECT conexion_user_id1 FROM rconexions WHERE conexion_user_id2 = '$id' AND conexion_status = '2'");
$stmt->execute();
$stmt->bindColumn(1, $otherUserId);
?>
<div id="frequests-drop" class="drop drop-wide hidden ">
  <div class="drop-icon-holder"><div class="drop-icon-border"></div><div class="drop-icon"></div></div>
  <div class="drop-box">
    <h2 class="drop-header">Friend Requests</h2>
    <div class="drop-list-wrapper scroll-wrapper">
      <div class="drop-list-area scroll-area">
        <ul class="ul" id="frequests-drop-list">
          <li class="ph ph-last ph-drop" data-placeholder="No friend requests."></li>
<?php
while($stmt->fetch())
{
  $otherUser = new User($con, $otherUserId);
  $otherUsername = $otherUser->getIdentifier('username');
  $percentage = $user->getPercentageWith($otherUser);
  echo 
  "
  <li class='drop-item friend-request' id='drop-item-fr-$otherUserId' data-fr-id='$otherUserId'>
    <div class='drop-item-box'>
      <a class='drop-item-pic' href='/profile/?u=$otherUsername' style='background-image: url(".$otherUser->getIdentifier('image').")'></a>
      <h3 class='drop-item-header'>
        <div class='drop-item-header-right'>
          <a data-ajax-url='../php/friends.process.php?a=3&amp;id=$otherUserId'
             data-ajax-text='Accepting...'
             data-ajax-callback-1='deleteById drop-item-fr-$otherUserId'
             data-ajax-callback-2='updateNofifCount'
             class='link-button button2'>Accept</a>
          <a data-ajax-url='../php/friends.process.php?a=0&amp;id=$otherUserId'
             data-ajax-text='Ignoring...'
             data-ajax-callback-1='deleteById drop-item-fr-$otherUserId'
             data-ajax-callback-2='updateNofifCount'
             class='link-button button2'>Ignore</a>
        </div>
        <a href='/profile/$otherUsername' class='link'>$otherUsername</a>
      </h3>
      <p class='drop-item-footer'></p>
      <p class='drop-item-text' style='color:rgba(".(160-160*$percentage/100).",".(160*$percentage/100).",0,1)'>$percentage%</p>
    </div>
  </li>
  ";
}
$requestCount = $stmt->rowCount();
if ($requestCount > 99) {
  $requestCount = "99+";
}
?>
        </ul>
      </div>
    </div>
    <a href='<?=$webRoot?>/friends/requests' class='drop-footer link'>View all</a>
  </div>
</div><span class="icon-holder" title="Friend Requests" data-toggle="frequests-drop" data-hide="drop" data-icon-number="<?=$requestCount?>">
  <span class="icon icon-frequests" data-toggle="frequests-drop" data-hide="drop"></span>
</span>
<?php
$stmt = null;
?>