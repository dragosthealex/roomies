<!-- Header for logged in users -->
<div class="header">
  <div class="header-padding">
    <?php require_once "header.php"; ?>
    <ul class="header-nav">
      <li class="header-item">
        <a href="<?=$webRoot?>/profile/<?=$user->getIdentifier('username')?>" class="icon-holder user-icon-holder">
          <span class="icon icon-user" style="background-image: url('<?=$user->getIdentifier("image")?>'), url(../media/img/default.gif)"></span>
        </a>
      </li>
      <li class="header-item">
        <span class=hidden></span>
        <a href="<?=$webRoot?>" class="icon-holder" title="Home">
          <span class="icon icon-home">Home</span>
        </a>
      </li>
      <li class="header-item drop-parent">
        <?php include __ROOT__."/inc/html/friend_requests.php"; ?>
      </li>
      <li class="header-item drop-parent">
        <?php include __ROOT__."/inc/html/new_messages.php"; ?>
      </li>
      <li class="header-item not-mobile">
        <span class="hidden"></span>
        <a href="<?=$webRoot?>/search" class="icon-holder" title="Find Roomies">
          <span class="icon icon-search">Find Roomies</span>
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
                <a href="<?=$webRoot?>/search" class="link">Find Roomies</a>
              </li>
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
<!-- Le chat friends list -->
<div class="slim">
  <div class="slim-tag" data-toggle="slim-toggler"></div>
  <div id="slim-toggler"></div>
  <ul class="slim-main">
    <li class='ph ph-last ph-drop' data-placeholder='No friends.'></li>
    <?php
    $userId = $user2->getCredential('id');
    $stmt = $con->prepare("SELECT * FROM rconexions WHERE (conexion_user_id1 = '$userId' OR conexion_user_id2 = '$userId') AND conexion_status = 1");
    $stmt->execute();
    if ($stmt->rowCount())
    {
      while ($conexion = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        $leOtherUser = new OtherUser($con, $conexion['conexion_user_id'.($conexion['conexion_user_id1']==$userId?'2':'1')]);
        if (!$leOtherUser->getError())
        {
          $leOtherUserId = $leOtherUser->getCredential('id');
          $leOtherUserUsername = $leOtherUser->getCredential('username');
          $leOtherUserName = $leOtherUser->getName();
          $onlineStatus = $leOtherUser->getOnlineStatus();
          echo "<li class='$onlineStatus' data-slim-user-id='$leOtherUserId'><a href='$webRoot/messages/$leOtherUserUsername' class='slim-link'>$leOtherUserName</a></li>";
        }
      }
    }
    ?>
  </ul>
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
