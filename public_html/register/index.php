<?php
/*
Set REQUIRE_SESSION (false). Set REQUIRE_NO_SESSION (true). Initalise. Receive
possible _POST['email'], _POST['password1'], and _POST['password2'] from
register form. Validate and check against database. If valid, create unique
validation code, add this to temp table with email and encrypted password, send
mail for validation (for now, just header to
../confirm?code=<confirmationcode>), and output confirm message. If any
invalid, output error messages along with form.
*/

define('REQUIRE_SESSION', FALSE);
require_once '../../inc/init.php';
require_once '../../inc/classes/form.php';
require_once '../../inc/classes/input.php';

// If these are set, proceed. Else, something wrong happened
if(isset($_POST['email'], $_POST['password1'], $_POST['password2'], $_POST['username']))
{
  $username = htmlentities(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
  $email = htmlentities(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
  $password1 = htmlentities(filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING));
  $password2 = htmlentities(filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING));

  // Check if confirm pass == pass
  if($password1 != $password2)
  {
    header('Location :/?err=confpass');
    exit();
  }

  // Check if email existing
  $stmt = $con->preprare("SELECT user_id, temp_id, username, temp_username FROM rusers, rtempusers 
                          WHERE email = $email");
  $stmt->execute();
  if($stmt->rowCount() == 1)
  {
    header('Location :/?err=emailexists');
    exit();
  }

  // Create random salt, hash the pass
  $salt = mt_rand();
  $password = hash('sha256', $password1.$salt);


  // Create confirmation code, insert user in temp table
  $confCode = substr(mt_rand(), 0, 6);

  $stmt = $con->prepare("INSERT INTO rtempusers (temp_email, temp_username, temp_pass, temp_salt, conf) 
                         VALUES ($email, $username, $password, $salt, $confCode)");
  $stmt->execute();

  // Save temp user into session
  $_SESSION['tempUser']['email'] = $email;
  $_SESSION['tempUser']['username'] = $username;
  $_SESSION['tempUser']['conf'] = $confCode;

  // Send mail to user with conf code, disabled for now
  /*
  
  $to = "$email";
  $subject = "Confirmation Token";
  
  $message = "Hello, dear user,<br><br> Here is your confirmation token. Please copy
              it in the confirmation box and submit.<br>    $confCode<br><br>Regards,
               Roomies team.
  
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: <webmaster@roomies.co.uk >';
  
  if(!mail($to, $subject, $message, $headers))
  {
    $error_msg .= "Problem with sending mail";
  }
  */

  // Send user to confirm page with correct conf code. Enabled for testing
  header('Location: /confirm.php?conf='.$confCode);
  exit();
}












?>