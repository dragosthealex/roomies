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
$conv = "";

/*
This script provides variables:
-> $conv, the conversation as html
-> $otherUser, the other user
-> $otherName, the other user name
-> $otherUserId, the other user id
*/
include "../php/conversation.process.php";


// Page begins here, html and body tags are opened in head, closed in footer. Also, main div is closed in footers
?>

<div class="column-wrapper">
    <!--About me-->
    <div class="column-2">
      <div class="column-box">
        <div class="box-padding">
          <h2 class="h2">Conversations</h2>
            <ul class='ul'>
              <?=$allConversations?>
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
          <?=($err)?$err:$conv?>
          <div class="message-input">
            <!--TODO: CHANGE METHOD TO POST-->
            <textarea rows="4" cols="50" class="textarea" onchange="this.nextSibling.setAttribute('data-action', '../php/messages.process.php?message='+this.value+'&receiver=<?=$otherUserId?>');"></textarea><input type="submit" class="input-button ajax " value="Send" onclick="this.previousSibling.value = '';">
          </div>
        </div>
      </div>
    </div>
  </div>


<?php include __ROOT__."/inc/html/footer.php";?>