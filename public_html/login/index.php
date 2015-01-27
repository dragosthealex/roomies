<?php
/*
Set REQUIRE_SESSION (false). Set REQUIRE_NO_SESSION (true). Initalise. 
Receive by POST 'email' and 'password'
Check the log table to see if brute-force attack (more than 5 wrong pass in last 2 hours)
-> if it is, show error (possibly blocking the account for time/sending email)
Check the password against the db pass
-> if it's valid, log in
-> if not valid, record in log table
*/

define('REQUIRE_SESSION', FALSE);
require_once '../../inc/init.php';
echo "shit";
// If these are set, proceed. Else, something wrong happened
if(isset($_POST['email'], $_POST['password']))
{
  $email = htmlentities(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
  $password = htmlentities(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

  // Check if valid chars
  if($email != $_POST['email'] || $password != $_POST['password'])
  {
    header("Location: ../?err=invalid");
    exit();
  }

  /*
  Check if brute-force attack. If user got the pass wrong over 5 times in last two hours
  then something is not right. So it will throw an error.
  */
  $stmt = $con->prepare("SELECT log_time FROM rlog WHERE log_email = '$email' LIMIT 1 OFFSET 4");
  $stmt->execute();
  $stmt->bindColumn(1, $time);
  $stmt->fetch();


  if($stmt->rowCount() == 1 && (strtotime($time) + 7200 > time()))
  {
    // Error acc blocked, must pass at least 2 hrs
    $stmt = null;
    $timeLeft = strtotime($time) + 7200 - time();
    header("Location: ../?err=locked&t=".$timeLeft);
    exit();
  }

  // Check the pass against the one in db. If incorrect, will be logged
  $stmt = $con->prepare("SELECT user_id, user_pass, username, user_salt FROM rusers WHERE user_email = '$email'");
  $stmt->execute();
  $stmt->bindColumn(1, $id);
  $stmt->bindColumn(2, $dbPassword);
  $stmt->bindColumn(3, $username);
  $stmt->bindColumn(4, $salt);
  $stmt->fetch();

  if(($stmt->rowCount() == 1) && (hash('sha256', $password.$salt) == $dbPassword))
  {
    // Successfully logged in
    $_SESSION['user']['id'] = $id;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['username'] = $username;

    $stmt = null;
    header("Location: ../");
    exit();
  }
  else
  {
    if($stmt->rowCount() == 1)
    {
      // The pass is wrong so log it
      $timeStamp = gmdate("Y-m-d H:i:s", time());
      $stmt = $con->prepare("INSERT INTO rlog (log_email, log_time) VALUES ('$email', '$timeStamp')");
      $stmt->execute();

      $stmt = null;
      header("Location: ../?err=incorrect");
      exit();
    }
    else
    {
      // No email was found. return with error
      //header("Location: ../?err=incorrect");
      exit();
    }
  }
}
else
{
  // Should go to 404
  echo "fuck";
}
?>