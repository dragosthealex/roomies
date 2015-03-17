<?php



echo $user2->hasConnected('facebook') ? "<input type='hidden' id='fbAction' value='0'>" : "<input type='hidden' id='fbAction' value='1'>";
?>

<div class="box">
  <div class="box-padding">
    <h3 class="h3">Facebook</h3>
    <!--Facebook Login-->
    <?php if(!$user2->hasConnected('facebook')){?>
    <fb:login-button scope="public_profile,email,user_birthday" onlogin="checkLoginState();">
    </fb:login-button>
    <?php }else{?>
    <a class="fb-disconnect" onclick="checkLoginState();">Disconnect</a>
    <? }?>
    <div id="status">
    </div>
  </div>
</div>