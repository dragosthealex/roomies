<?php
/*
Script retrievs messages from db for this user, that have not been read yet,
ordering them by timestamp (newest first)
*/

$messages = $user->getNotifMessages(0);

echo '<div class="drop-list-wrapper scroll-wrapper"><div class="scroll-area drop-list-area">';

if ($messages)
{
    echo '<ul class="ul">';
    echo $user->getNotifMessages(0);
    echo '</ul>';
}
else
{
    echo '<p class="drop-placeholder">No messages.</p>';
}

echo '</div></div>';

if ($messages)
{
    echo "<a href='$webRoot/messages' class='drop-footer link'>View all</a>";
}
?>