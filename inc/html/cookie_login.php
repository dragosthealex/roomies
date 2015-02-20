<?php
/*
Tries to login with a cookie
We assume we have all variables from inc set
*/
require_once __ROOT__.'/inc/functions/login_func.php';

if(isset($_COOKIE['login'], $_COOKIE['userId']) && $_COOKIE['login'] && $_COOKIE['userId'])
{
  $rememberedCookieToken = $_COOKIE['login'];

  $ip = $_SERVER['REMOTE_ADDR'];
  $browser = $_SERVER['HTTP_USER_AGENT'];
  $currentCookieToken = hash('sha256', $ip.$browser);

  if($currentCookieToken === $rememberedCookieToken)
  {
    $userId = $_COOKIE['userId'];
    $stmt = $con->prepare("SELECT user_cookie FROM rusers WHERE user_id = '$userId'");
    $stmt->execute();
    $stmt->bindColumn(1, $dbCookies);
    $stmt->fetch();

    $dbCookies = explode(':', $dbCookies);

    // Check if the same as in db
    if(in_array($currentCookieToken, $dbCookies))
    {
      // Login
      loginUser($con, $userId);
    }
  }
}


?>