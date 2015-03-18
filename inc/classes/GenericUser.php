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
}


?>