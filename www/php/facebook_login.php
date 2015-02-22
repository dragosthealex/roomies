<?php
//stuff
require_once '../../inc/init.php';
require_once __ROOT__."/inc/functions/login_func.php";
// Initialise fb session
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;

$response = '';

if(isset($_POST['id'], $_POST['acc']))
{
  $helper = new FacebookJavaScriptLoginHelper();
  $msgErr = '';
  try 
  {
    $session = $helper->getSession();
  } 
  catch(FacebookRequestException $ex) 
  {
    // When Facebook returns an error
    $msgErr .= "Error from Facebook ";
  } 
  catch(\Exception $ex) 
  {
    // When validation fails or other local issues
    $msgErr .= "Sneaky, sneaky. Invalid credentials/parameters ";
  }
  if ($session) 
  {
    // Logged In
    $fbId = htmlentities($_POST['id']);
    try 
    {
      $response = (new FacebookRequest($session, 'GET', '/me'))->execute();
      $object = $response->getGraphObject();
      $fbEmail = $object->getProperty('email');
    } 
    catch (FacebookRequestException $ex) 
    {} 
    catch (\Exception $ex) 
    {}
    // See if user exists
    $stmt = $con->prepare("SELECT user_id FROM rusers WHERE facebook_id = $fbId OR user_email = '$fbEmail'");
    $stmt->execute();
    if(!$stmt->rowCount())
    {
      // We don't have this user yet, tell them to register
      $response = 'notInDb';
    }
    else
    {
      // We have found the user, log them in
      $stmt->bindColumn(1, $dbUserId);
      $stmt->fetch();
      loginUser($con, $dbUserId);

      $response = $dbUserId;
    }
  }
}
echo "{\"error\" : \"$msgErr\",
       \"response\" : \"$response\"}";

?>