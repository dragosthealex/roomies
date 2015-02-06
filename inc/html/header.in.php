<!-- Header for logged in users -->
<div class="header">
  <!-- <div class="header-padding">
    <div class="header-div">
    </div>
    <div class="header-div logo"> -->
<?php
// Include the logo
require_once "header.php";
?>
    <!-- </div>
    <div class="header-div">
      <ul class="ul header-menu" style="height:auto;">
      <li class="li">
        <a class="a" href="<?=$webRoot?>/profile/" title="My profile"><?=$user->getIdentifier('username')?></a>
      </li>
      <li class="li">
        <a class="a settings door" href="?logout=yes" title="Logout"></a>
      </li>
      <li class="li">
        <a class="a settings cog" href="<?=$webRoot?>/settings/" title="Account Settings"></a>
      </li>
    </ul>
    </div>
  </div> -->
</div>
<!-- Space for the header -->
<div class="header-space"></div>
<!-- Main content -->
<div class="main">