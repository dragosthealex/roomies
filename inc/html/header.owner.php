<!-- Header for logged in users -->
<div class="header">
  <div class="header-padding">
    <?php require_once "header.php"; ?>
    <ul class="header-nav">
      <li class="header-item">
        <a href="<?=$webRoot?>/profile/<?=$owner->getCredential('username')?>" class="icon-holder user-icon-holder">
          <span class="icon icon-user" style="background-image: url('<?=$owner->getCredential("image")?>'), url(../media/img/default.gif)"></span>
        </a>
      </li>
      <li class="header-item">
        <span class=hidden></span>
        <a href="<?=$webRoot?>" class="icon-holder" title="Home">
          <span class="icon icon-home">Home</span>
        </a>
      </li>
      <li class="header-item not-mobile">
        <span class="hidden"></span>
        <a href="<?=$webRoot?>/accommodation" class="icon-holder" title="Accommodation">
          <span class="icon icon-accommodation">Accommodation</span>
        </a>
      </li>
      <li class="header-item drop-parent">
        <div id="extra-drop" class="drop drop-mini hidden ">
          <div class="drop-icon-holder"><div class="drop-icon-border"></div><div class="drop-icon"></div></div>
          <div class="drop-box">
            <ul class="ul">
              <li class="mobile-only">
                <a href="<?=$webRoot?>/accommodation" class="link">Accommodation</a>
              </li>
              <li class="mobile-only drop-mini-split"></li>
              <li>
                <a href="<?=$webRoot?>/settings" class="link">Settings</a>
              </li>
              <li>
                <a href="?logout" class="link">Sign out</a>
              </li>
            </ul>
          </div>
        </div>
        <span class="icon-holder" data-toggle="extra-drop">
          <span class="icon icon-down" data-toggle="extra-drop"></span>
        </span>
      </li>
    </ul>
  </div>
</div>
<div class="slim hidden">
  <div id="slim-toggler"></div>
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
      <h1 class="h1">Welcome back, <?=$owner->getName()?>!</h1>
    </header>
  </div>
<?php
} // if
?>
