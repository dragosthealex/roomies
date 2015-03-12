<?php
/**
* Class OtherUser
*
* This represents the "other user", without the most of the stuff from current user
* 
*/
require_once __ROOT__.'/inc/classes/GeneralUser.php';
class OtherUser extends GeneralUser
{
  /**
  * Constructor
  *
  * Constructs other user, given the CON and the ID
  *
  * @param - $con(PDO object), the database handler
  * @param - $id, the user id
  */
  public function __construct($con, $key)
  {
    // Validate the input
    $id = ($key)?$key:"";

    try
    {
      if(!$key)
      {
        throw new Exception("Error. The id is empty", 1);
      }

      // Get the user from db
      $stmt = $con->prepare("SELECT user_id, username, user_email FROM rusers
                             WHERE user_id = '$key' 
                              OR user_email = '$key'
                              OR username = '$key'");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting the user from database", 1);
      }
      // Check if we have users with given key
      if(!$stmt->rowCount())
      {
        throw new Exception("Error. No user with given key", 1);
      }
      // Fetch from db
      $stmt->bindColumn(1, $id);
      $stmt->bindColumn(2, $username);
      $stmt->bindColumn(3, $email);
      $stmt->fetch();

      // Set the instance vars
      $this->id = $id;
      $this->username = $username;
      $this->email = $email;

      // Get the rest of the details as mapped ints from the db
      $stmt = $con->prepare("SELECT * FROM rdetails WHERE profile_filter_id =$id");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting details from database", 1);
      }
      $details = $stmt->fetch(PDO::FETCH_ASSOC);

      // Assign the unmapped details
      $this->name = $details['first_name'] . $details['last_name'];
      $this->birthday = $details['birthday'];
      // Assign the details
      $this->details = $details;

      $stmt = null;
    }// try
    catch (Exception $e)
    {
      $this->setError($e->getMessage());
    }
  }// function __construct()

  /**
  * Function getName(optional $friendshipStatus)
  *
  * Gets the name or the username of this user, depending on their privacy settings, and on the friendship status
  *
  * @param - $friendshipStatus(int), the friendship status
  * @return - $name(String), the username or name
  */
  public function getName($friendshipStatus = 0)
  {
    // Localise stuff
    $con = $this->con;
    $id = $this->id;

    // Search for privacy
    // TODO

    // Get for the status
    if($friendshipStatus == 1)
    {
      return $this->name;
    }
    else
    {
      return $this->username;
    }
  }
}// class OtherUser


?>