<!-- Header for logged in users -->
<div class="header">
  <div class="header-padding">
    <?php require_once "header.php"; ?>
    <ul class="header-nav">
      <li class="header-item">
        <a href="<?=$webRoot?>/profile/<?=$user->getIdentifier('username')?>" class="icon-holder user-icon-holder">
          <span class="icon icon-user" style="background-image: url('<?=$webRoot?>/media/img/anonymous.jpg')"></span>
        </a>
      </li>
      <li class="header-item">
        <span class=hidden></span>
        <a href="<?=$webRoot?>" class="icon-holder" title="Home">
          <span class="icon icon-home">Home</span>
        </a>
      </li>
      <li class="header-item">
        <!-- message drop -->
        <div id="frequests-drop" class="drop hidden ">
          <div class="drop-icon-holder"><div class="drop-icon-border"></div><div class="drop-icon"></div></div>
          <div class="drop-box">
            <div class="drop-header">
              <p class="drop-header-text">Friend Requests</p>
            </div>
            <div class="drop-list-wrapper scroll-wrapper">
              <ul class="drop-list scroll-area">
                <?php include __ROOT__."/inc/html/friend_requests.php"; ?>
              </ul>
            </div>
            <div class="drop-footer">
              <p class="drop-header-text">View all</p>
            </div>
          </div>
        </div>
        <span class="icon-holder" title="Friend Requests" data-toggle="frequests-drop" data-hide="drop">
          <span class="icon icon-frequests" data-toggle="frequests-drop" data-hide="drop"></span>
        </span>
      </li>
      <li class="header-item">
        <div id="message-drop" class="drop hidden ">
          <div class="drop-icon-holder"><div class="drop-icon-border"></div><div class="drop-icon"></div></div>
          <div class="drop-box">
            <div class="drop-header">
              <p class="drop-header-text">Messages</p>
            </div>
            <div class="drop-list-wrapper scroll-wrapper">
              <ul class="drop-list scroll-area">
                <?php include __ROOT__."/inc/html/new_messages.php"; ?>
                <!-- <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div>
                <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div>
                <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div>
                <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div>
                <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div>
                <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div>
                <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div>
                <div class="drop-item">
                  <span class="message-icon" style="background-image: url('/media/img/anonymous.jpg')"></span>
                  <p class="drop-text"><span class="drop-text-span">Hello my name is profile pic and bal bal ala SF ASDF ASdasf asdf asdgasdf</span></p>
                </div> -->
              </ul>
            </div>
            <div class="drop-footer">
              <a href="<?=$webRoot?>/messages" class="link drop-header-text">View all</a>
            </div>
          </div>
        </div>
        <span class="icon-holder" title="Messages" data-toggle="message-drop" data-hide="drop">
          <span class="icon icon-messages" data-toggle="message-drop" data-hide="drop"></span>
        </span>
      </li>
      <li class="header-item">
        <span class="hidden"></span>
        <a href="<?=$webRoot?>/search" class="icon-holder" title="Find Roomies">
          <span class="icon icon-search">Find Roomies</span>
        </a>
      </li>
      <li class="header-item">
        <span class="hidden"></span>
        <a href="<?=$webRoot?>/accommodation" class="icon-holder" title="Accommodation">
          <span class="icon icon-accommodation">Accommodation</span>
        </a>
      </li>
      <li class="header-item">
        <div id="extra-drop" class="drop hidden ">
          <div class="drop-icon-holder"><div class="drop-icon-border"></div><div class="drop-icon"></div></div>
          <div class="drop-box">
            <ul class="drop-list box-padding">
              <li class="drop-item">
                <a href="<?=$webRoot?>/settings" class="link-button block">Settings</a>
              </li>
              <li class="drop-item">
                <a href="?logout" class="link-button block">Sign out</a>
              </li>
            </ul>
          </div>
        </div>
        <span class="icon-holder" data-toggle="extra-drop" data-hide="drop">
          <span class="icon icon-down" data-toggle="extra-drop" data-hide="drop"></span>
        </span>
      </li>
    </ul>
  </div>
</div>
<!-- Space for the header -->
<div class="header-space header-space-extra"></div>
<!-- Main content -->
<div class="main">
<?php
// Welcome back message.
if (JUST_LOGGED_IN)
{
?>
  <div class="not-mobile banner">
    <header>
      <h1 class="h1">Welcome back, <?=$user->getFirstName()?>!</h1>
      <?php
      // TODO:
      // SELECT * FROM  [message table]
      //          WHERE the recipient is this user AND unread = true
      // If rowCount > 0, then output the following:
      // <p class="text">You have [rowCount] unread messages.</p>

      // TODO:
      // SELECT * FROM  [conexion table]
      //          WHERE the recipient is this user AND status is awaiting approval
      // If rowCount > 0, then output the following:
      // <p class="text">You have [rowCount] friend requests.</p>
      ?>
    </header>
  </div>
<?php
} // if
?>
