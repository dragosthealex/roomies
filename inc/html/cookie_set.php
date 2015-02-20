<?php
/*
This sets a cookie for the user when they login
We can assume that we already have the $user object, the $con, and every stuff
*/

$ip = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];
$cookieToken = hash('sha256', $ip.$browser);
$expire = time() + 60*60*24*30*12;
$domain = $_SERVER['SERVER_NAME'];
// Set the cookie
$userId = $user->getIdentifier('id');
setcookie('login', $cookieToken, $expire, '/', $_SERVER['SERVER_NAME'], FALSE, TRUE);
setcookie('userId', $userId, $expire, '/', $_SERVER['SERVER_NAME'], FALSE, TRUE);
// Get the current cookies in the table
$stmt = $con->prepare("SELECT user_cookie FROM rusers WHERE user_id = '$userId'");
$stmt->execute();
$stmt->bindColumn(1, $dbCookies);
$stmt->fetch();

// Get cookies and push the current one
$dbCookies = explode(':', $dbCookies);
array_push($dbCookies, $cookieToken);
$dbCookies = implode(":", $dbCookies);

// Insert the cookie in the table
$stmt = $con->prepare("UPDATE rusers SET user_cookie = '$dbCookies' WHERE user_id = '$userId'");
$stmt->execute();

?>