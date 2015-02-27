<?php
/*
Script retrievs messages from db for this user, that have not been read yet,
ordering them by timestamp (newest first)
*/

$messages = $user->getNotifMessages(0);
?>
<div id="message-drop" class="drop drop-wide hidden ">
  <div class="drop-icon-holder"><div class="drop-icon-border"></div><div class="drop-icon"></div></div>
  <div class="drop-box">
    <h2 class="drop-header">Messages</h2>
    <div class="drop-list-wrapper scroll-wrapper">
      <div class="scroll-area drop-list-area">
        <ul class="ul">
          <li class="ph ph-last ph-drop" data-placeholder="No messages."></li>
<?php
if ($messages)
{
  echo $messages;
}
$userId = $user->getIdentifier('id');
$unreadCount = $user->getUnreadCount();
if ($unreadCount > 99) {
  $unreadCount = "99+";
}
?>
        </ul>
      </div>
    </div>
    <a href='<?=$webRoot?>/messages' class='drop-footer link'>View all</a>
  </div>
</div>
<span class="icon-holder" title="Messages" data-toggle="message-drop" data-hide="drop" data-icon-number="<?=$unreadCount?>">
  <span class="icon icon-messages" data-toggle="message-drop" data-hide="drop"></span>
</span>