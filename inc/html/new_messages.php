<?php
/*
Script retrievs messages from db for this user, that have not been read yet,
ordering them by timestamp (newest first)
*/

?>

<div class="new-messages">
  <?=($user->getNotifMessages(0))?$user->getNotifMessages(0):"No messages. Nobody loves you.";?>
</div>