<?php

require_once __ROOT__.'/inc/classes/Owner.php';
require_once __ROOT__.'/inc/classes/TempOwner.php';
// Takes $login which can be id, username, 
function loginUser($con, $login, $password='', $remember=false)
{
  
  //TODO : MAKE DB CON OBJ 
    
  // Check the pass against the one in db. If incorrect, will be logged
  $stmt = $con->prepare("SELECT user_id, user_pass, username, user_email, user_salt FROM rusers
                          WHERE user_email = '$login' OR username = '$login' OR user_id = '$login'");
  $stmt->execute();
  $stmt->bindColumn(1, $id);
  $stmt->bindColumn(2, $dbPassword);
  $stmt->bindColumn(3, $username);
  $stmt->bindColumn(4, $email);
  $stmt->bindColumn(5, $salt);
  $stmt->fetch();

  if(($stmt->rowCount() == 1) && ((hash('sha256', $password.$salt) == $dbPassword) || $password == ''))
  {
    // Successfully logged in
    $_SESSION['user']['id'] = $id;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['username'] = $username;
    $_SESSION['justLoggedIn'] = true; // For header.in.php

    // Set cookie, if remember me
    if ($remember)
    {
      include_once __ROOT__."/inc/html/cookie_set.php";
    }

    // Check whether the user has completed his profile
    $stmt = $con->prepare("SELECT profile_filter_id FROM rdetails WHERE profile_filter_id = $id");
    $stmt->execute();
    $stmt->bindColumn(1, $profileId);
    $stmt->fetch();

    if(!$stmt->rowCount())
    {
      // The user has to complete his profile
      $stmt = null;
      $_SESSION['notComplete'] = true;
      header("Location: ../complete-register/");
      exit();
    }

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
      $stmt = $con->prepare("INSERT INTO rlog (log_email, log_time, log_username)
                              VALUES ('$email', '$timeStamp', '$username')");
      $stmt->execute();

      $stmt = null;
      header("Location: ../?err=incorrect1");
      exit();
    }
    else
    {
      // No email was found. Check temp users
      $stmt = $con->prepare("SELECT temp_username, temp_pass, temp_salt, conf, temp_email
                              FROM rtempusers
                              WHERE temp_email = '$login' OR temp_username = '$login'");
      $stmt->execute();
      $stmt->bindColumn(1, $tempUsername);
      $stmt->bindColumn(2, $tempDbPassword);
      $stmt->bindColumn(3, $tempSalt);
      $stmt->bindColumn(4, $confCode);
      $stmt->bindColumn(5, $tempEmail);
      $stmt->fetch();

      if(($stmt->rowCount() == 1) && (hash('sha256', $password.$tempSalt) == $tempDbPassword))
      {
        // The user is in temp table, so send him to conf page
        $_SESSION['tempUser']['username'] = $tempUsername;
        $_SESSION['tempUser']['conf'] = $confCode;
        $_SESSION['tempUser']['email'] = $tempEmail;

        $stmt = null;
        header("Location: ../confirm/");
        exit();
      }
      else
      {
        // Check if owner
        $stmt = $con->prepare("SELECT owner_id FROM rowners WHERE owner_id = '$login' OR owner_email = '$login' OR owner_username = '$login'");
        $stmt->execute();
        if(!$stmt->rowCount())
        {
          // Check if temp owner
          $stmt = $con->prepare("SELECT temp_id FROM rtempowners WHERE temp_id = '$login' OR temp_username = '$login' OR temp_email = '$login'");
          $stmt->execute();
          if(!$stmt->rowCount())
          {

            // The user does not exist at all
            $stmt = null;
            header("Location: ../?err=incorrect2");
            exit();
          }
          $stmt->bindColumn(1, $tempId);
          $stmt->fetch();
          $tempOwner = new TempOwner ($con, 'get', array('id' => $tempId));
          // Try to login. If false, pass incorrect
          if($tempOwner->getError() || !$tempOwner->login($password) || $tempOwner->getError())
          {
            header("Location: ../?err=incorrect1" . $tempOwner->getError());
            exit();
          }
          // Logged in
          header('Location: ../register-owner');
          exit();
        }
        // Else check if valid owner
        $stmt->bindColumn(1, $ownerId);
        $stmt->fetch();
        $owner = new Owner($con, 'get', array('id' => $ownerId));
        if($owner->getError() || !$owner->login($password) || $owner->getError())
        {
          header("Location: ../?err=incorrect1" . $owner->getError());
          exit();
        }
        // Logged in
        header('Location: ../');
        exit();
      }
    }
  }
}