<?php
/**
* Class Owner
*
* Represents an owner of one or more accommodations (properties)
*
*/

class Owner extends GenericUser
{
  // The array of accommodations owned
  protected $accommodations;
  // The db thingies
  const TABLE_NAME = 'rowners';
  const ID_COLUMN = 'owner_id';
  const USERNAME_COLUMN = 'owner_username';
  const EMAIL_COLUMN = 'owner_email';
  const PASSWORD_COLUMN = 'owner_password';
  const SALT_COLUMN = 'owner_salt';
  const SESSION_VAR = 'owner';
  const FOREIGN_KEY_COLUMN = '';

  /**
  * Constructor 
  *
  * Constructs an owner. If $action is 'get', $params should contain id/username/email, and the respective owner will be created.
  * If $action is 'insert', a new owner will be inserted into table
  *
  * @param - $con(PDO), the db connection handler
  * @param - $action(String), can be 'get' or 'insert', depending on which the owner is got or inserted
  * @param - $params(mixed array), if $action is 'get', must have either ['id'], ['email'] or ['username']
  *                                if $action is 'insert', must have:
  *                                 ['username']
  *                                 ['email']
  *                                 ['password']
  *                                 ['first_name']
  *                                 ['last_name']
  *                                 ['mobile_phone']
  *                                 ['country']
  *                                 ['city']
  *                                 ['post_code']
  *                                 ['gender']
  *                                 ['birthday']
  */
  function __construct($con, $action, $params)
  {
    try
    {
      switch ($action) 
      {
        case 'get':
          // Check the params
          $key = isset($params['id'])?htmlentities($params['id']):(isset($params['email'])?htmlentities($params['email']):(isset($params['username'])?htmlentities($params['username']):''));
          if(!$key)
          {
            throw new Exception("Error getting owner. Invalid key", 1);
          }

          // Get from db
          $stmt = $con->prepare("SELECT * FROM rowners WHERE owner_id = $key OR owner_email = '$key' OR owner_username = '$key'");
          if(!$stmt->execute())
          {
            throw new Exception("Error getting owner $key", 1);
          }
          $details = $stmt->fetch(PDO::FETCH_ASSOC);

          // Assign class vars
          $this->id = isset($details['owner_id']) ? $details['owner_id'] : '';
          $this->username = isset($details['owner_username']) ? $details['owner_username'] : '';
          $this->email = isset($details['owner_email']) ? $details['owner_email'] : '';
          $this->name = isset($details['owner_first_name'], $details['owner_last_name']) ? $details['owner_first_name'] . ' ' . $details['owner_last_name'] : '';
          $this->rank = 'owner';
          $this->image = isset($details['owner_img_url']) ? $details['owner_img_url'] : '';
          $this->con = $con;

          // Validate
          if(!$this->id || !$this->username || !$this->email || !$this->name)
          {
            throw new Exception("Problem with owner in database. Have no idea", 1);
            
          }
          break; 
        case 'insert':
          // Check params
          $password = isset($params['password']) ? htmlentities($params['password']) : '';
          $salt = isset($params['salt']) ? htmlentities($params['salt']) : '';
          $username = isset($params['username']) ? htmlentities($params['username']) : '';
          $email = isset($params['email']) ? htmlentities($params['email']) : '';
          $firstName = isset($params['first_name']) ? htmlentities($params['first_name']) : '';
          $lastName = isset($params['last_name']) ? htmlentities($params['last_name']) : '';
          $phone = isset($params['phone']) ? htmlentities($params['phone']) : '';
          $country = isset($params['country']) ? htmlentities($params['country']) : '';
          $city = isset($params['city']) ? htmlentities($params['city']) : '';
          $postCode = isset($params['post_code']) ? htmlentities($params['post_code']) : '';
          $gender = isset($params['gender']) ? htmlentities($params['gender']) : '';
          $birthday = isset($params['birthday']) ? htmlentities($params['birthday']) : '';

          // Validate
          if(!$password || !$username || !$email || !$firstName || !$lastName || !$phone || !$country || !$city || !$postCode || !$gender || !$birthday)
          {
            throw new Exception("Empty values are not accepted. Error creating new owner", 1);
          }

          // Insert new owner into db
          $stmt = $con->prepare("INSERT INTO rowners (owner_email, owner_username, owner_password, owner_last_name, owner_first_name, owner_salt, owner_post_code, owner_gender, owner_birthday, owner_country, owner_phone)
                                 VALUES ('$email', '$username', '$password', '$lastName', '$firstName', '$salt', '$postCode', '$gender', '$birthday', '$country', '$phone')");
          if(!$stmt->execute())
          {
            throw new Exception("INSERT INTO rowners (owner_email, owner_username, owner_password, owner_last_name, owner_first_name, owner_salt, owner_post_code, owner_gender, owner_birthday, owner_country, owner_phone)
                                 VALUES ('$email', '$username', '$password', '$lastName', '$firstName', '$salt', '$postCode', '$gender', '$birthday', '$country', '$phone')", 1);
          }

          // Get from db
          $stmt = $con->prepare("SELECT * FROM rowners WHERE owner_username = '$username'");
          if(!$stmt->execute())
          {
            throw new Exception("Error getting owner $key", 1);
          }
          $details = $stmt->fetch(PDO::FETCH_ASSOC);

          // Assign class vars
          $this->id = isset($details['owner_id']) ? $details['owner_id'] : '';
          $this->username = isset($details['owner_username']) ? $details['owner_username'] : '';
          $this->email = isset($details['owner_email']) ? $details['owner_email'] : '';
          $this->name = isset($details['owner_first_name'], $details['owner_last_name']) ? $details['owner_first_name'] . ' ' . $details['owner_last_name'] : '';
          $this->rank = 'owner';
          $this->image = isset($details['owner_img_url']) ? $details['owner_img_url'] : '';
          $this->con = $con;

          // Validate
          if(!$this->id || !$this->username || !$this->email || !$this->name)
          {
            throw new Exception($this->id . ' ' . $this->username . ' ' . $this->email . ' ' . $this->name .'shit', 1);
          }
          unset($_SESSION['tempOwner']);
          $_SESSION['owner']['id'] = $this->id;
          $_SESSION['owner']['username'] = $this->username;
          $_SESSION['owner']['email'] = $this->email;
          break;
        default:
          throw new Exception("Weird thingie. dk. owner.", 1);
          
          break;
      }//switch
    }// try
    catch(Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }// catch
  }// function __construct

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
  }// function getCredential

  /**
  * Function getName
  *
  * Returns this owner's name
  * @return - $name(String), the name
  */
  public function getName($friendshipStatus=0)
  {
    return $this->details['first_name'] . ' ' . $this->details['last_name'];
  }

  // private function that gets accommodations for this owner from db
  private function setAccommodations()
  {
    // Localise stuff
    $con = $this->con;
    $ownerId = $this->id;

    // Get the accom id from db and create new accom objects
    $stmt = $con->prepare("SELECT " . Accommodation::ID_COLUMN . " FROM " . Accommodation::TABLE_NAME . " WHERE " . Accommodation::FOREIGN_KEY_COLUMN . " = $ownerId ");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting accommodations for owner $ownerId", 1);
      }
      $stmt->bindColumn(1, $accId);
      $accommodations = array();
      // Loop through every accommodation
      while($stmt->fetch())
      {
        $acc = new Accommodation($con, 'get', array('id' => $accId));
        if($acc->getError())
        {
          $this->errorMsg .= " Error with accommodation $accId: " . $acc->getError();
          continue;
        }
        array_push($accommodations, $acc);
      }
      // Set accom
      $this->accommodations = $accommodations;
    }// try
    catch(Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function setAccommodations

  /**
  * Function getAccommodations($offset)
  *
  * Returns all accomodations as json
  * @return - $accommodations(Json String), the accommodations
  */
  public function getAccommodations($offset=0)
  {
    if(!isset($this->accommodations[0]))
    {
      $this->setAccommodations();
      if($this->getError())
      {
        $this->errorMsg = "Error setting accommodations for this owner: " . $this->getError();
        return json_encode("{\"error\" : \"Error setting accommodations for this owner: " . $this->getError() . "\"}");
      }
    }
    // Return the accommodations between $offset and $offset+10
    $accommodationsJson = array();
    for($index=$offset; $index<$offset+10, $index<count($this->accommodations); $index++)
    {
      $acc = $this->accommodations[$index];
      $accJson = $acc->toJson();
      if($acc->getError())
      {
        $this->errorMsg .= "Error with accommodation " . $acc->getId() . ": " . $acc->getError();
        continue;
      }
      // Decode and push in array
      $accJson = json_decode($accJson, 1);
      array_push($accommodationsJson, $accJson);
    }
    // Encode
    $accommodationsJson = json_encode($accommodationsJson);
    return $accommodationsJson;
  } // function getAccommodation()
  


}// class Owner
?>