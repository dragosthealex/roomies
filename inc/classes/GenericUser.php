<?php
/**
*Class GenericUser
*
* Represents a generic user, which can be a user or owner
*
*/
require_once __ROOT__.'/inc/classes/Base.php';
abstract class GenericUser extends Base
{
  // The username
  protected $username;
  // The email
  protected $email;
  // The rank
  protected $rank;
  // The name
  protected $name;
  // The settings for this user, assoc array
  protected $settings;
  // The image path
  protected $image;
  // The details of this user
  protected $details;
  // THE DB DETAILS
  const TABLE_NAME = 'undefined';
  const ID_COLUMN = 'undefined';
  const USERNAME_COLUMN = 'undefined';
  const EMAIL_COLUMN = 'undefined';
  const PASSWORD_COLUMN = 'undefined';
  const SALT_COLUMN = 'undefined';
  const SESSION_VAR = 'undefined';

  /**
  * Function getCredential($key)
  *
  * Returns id, email, username, rank, and any detail
  *
  * @return - $credential(String), the credential returned
  */
  public function getCredential($key)
  {
    switch ($key)
    {
      case 'id':
        return $this->id;
        break;
      case 'username':
        return $this->username;
        break;
      case 'email':
        return $this->email;
        break;
      case 'image':
        return $this->image;
        break;
      default:
        return isset($this->details[$key])?$this->details[$key]:'Wrong key';
        break;
    }
  }

  /**
  * Function login($password)
  *
  * Tries to login with this password. Sets the session Returns true if successful, false else
  *
  * @param - $password(String), the password
  * @return - $value(boolean), true if logged in
  */
  public function login($password)
  {
    // Localise stuff
    $con = $this->con;
    $id = $this->id;

    // Get salt
    $stmt = $con->prepare("SELECT " . $this::SALT_COLUMN . ", " . $this::PASSWORD_COLUMN . " ," . $this::EMAIL_COLUMN . ", " . $this::USERNAME_COLUMN . " FROM " . $this::TABLE_NAME . " WHERE " . $this::ID_COLUMN . "=$id");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting info for user $id", 1);
      }
      $stmt->bindColumn(1, $dbSalt);
      $stmt->bindColumn(2, $dbPass);
      $stmt->bindColumn(3, $email);
      $stmt->bindColumn(4, $username);
      $stmt->fetch();
      if(!$stmt->rowCount())
      {
        return false;
      }
      if(hash('sha256', $password . $dbSalt) == $dbPass)
      {
        $_SESSION[$this::SESSION_VAR]['id'] = $id;
        $_SESSION[$this::SESSION_VAR]['email'] = $email;
        $_SESSION[$this::SESSION_VAR]['username'] = $username;
      }        
      return hash('sha256', $password . $dbSalt) == $dbPass;
    }
    catch(Exception $e)
    {
      $this->errorMsg = $e->getMessage();
      return false;
    }
  }
}


?>