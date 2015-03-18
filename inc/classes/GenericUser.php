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

}


?>