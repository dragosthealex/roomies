<?php
/*
Script retrievs messages from db for this user, that have not been read yet,
ordering them by timestamp (newest first)
*/

?>

<?=($user->getNotifMessages(0))?$user->getNotifMessages(0):"No messages. Nobody loves you.";?>