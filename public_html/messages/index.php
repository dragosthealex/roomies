<?php
include '../../inc/init.php';

// The title of the page
$title = "Messages";
if(!LOGGED_IN)
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";

$err = "";
// The current opened conversation
$conv = "";
// Handlers for all conversations
$allConversations = "";

/*
This script provides variables:
-> $conv, the conversation as html
-> $otherUser, the other user
-> $otherName, the other user name
-> $otherUserId, the other user id
*/
include __ROOT__."/inc/html/messages_page.php";


// Page begins here, html and body tags are opened in head, closed in footer. Also, main div is closed in footers
?>

<div class="column-wrapper">
    <!--About me-->
    <div class="column-2">
      <div class="column-box">
        <div class="box-padding">
          <h2 class="h2">Conversations</h2>
            <ul class='ul'>
              <div id="allConversations">
                <?=$allConversations?>
              </div>
            </ul>
          <p class="text">

          </p>
        </div>
      </div>
    </div>
    <!--filters-->
    <div class="column-2">
      <div class="column-box">
        <div class="box-padding">
          <h2 class="h2"><?=$otherName?></h2>
            <div id="conv">
              <?=($err)?$err:$conv?>
            </div>
          <div class="message-input">
            <!--TODO: CHANGE METHOD TO POST-->
            <textarea rows="4" cols="50" class="textarea" id="message"></textarea>
            <input type="submit" class="input-button" value="Send" onclick="this.previousSibling.value = '';"
            data-ajax-url="../php/messages.process.php?receiver=<?=$otherUserId?>"
            data-ajax-post="message"
            data-ajax-callback="update messages ../php/update_message.process.php?otherId=<?=$otherUserId?>">
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include __ROOT__."/inc/html/footer.php";
    // Read all messages in this conv
  $conversation = new Conversation($con, $user->getIdentifier('id'), $otherUserId);
  $conversation->readMessages();
?>