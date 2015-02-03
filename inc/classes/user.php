<?php
/**
* Class represents a user
*
* @author - Alex Radu
*/
class User()
{
  private $con;
  private $id;
  private $username;
  private $email;
  private $firstName;
  private $lastName;
  private $birthday;
  private $details;
  private $questions;

  /**
  * Constructor
  *
  * Assigns the id, username, email, first name, last name and birthday of the user
  * 
  * @param - $con, the connection to db
  * @param - $key, the key that is used for getting the user. Can be id, email or username
  */
  public __construct($con, $key)
  {
    $stmt = $con->prepare("SELECT user_id, username, user_email FROM rusers 
                            WHERE user_id = '$key' OR username = '$key' OR user_email = '$key'");
    $stmt->execute();
    $stmt->bindColumn(1,$id);
    $stmt->bindColumn(2,$username);
    $user_email->bindColumn(3,$email);
    $stmt->fetch();

    $this->id = $id;
    $this->username = $username;
    $this->email = $email;
    $this->con = $con;

    $stmt = $con->prepare("SELECT first_name, last_name, birthday FROM rdetails WHERE profile_filter_id = $id");
    $stmt->execute();
    $stmt->bindColumn(1,$firstName);
    $stmt->bindColumn(2,$lastName);
    $stmt->bindColumn(3,$birthday);
    $stmt->fetch();

    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->birthday = $birthday;

    $stmt = null;
  }

  /**
  * Function getDetails()
  *
  * Sets the details of the current user, and returns them as an array
  *
  * @return - $details, the associative array containing the details;
  */
  public function getDetails()
  {
    $con = $this->con;
    $stmt = $con->prepare("SELECT * FROM rdetails WHERE profile_filter_id =".$this->id);
    $stmt->execute();
    $details = $stmt->fetch(PDO::FETCH_ASSOC);

    $details[0] = '';
    $details[1] = '';
    $details[2] = '';
    $details[3] = '';
    $details[4] = '';

    $trueDetails = array();

    foreach ($details as $key => $value)
    {
      if($value)
      {
        $stmt = $con->prepare("SELECT $key FROM rfiltersmap WHERE filter_value = $value");
        $stmt->execute();
        $stmt->bindColumn(1,$filter);
        array_push($trueDetails, $filter);
      }
    }


    $this->details = $trueDetails;
    return $trueDetails;
  }


}

?>