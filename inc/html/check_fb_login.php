<?php
/*
Checks if the access token is valid
if it is, use the fb data to register the user
We have the following vars
$fbAccessToken
*/

require_once '../../inc/init.php';
use Facebook\FacebookRequest;
use Facebook\FacebookSession;

// Initialise with false
$fbSessionValid = false;
$fbEmail = "";
$fbBirthDay = array('', '', '');
$fbFirstName = '';
$fbLastName = '';
$fbGender = '';

if(isset($_GET['ref'], $_GET['tok']) && $_GET['ref'] == 'fb' && $_GET['tok'])
{
  $fbAccessToken = htmlentities($_GET['tok']);
  $session = new FacebookSession($fbAccessToken);
  // To validate the session:
  try 
  {
    $session->validate();
    $fbSessionValid = true;

    // Get values from facebook
    $response = (new FacebookRequest($session, 'GET', '/me'))->execute();
    $object = $response->getGraphObject();
    $fbEmail = $object->getProperty('email');
    $fbBirthDay = $object->getProperty('birthday');
    $fbBirthDay = ($fbBirthDay)?explode('/', $fbBirthDay):array('','','');
    $fbFirstName = $object->getProperty('first_name');
    $fbLastName = $object->getProperty('last_name');
    switch ($object->getProperty('gender')) 
    {
      case 'male':
        $fbGender = 'man';
        break;
      case 'female':
        $fbGender = 'woman';
        break;
      default:
        $fbGender = 'trans';
        break;
    };

  } 
  catch (FacebookRequestException $ex) 
  {
    // Session not valid, Graph API returned an exception with the reason.
    echo $ex->getMessage();
  }
  catch (\Exception $ex) 
  {
    // Graph API returned info, but it may mismatch the current app or have expired.
    echo $ex->getMessage();
  }
}
define('FB_LOGGED_IN', "$fbSessionValid");
?>