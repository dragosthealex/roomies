<?php
$message = '';
if(isset($_POST['currentPass'], $_POST['submit2']) && $_POST['currentPass'] && $_POST['submit2'])
{
  $ok = 0;
  try 
  {
    if(!validate_pass($con, $id, $currentPass))
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
    else if(!$notif_request && $_POST['notif_request'])
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







<form id="notif_settings" action="" method="POST" style="display:none">
  <span>
    <p>
      I want to get an email when:
    </p>
  </span>
  <div>
    <label for="check3" class='cr-label cr-label-block'>
      <input id="check3" class="cr" type="checkbox" name="notif_request" <?=$user2->getSetting('notif_request')?'checked':''?>>I receive a friend request.
      <span class="cr-button"></span>
    </label>
  </div>
  <div>
    <label for="check4" class='cr-label cr-label-block'>
      <input id="check4" class="cr" type="checkbox" name="notif_accept" <?=$user2->getSetting('notif_accept')?'checked':''?>>Someone accepts a friend request that I sent.
      <span class="cr-button"></span>
    </label>
  </div>
  <div>
    <label for="check5" class='cr-label cr-label-block'>
      <input id="check5" class="cr" type="checkbox" name="notif_message" <?=$user2->getSetting('notif_message')?'checked':''?>>I have a new message.
      <span class="cr-button"></span>
    </label>
  </div>
  <div>
    <label for="check6" class='cr-label cr-label-block'>
      <input id="check6" class="cr" type="checkbox" name="notif_over90" <?=$user2->getSetting('notif_over90')?'checked':''?>>I have a new over 90% match.
      <span class="cr-button"></span>
    </label>
  </div>
  <!--TODO: IMPLEMENT FB FRIENDS CHECK-->
  <div>
    <label for="check7" class='cr-label cr-label-block'>
      <input id="check7" class="cr" type="checkbox" name="notif_fbfriend" <?=$user2->getSetting('notif_fbfriend')?'checked':''?>>One of my Facebook friends registers on Roomies.
      <span class="cr-button"></span>
    </label>
  </div>
  <span>
    <p>
      Your current Password:
    </p>
  </span>
  <input class="input" placeholder="Current Password" required type="password" name="currentPass">
  <input class="input-button" type="submit" name="submit2" value="Update">
  <?=$message?>
</form>