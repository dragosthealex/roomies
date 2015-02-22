<?php
/*
Script retrievs messages from db for this user, that have not been read yet,
ordering them by timestamp (newest first)
*/

$messages = $user->getNotifMessages(0);

echo '<div class="drop-list-wrapper scroll-wrapper"><div class="scroll-area drop-list-area"><ul class="ul"><li class="drop-placeholder" data-placeholder="No messages."></li>';

if ($messages)
{
    echo $user->getNotifMessages(0);
}

echo "</ul></div></div><a href='$webRoot/messages' class='drop-footer link'>View all</a>";
?>