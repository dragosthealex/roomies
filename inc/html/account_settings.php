<?php
$message = '';
//EMAIL 
if(isset($_POST['currentPass'], $_POST['email1'], $_POST['email2'], $_POST['submit1']) && $_POST['email1'] && $_POST['email2'] && $_POST['currentPass'])
{
  // THIS IS THE ID
  $cactus = $user2->getCredential('id');
      
  $email1 = filter_input(INPUT_POST, 'email1', FILTER_VALIDATE_EMAIL);
  $email2 = filter_input(INPUT_POST, 'email2', FILTER_VALIDATE_EMAIL);   
  $email1 = htmlentities($_POST["email1"]);
  $email2 = htmlentities($_POST['email2']);
  $pass = htmlentities($_POST['currentPass']);
  try
  {
    // Check if conf email matches email
    if($email1 != $email2)
    {
      throw new Exception("Email confirmation doesn't match.", 1);
    }

    // Check if they have inserted invalid chars
    if($email1 != $_POST['email1'])
    {
      throw new Exception("You inserted invalid characters.", 1);
    }

    // Check if it's the right pass
    if(!validate_pass($con, $cactus, $pass))
    {
      throw new Exception("Your password was incorrect.", 1);
    }

    //if email is valid, update the table
    $stmt = $con->prepare("UPDATE rusers SET user_email='$email1' WHERE user_id = $id");
    $stmt->execute();
    $message .= "Your email was updated." . "<br>";
  }
  catch (Exception $e)
  {
    $message .= $e->getMessage() . "<br>";
  }
  $cactus = null;
  $stmt = null;
  $id = null;
}

//PASSWORD
if(isset($_POST['password1'], $_POST['password2'], $_POST['currentPass'], $_POST['submit1']) && $_POST['password1'] && $_POST['password2'] && $_POST['currentPass'])
{
  //$id = $user2->getCredential('id');
  $id = $user2->getCredential('id');

  $password1 = htmlentities($_POST['password1']);
  $password2 = htmlentities($_POST['password2']);
  $currentPass = htmlentities($_POST['currentPass']);

  try
  {
    if($password1 != $password2)
    {
      throw new Exception("Your confirm password doesn't match", 1);
    }

    if(!valid_pass($password1))
    {
      throw new Exception("You inserted invalid characters", 1);
    }

    if(!validate_pass($con, $id, $currentPass))
    {
      throw new Exception("Your password was incorrect", 1);
    }
    //if password is valid, proceed to update the table

    // Create random salt, hash the pass
    $salt = mt_rand();
    $password = hash('sha256', $password1.$salt);

    $stmt = $con->prepare("UPDATE rusers SET user_salt = '$salt', user_pass  = '$password' WHERE user_id = $id");
    $stmt->execute();
    $message .= "Your password was changed. <br>";
  }
  catch (Exception $e)
  {
    $message .= $e->getMessage() . "<br>";
  }

  $id = null;
  $stmt = null;
  $salt = null;
  $password = null;
}

// INVISIBLE
if(isset($_POST['currentPass'], $_POST['submit1']) && $_POST['currentPass'])
{
  $currentPass = htmlentities($_POST['currentPass']);
  $id = $user2->getCredential('id');

  try
  {
    if(!validate_pass($con, $id, $currentPass))
    {
      throw new Exception("Your password was incorrect", 1);
    }

    $dbInvisible = $user2->getSetting('is_invisible');
    if($user2->getError())
    {
      throw new Exception($user2->getError(), 1);
    }

    // Check if the set value is different from db
    if($dbInvisible && !isset($_POST['invisible']))
    {
      $dbInvisible = 0;
      $message .= "Your invisible status was updated. <br>";
    }
    else if(!$dbInvisible && $_POST['invisible'])
    {
      $dbInvisible = 1;
      $message .= "Your invisible status was updated. <br>";
    }

    // Update setting
    $stmt = $con->prepare("UPDATE rusersettings SET is_invisible=$dbInvisible WHERE setting_user_id = $id");
    if(!$stmt->execute())
    {
      throw new Exception("Error updating invisible status", 1);
    }
    $stmt->execute();

  }
  catch (Exception $e)
  {
    $message .= $e->getMessage() . "<br>";
  }
}

// SET PRIVATE
if(isset($_POST['currentPass'], $_POST['privacy'], $_POST['submit1']) && $_POST['currentPass'])
{
  $currentPass = htmlentities($_POST['currentPass']);
  $id = $user2->getCredential('id');

  try
  {
    if(!validate_pass($con, $id, $currentPass))
    {
      throw new Exception("Your password was incorrect", 1);
    }

    $isPrivate = ($_POST['privacy'] == 'private')?1:0;
    $stmt = $con->prepare("UPDATE rusersettings SET is_private=$isPrivate WHERE setting_user_id=$id");
    if(!$stmt->execute())
    {
      throw new Exception("Error updating private status", 1);
    }
    $message .= "Your private status was updated. <br>";
  }
  catch (Exception $e)
  {
    $message .= $e->getMessage();
  }
}

// Validate the new password
function valid_pass($password) 
{
   $r1='/[A-Z]/';  //Uppercase
   $r2='/[a-z]/';  //lowercase
   $r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
   $r4='/[0-9]/';  //numbers

   if(!preg_match_all($r1,$password, $o)) return FALSE;

   if(!preg_match_all($r2,$password, $o)) return FALSE;

   if(!preg_match_all($r3,$password, $o)) return FALSE;

   if(!preg_match_all($r4,$password, $o)) return FALSE;

   if(strlen($password)>25) return FALSE;

   return TRUE;
} //end valid_pass
?>

<form action="" method="POST" class="settings account-settings">
  <div class="box-padding">
    <h3 class="h3">Change Email</h3>
    <div class="input-wrapper">
      <input class="input" placeholder="New Email" type="email" name="email1">
      <input class="input" placeholder="Repeat New Email" type="email" name="email2">
    </div>
    <h3 class="h3">Change Password</h3>
    <div class="input-wrapper">
      <input class="input" placeholder="New Password" type="password" name="password1">
      <input class="input" placeholder="Repeat New Password" type="password" name="password2">
    </div>
    <h3 class="h3">Privacy</h3>
    <div class="indented-section">
      <label for="radio1" class="cr-label">
        <input id="radio1" class="cr" type="radio" name="privacy" value="public" <?=$user2->getSetting('is_private')?'':'checked'?>>
        <span class="cr-button r-button"></span>
        <span class="cr-text">Show my full name</span>
      </label>
      <label for="radio2" class="cr-label">
        <input id="radio2" class="cr" type="radio" name="privacy" value="private" <?=$user2->getSetting('is_private')?'checked':''?>>
        <span class="cr-button r-button"></span>
        <span class="cr-text">Only show username</span>
      </label>
      <label for="check1" class="cr-label">
        <input id="check1" class="cr" type="checkbox" name="invisible" <?=$user2->getSetting('is_invisible')?'checked':''?>>
        <span class="cr-button"></span>
        <span class="cr-text">Make me invisible</span>
      </label>
      <!--TODO: IMPLEMENT DELETE ACCOUNT-->
      <label for="check2" class="cr-label">
        <input id="check2" class="cr" type="checkbox" name="delete">
        <span class="cr-button"></span>
        <span class="cr-text">Delete account</span>
      </label>
    </div>
    <h3 class="h3">Confirm changes</h3>
    <div class="input-wrapper">
      <input class="input" placeholder="Current Password" required type="password" name="currentPass">
      <input class="input-button" type="submit" name="submit1" value="Update">
    </div>
    <?=$message?>
  </div>
</form>
