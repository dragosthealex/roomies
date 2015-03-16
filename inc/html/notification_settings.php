<?php
$message = '';
if(isset($_POST['currentPass'], $_POST['submit2']) && $_POST['currentPass'] && $_POST['submit2'])
{
  $ok = 0;
  try 
  {
    if(!validate_pass($con, $id, $_POST['currentPass']))
    {
      throw new Exception("Your password was incorrect", 1);
    }
    
    $pass = htmlentities($_POST['currentPass']);
    $id = $user2->getCredential('id');
    $notif_request = $user2->getSetting('notif_request');
    $notif_accept = $user2->getSetting('notif_accept');
    $notif_message = $user2->getSetting('notif_message');
    $notif_over90 = $user2->getSetting('notif_over90');
    //$notif_fbfriends = $user2->getSetting('notif_fbfriends');

    if($user2->getError())
    {
      throw new Exception($user2->getError(), 1);
    }

    // Check if the set value is different from db
    if($notif_request && !isset($_POST['notif_request']))
    {
      $notif_request = 0;
      $ok = 1;
    }
    else if(!$notif_request && isset($_POST['notif_request']))
    {
      $notif_request = 1;
      $ok = 1;
    }

    // Check if the set value is different from db
    if($notif_accept && !isset($_POST['notif_accept']))
    {
      $notif_accept = 0;
      $ok = 1;
    }
    else if(!$notif_accept && $_POST['notif_accept'])
    {
      $notif_accept = 1;
      $ok = 1;
    }

    // Check if the set value is different from db
    if($notif_message && !isset($_POST['notif_message']))
    {
      $notif_message = 0;
      $ok = 1;
    }
    else if(!$notif_message && $_POST['notif_message'])
    {
      $notif_message = 1;
      $ok = 1;
    }

    // Check if the set value is different from db
    if($notif_over90 && !isset($_POST['notif_over90']))
    {
      $notif_over90 = 0;
      $ok = 1;
    }
    else if(!$notif_over90 && $_POST['notif_over90'])
    {
      $notif_over90 = 1;
      $ok = 1;
    }

    // Update the details
    $stmt = $con->prepare("UPDATE rusersettings SET notif_over90=$notif_over90, notif_message=$notif_message, notif_accept=$notif_accept, notif_request=$notif_request WHERE setting_user_id=$id");
    if(!$stmt->execute())
    {
      throw new Exception("Error updating email settings", 1);
      
    }

    if($ok)
    {
      $message .= "Your settings were updated.";
    }
  }
  catch (Exception $e)
  {
    $message .= $e->getMessage();
  }
}
?>
<form action="" method="POST" class="settings notif-settings hidden" onsubmit="rCookie.set('data-hide','settings');rCookie.set('data-show','notif-settings');return true">
  <div class="box-padding">
    <h3 class="h3">I want to get an email when&hellip;</h3>
    <div class="cr-block">
      <label for="check3" class='cr-label'>
        <input id="check3" class="cr" type="checkbox" name="notif_request" <?=$user2->getSetting('notif_request')?'checked':''?>>
        <span class="cr-button"></span>
        <span class="cr-text">I receive a friend request.</span>
      </label>
    </div>
    <div class="cr-block">
      <label for="check4" class='cr-label'>
        <input id="check4" class="cr" type="checkbox" name="notif_accept" <?=$user2->getSetting('notif_accept')?'checked':''?>>
        <span class="cr-button"></span>
        <span class="cr-text">Someone accepts a friend request that I sent.</span>
      </label>
    </div>
    <div class="cr-block">
      <label for="check5" class='cr-label'>
        <input id="check5" class="cr" type="checkbox" name="notif_message" <?=$user2->getSetting('notif_message')?'checked':''?>>
        <span class="cr-button"></span>
        <span class="cr-text">I have a new message.</span>
      </label>
    </div>
    <div class="cr-block">
      <label for="check6" class='cr-label'>
        <input id="check6" class="cr" type="checkbox" name="notif_over90" <?=$user2->getSetting('notif_over90')?'checked':''?>>
        <span class="cr-button"></span>
        <span class="cr-text">I have a new over 90% match.</span>
      </label>
    </div>
    <div class="cr-block">
      <!--TODO: IMPLEMENT FB FRIENDS CHECK-->
      <label for="check7" class='cr-label'>
        <input id="check7" class="cr" type="checkbox" name="notif_fbfriend" <?=$user2->getSetting('notif_fbfriend')?'checked':''?>>
        <span class="cr-button"></span>
        <span class="cr-text">One of my Facebook friends registers on Roomies.</span>
      </label>
    </div>
    <h3 class="h3">Confirm changes</h3>
    <div class="input-wrapper">
      <input class="input has-submit" placeholder="Current Password" required type="password" name="currentPass"
      ><input class="input-button" type="submit" name="submit2" value="Update">
    </div>
    <?=$message?>
  </div>
</form>