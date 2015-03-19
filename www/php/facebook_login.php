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
    $stmt = $con->prepare("SELECT user_id, facebook_id FROM rusers WHERE facebook_id = $fbId OR user_email = '$fbEmail'");
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
      $stmt->bindColumn(2, $dbFbId);
      $stmt->fetch();
      loginUser($con, $dbUserId);

      // If we don't have the fb id, insert it
      if(!$dbFbId)
      {
        $fbImage = "https://graph.facebook.com/$fbId/picture?type=large";
        $stmt = $con->prepare("UPDATE rusers SET facebook_id = $fbId, image_url = '$fbImage' WHERE user_email = $fbEmail");
        $stmt->execute();
      }
      $response = $dbUserId;
    }
  }
}
echo "{\"error\" : \"$msgErr\",
       \"response\" : \"$response\"}";

?>