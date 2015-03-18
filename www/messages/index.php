<?php
include '../../inc/init.php';

if(!LOGGED_IN)
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

// The default title of the page
$title = "Messages";

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

// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";

// Page begins here, html and body tags are opened in head, closed in footer. Also, main div is closed in footers
?>

<div class="column-wrapper">
  <?=$errorMsg?>
    <!--About me-->
    <div class="column-2">
      <div class="column-box">
        <div class="box-padding">
          <h2 class="h2">Conversations</h2>
            <ul class='ul'>
              <div id="allConversations">
              <?php
              if ($allConversations == '')
              {
              ?>
                <li class='ph ph-last ph-message' data-placeholder="No conversations."></li>
              <?php
              }
              else echo $allConversations;
              ?>
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
          <div class="scroll-area" id="main_conversation_scroll"><?=($err)?$err:$conv?></div>
          <script>setTimeout(function(){var p=document.getElementById("main_conversation_scroll");p.scrollTop=p.scrollHeight},100)</script>
        </div>
        <?php if (!isset($noMessages)) { ?>
        <div class="box-padding">
          <!--TODO: CHANGE METHOD TO POST-->
          <div class="textarea-holder"
            ><textarea class="textarea" id="message" placeholder="Write a message..."
                       oninput="this.style.height=((this.value.match(/\n/g)||[]).length+2)*1.3+'em';return false"
                       onkeydown="return !this.parentNode.nextSibling.firstChild.checked || (event.shiftKey || ((event.keyCode === 13 && this.value.trim()) ? (window.onclick({button:1,target:this.parentNode.nextSibling.nextSibling}), false) : event.keyCode !== 13));"
                       data-conv-id="<?=$otherUserId==''?0:$otherUserId?>"
                       data-group-id="<?=$groupId?>"
                       data-focus></textarea>
          </div
          ><label for="pressEnterToSend" class="cr-label"
            ><input type="checkbox" id="pressEnterToSend" onchange="var b=this.parentNode.nextSibling,c=b.className;b.className=this.checked?c+'hidden ':c.replace(' hidden ', ' ');rCookie.set('pressEnterToSend',this.checked,Infinity,'/')" class="cr">
            <span class="cr-button"></span>
            <span class="cr-text cr-text-faded">Press Enter to send</span>
          </label
          ><input type="submit" class="input-button block " value="Send"
                  data-ajax-url="../php/messages.process.php?receiver=<?=$otherUserId?>&gid=<?=$groupId?>"
                  data-ajax-post="message"
                  data-ajax-callback-1="scrollToBottom conv 1"
                  data-ajax-callback-2="focusById message">
          <script>(function(){var p=document.getElementById('pressEnterToSend');p.checked=rCookie.get('pressEnterToSend')==='true';p.onchange()}())</script>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
<?php
// $scripts = array('update_message');
include __ROOT__."/inc/html/footer.php";
?>