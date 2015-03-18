<?php
/**
* Class TempOwner
*
* Represents a temporary owner
*/
include_once __ROOT__.'/inc/init.php';
include_once __ROOT__.'/inc/classes/Owner.php';
class TempOwner extends GenericUser
{
  // The password
  private $password;
  // The conf
  private $conf;
  // The salt
  private $salt;

  const TABLE_NAME = 'rtempowners';
  const ID_COLUMN = 'temp_owner_id';
  const USERNAME_COLUMN = 'temp_owner_username';
  const EMAIL_COLUMN = 'temp_owner_email';
  const PASSWORD_COLUMN = 'temp_owner_password';
  const SALT_COLUMN = 'temp_owner_salt';
  const SESSION_VAR = 'temp_owner';
  /**
  * Constructor
  *
  * Constructs a temporary owner, given the params and the thingie
  *
  */
  public function __construct($con, $action, $params)
  {
    try
    {
      switch ($action)
      {
        case 'get':
          // Validate params
          $key = isset($params['id'])?htmlentities($params['id']):isset($params['email'])?htmlentities($params['email']):isset($params['username'])?htmlentities($params['username']):'';
          if(!$key)
          {
            throw new Exception("Error getting owner. Invalid key", 1);
          }

          // Get details from db
          $stmt = $con->prepare("SELECT * FROM rtempowners WHERE temp_id = '$key' OR temp_email = '$key' OR temp_username = '$key'");          
          if(!$stmt->execute())
          {
            throw new Exception("Error with getting temp owner $key", 1);
          }
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set class vars
          $this->con = $con;
          $this->id = isset($result['temp_id']) ? $result['temp_id'] : '';
          $this->username = isset($result['temp_username']) ? $result['temp_username'] : '';
          $this->email = isset($result['temp_email']) ? $result['temp_email'] : '';
          $this->salt = isset($result['temp_salt']) ? $result['temp_salt'] : '';
          $this->password = isset($result['temp_pass']) ? $result['temp_pass'] : '';
          $this->conf = isset($result['conf']) ? $result['conf'] : '';
          $detailsString = isset($result['temp_details']) ? $result['temp_details'] : array();
          // Make the details array
          $detailsString = explode(':', $detailsString);
          $detailsString[0] = $detailsString[0] ? explode(',', $detailsString[0]) : array();
          $detailsString[1] = $detailsString[1] ? explode(',', $detailsString[1]) : array();
          $details = array();
          foreach ($detailsString[0] as $key => $value)
          {
            $details[$value] = $detailsString[1][$key];
          }
          $this->details = $details;

          // Validate
          if(!$this->id || !$this->username || !$this->email || !$this->salt || !$this->password || !$this->conf)
          {
            throw new Exception("Error setting the details for temp owner $key", 1);
          }
          break;
        case 'insert':
          // Validate params
          $username = isset($params['username']) ? htmlentities($params['username']) : '';
          $password = isset($params['password']) ? htmlentities($params['password']) : '';
          $email = isset($params['email']) ? htmlentities($params['email']) : '';
          $salt = isset($params['salt']) ? htmlentities($params['salt']) : '';
          $details = isset($params['details']) ? $params['details'] : '';
          // Create conf
          $conf = substr(mt_rand(), 0, 6);

          if(!$username || !$password || !$email || !$salt || !$conf || !$details)
          {
            throw new Exception("All values must be set.", 1);
          }

          // Insert in db
          $stmt = $con->prepare("INSERT INTO rtempowners (temp_pass, temp_email, temp_username, temp_salt, conf, temp_details)
                                  VALUES ('$password', '$email', '$username', '$salt', '$conf', '$details')");
          if(!$stmt->execute())
          {
            throw new Exception("Error inserting new temp owner in db", 1);
          }

          // Set class vars
          $this->username = $username;
          $this->password = $password;
          $this->email = $email;
          $this->conf = $conf;
          $this->salt = $salt;
          // Make details array
          $detailsString = explode(':', $details);
          $detailsString[0] = $detailsString[0] ? explode(',', $detailsString[0]) : array();
          $detailsString[1] = $detailsString[1] ? explode(',', $detailsString[1]) : array();
          $details = array();
          foreach ($detailsString[0] as $key => $value)
          {
            $details[$value] = $detailsString[1][$key];
          }
          $this->details = $details;

          // Send the email and set the temp owner in session
          $this->sendConf();
          if($this->getError())
          {
            throw new Exception("Problem with setting up temp owner: " . $this->getError(), 1);
          }

          break;
        default:
          # code...
          break;
      }// switch
    }// try
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function __construct

  // Private function to send mail
  private function sendConf()
  {
    // Localise stuff
    $email = $this->email;
    $username = $this->username;
    $conf = $this->conf;

    // Send mail to user with conf code, disabled for now
    /*

    $to = "$email";
    $subject = "Confirmation Token";

    $message = "Hello, dear user,<br><br> Here is your confirmation token. Please copy
                it in the confirmation box and submit.<br>    $conf<br><br>Regards,
                 Roomies team.

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <webmaster@roomies.co.uk >';

    if(!mail($to, $subject, $message, $headers))
    {
      $this->errorMsg = "Problem with sending mail";
    }
    */

    // Save temp user into session
    $_SESSION['tempOwner']['email'] = $email;
    $_SESSION['tempOwner']['username'] = $username;
    $_SESSION['tempOwner']['conf'] = $conf;
  }// function __construct

  /**
  * Function confirm($code)
  *
  * Tries to confirm this user with the given code
  *
  * @param - $code(int), the code that is to be verified;
  */
  public function confirm($code)
  {
    // Localise shit
    $con = $this->con;
    $tempId = $this->id;

    // Check code against the one in db
    $stmt = $con->prepare("SELECT temp_id FROM rtempowners WHERE temp_id = $tempId AND conf = $code");
    try
    {      
      if(!$stmt->execute())
      {
        throw new Exception("WHEIRD, not everyone is master of php",1);
      }
      $stmt->bindColumn(1, $dbId);
      $stmt->fetch();
      if($stmt->rowCount())
      {
        // Set new owner
        $details = $this->details;
        $details['username'] = $this->username;
        $details['password'] = $this->password;
        $details['email'] = $this->email;
        $details['salt'] = $this->salt;
        $details['birthday'] = $details['b_year'] . '-' . $details['b_month'] . '-' . $details['b_day'];

        $newOwner = new Owner($con, 'insert', $details);
        if($newOwner->getError())
        {
          throw new Exception("Error creating new owner user: " . $newOwner->getError(), 1);
        }
        $this->delete();
        return true;
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
      return false;
    }
  }// function confirm

  // Function to delete this user
  public function delete()
  {
    // Localise stuff
    $con = $this->con;
    $tempId = $this->id;

    // Delete from table
    $stmt = $con->prepare("DELETE FROM rtempowners WHERE temp_id = '$tempId' ");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error deleting temp owner", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function delete
}

?>