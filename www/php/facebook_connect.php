<?php
require_once '../../inc/init.php';
require_once __ROOT__."/inc/functions/login_func.php";
// Initialise fb session
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;

if(!LOGGED_IN)
{
  include __ROOT__.'/inc/html/notfound.php';
  exit();
}


$response = array();
$response['error'] = '';
try
{
  if(!isset($_POST['id'], $_POST['acc']) || !$_POST['id'] || !$_POST['acc'] || $_POST['a'] == '')
  {
    throw new Exception("Id or access token invalid", 1);
  }

  $helper = new FacebookJavaScriptLoginHelper();

  try 
  {
    $session = $helper->getSession();
  } 
  catch(FacebookRequestException $ex) 
  {
    // When Facebook returns an error
    $response['error'] .= "Error from Facebook ";
  } 
  catch(\Exception $ex) 
  {
    // When validation fails or other local issues
    $response['error'] .= "Sneaky, sneaky. Invalid credentials/parameters ";
  }

  if (isset($session) && $session) 
  {
    // Logged In
    $fbId = htmlentities($_POST['id']);
    
    if($_POST['a'] == 0)
    {
      $request = new FacebookRequest(
                                  $session,
                                  'DELETE',
                                  '/me/permissions'
                                  );

      $response1 = $request->execute();
      $graphObject = $response1->getGraphObject();
      
      // Delete id from db
      if(!$user2->disconnect('facebook'))
      {
        throw new Exception("Problem disconnecting account", 1);
      }

      $response['success'] = json_decode($graphObject, 1)['success'];
    }
    else if($_POST['a'] == 1)
    {
      // Try to get user details
      try 
      {
        $response1 = (new FacebookRequest($session, 'GET', '/me'))->execute();
        $object = $response1->getGraphObject();
        $fbEmail = $object->getProperty('email');
      } 
      catch (FacebookRequestException $ex) 
      {} 
      catch (\Exception $ex) 
      {}

      // The fb image
      $fbImage = "https://graph.facebook.com/$fbId/picture?type=large";
      $id = $user2->getCredential('id');
      // Update details
      $stmt = $con->prepare("UPDATE rusers SET facebook_id = $fbId, image_url = '$fbImage' WHERE user_id = $id");
      if(!$stmt->execute())
      {
        throw new Exception("Weird thing in db setting fb id and image", 1);
      }
      $response['success'] = true;
    }// else
    else
    {
      throw new Exception("Weird action", 1);
    }
  }// if(session)
  else
  {
    throw new Exception("Error with session", 1);
  }
}
catch (Exception $e)
{
  $response['error'] .= $e->getMessage();
  $response['success'] = false;
}
echo json_encode($response);
?>