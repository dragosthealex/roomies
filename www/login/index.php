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
require_once __ROOT__.'/inc/functions/login_func.php';

// If these are set, proceed. Else, something wrong happened
if(isset($_POST['login'], $_POST['password']))
{

  $login = htmlentities($_POST['login']);
  $password = htmlentities(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

  // Check if valid chars
  if($login != $_POST['login'] || $password != $_POST['password'])
  {
    header("Location: ../?err=invalid");
    exit();
  }

  /*
  Check if brute-force attack. If user got the pass wrong over 5 times in last two hours
  then something is not right. So it will throw an error.
  */
  $stmt = $con->prepare("SELECT log_time FROM rlog WHERE log_email = '$login'
                          OR log_username = '$login'  LIMIT 1 OFFSET 4");
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

  $user = new User($con, $login);

  if ($_POST['rememberMe'])
  {
    include_once __ROOT__."/inc/html/cookie_set.php";
  }
  
  loginUser($con, $login, $password);
}
else
{
  // Should go to 404
  echo "fuck not found";
}
?>