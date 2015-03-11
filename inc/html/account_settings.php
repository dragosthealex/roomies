<form action="" method="POST" id="account_settings">
  <span>
    <p>
      Change your password:
    <p>
  </span>
  <input class="input" placeholder="New Password" type="password" name="password1">
  <input class="input" placeholder="Repeat New Password" type="password" name="password2">
  <span>
    <p>
      Change your email: 
    </p>
  </span>
  <input class="input" placeholder="New Email" type="email" name="email1">
  <input class="input" placeholder="Repeat New Email" type="email" name="email2">
  <span>
    <p>
      Privacy Settings: 
    </p>
  </span>
  <div class="cr-label">
    <label for="radio1">
      <input id="radio1" class="cr" type="radio" name="privacy" value="public">Show my full name
      <span class="cr-button r-button"></span>
    </label>
  </div>
  <div class="cr-label">
    <label for="radio2">
      <input id="radio2" class="cr" type="radio" name="privacy" value="private">Only show username
      <span class="cr-button r-button"></span>
    </label>
  </div>
  <div class="cr-label">
    <label for="check1">
      <input id="check1" class="cr" type="checkbox" name="invisible">Make me invisible
      <span class="cr-button"></span>
    </label>
  </div>
  <div class="cr-label">
    <label for="check2">
      <input id="check2" class="cr" type="checkbox" name="delete">Delete account
      <span class="cr-button"></span>
    </label>
  </div>
  <span>
    <p>
      Your current Password:
    </p>
  </span>
  <input class="input" placeholder="Current Password" required type="password" name="currentPass">
  <input class="input-button" type="submit" value="Update">
</form>

<?php

//EMAIL 
if(isset($_POST['currentPass'], $_POST['email1'], $_POST['email2']) && $_POST['email1'] && $_POST['email2'] && $_POST['currentPass'])
{
  // THIS IS THE ID
  $cactus = $user->getIdentifier('id');
      
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
    echo "Your email was updated.";
  }
  catch (Exception $e)
  {
    echo $e->getMessage();
  }
  $cactus = null;
  $stmt = null;
  $id = null;
}

//PASSWORD
if(isset($_POST['password1'], $_POST['password2'], $_POST['currentPass']) && $_POST['password1'] && $_POST['password2'] && $_POST['currentPass'])
{
  $id = $user->getIdentifier('id');

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
    echo "Your password was changed";
  }
  catch (Exception $e)
  {
    echo $e->getMessage();
  }

  $id = null;
  $stmt = null;
  $salt = null;
  $password = null;
}



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