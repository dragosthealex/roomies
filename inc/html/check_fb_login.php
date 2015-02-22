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

// Process the request, if values were sent through post
// Process just username, email, password. The others are processed by the normal script
if(isset($_SESSION['fbToken'], $_POST['registerPassword'], $_POST['registerConfirmPassword'], $_POST['registerUsername'])
  && $_POST['registerPassword'] && $_POST['registerConfirmPassword'] && $_POST['registerUsername']
  && (isset($_POST['first_name'],$_POST['last_name'],$_POST['b_year'],
         $_POST['b_month'],$_POST['b_day'],$_POST['country'],
         $_POST['language'],$_POST['gender'],$_POST['randomKey'],$_POST['city']))
  && ($_SESSION['randomKey'] == $_POST['randomKey']))
{
  // Initialise new fb session with the access token
  $fbAccessToken = $_SESSION['fbToken'];
  $session = new FacebookSession($fbAccessToken);
  try 
  {
    $session->validate();
    $fbSessionValid = true;

    // Get email from facebook
    $response = (new FacebookRequest($session, 'GET', '/me'))->execute();
    $object = $response->getGraphObject();
    $email = $object->getProperty('email');
    $fbId = $object->getProperty('id');

    $username = htmlentities(filter_input(INPUT_POST, 'registerUsername', FILTER_SANITIZE_STRING));
    $email = htmlentities(filter_input(INPUT_POST, 'registerEmail', FILTER_VALIDATE_EMAIL));
    $password1 = htmlentities(filter_input(INPUT_POST, 'registerPassword', FILTER_SANITIZE_STRING));
    $password2 = htmlentities(filter_input(INPUT_POST, 'registerConfirmPassword', FILTER_SANITIZE_STRING));

      // Check if invalid characters
    if($password1 != $_POST['registerPassword'] || $password2 != $_POST['registerConfirmPassword']
      || $email != $_POST['registerEmail'] || $username != $_POST['registerUsername'])
    {
      throw new Exception("You inserted invalid characters", 1);
    }

    // Check if confirm pass == pass
    if($password1 != $password2)
    {
      throw new Exception("Your password confirmation is not the same as your passowrd", 1);
    }

    // Check if existent username
    $stmt = $con->prepare("SELECT user_id, temp_user_id FROM rusers, rtempusers
                            WHERE username = $username OR temp_username = $username
                              OR user_email = $email OR temp_user_email = $email");
    $stmt->execute();
    if($stmt->rowCount())
    {
      throw new Exception("Username already exists. Please try another one", 1);
    }

    // Create random salt, hash the pass
    $salt = mt_rand();
    $password = hash('sha256', $password1.$salt);

    // Insert new user in db
    $stmt = $con->prepare("INSERT INTO rusers (username, user_email, user_salt, user_pass, facebook_id)
                            VALUES ('$username', '$email', '$salt', '$password', '$fbId')");
    $stmt->execute();
    // Get the id
    $stmt = $con->prepare("SELECT user_id FROM rusers WHERE user_email = '$email'");
    $stmt->execute();
    $stmt->bindColumn(1, $userId);
    $stmt->fetch();
    // Set the user in session
    $_SESSION['user']['id'] = $userId;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['username'] = $username;

  }
  catch (FacebookRequestException $ex) 
  {
    // Session not valid, Graph API returned an exception with the reason.
    array_push($errors, $ex->getMessage());
  }
  catch (\Exception $ex) 
  {
    // Graph API returned info, but it may mismatch the current app or have expired.
    array_push($errors, $ex->getMessage());
  }
  catch (Exception $exception)
  {
    array_push($errors, $ex->getMessage());
  }
}
else if(isset($_POST['randomKey']))
{
  array_push($errors, "All values must be filled");
}

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
  $_SESSION['fbToken'] = $fbAccessToken;
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
    array_push($errors, $ex->getMessage());
  }
  catch (\Exception $ex) 
  {
    // Graph API returned info, but it may mismatch the current app or have expired.
    array_push($errors, $ex->getMessage());
  }
}
define('FB_LOGGED_IN', "$fbSessionValid");
?>