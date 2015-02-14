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
        </div>
        <div class="scroll-wrapper">
          <div class="scroll-area">
            <div id="conv" class="conversation">
              <?=($err)?$err:$conv?>
            </div>
            <div class="scroll-bar"><div class="scroll-tracker"></div></div>
          </div>
        </div>
        <div class="box-padding">
          <!--TODO: CHANGE METHOD TO POST-->
          <div class="textarea-holder"><textarea class="textarea" id="message" placeholder="Write a message..."></textarea>
          </div><input type="submit" class="input-button block" value="Send"
          data-ajax-url="../php/messages.process.php?receiver=<?=$otherUserId?>"
          data-ajax-post="message"
          data-ajax-callback="update messages ../php/update_message.process.php?otherId=<?=$otherUserId?>">
        </div>
      </div>
    </div>
  </div>
<?php
$scripts = array('update_message');
include __ROOT__."/inc/html/footer.php";

// Read all messages in this conv
$conversation = new Conversation($con, $user->getIdentifier('id'), $otherUserId);
$conversation->readMessages();
?>