<?php
/*
Represents a group of people
*/

class Group extends Base
{
  // The members of group, as array of OtherUsers objects
  private $members = array();
  // The name of the group
  private $name;
  // The description of group
  private $description;

  /**
  * Constructor
  *
  * 
  */
  public function __construct($con, $action, $params)
  {
    try
    {
      switch ($action)
      {
        case 'get':
          $groupId = $params['id'];

          // Get the group details
          $stmt = $con->prepare("SELECT * FROM rgroups WHERE group_id = $groupId");
          if(!$stmt->execute())
          {
            throw new Exception("Error getting group $groupId from databease. weird", 1);
          }
          $grpDetails = $stmt->fetch(PDO::FETCH_ASSOC);

          // Get grp users
          $usersInGrp = array();
          $stmt = $con->prepare("SELECT group_user_id FROM ruser_groups WHERE group_group_id = $groupId");
          if(!$stmt->execute())
          {
            throw new Exception("ERROR MOTHERFUCKER YEAAAAAAAAAH", 1);
          }
          $stmt->bindColumn(1, $grpUserId);
          while($stmt->fetch())
          {
            array_push($usersInGrp, $grpUserId);
          }

          // Assign the class vars
          $this->name = isset($grpDetails['group_name'])?$grpDetails['group_name']:'';
          $this->description = isset($grpDetails['group_description'])?$grpDetails['group_description']:'';
          $this->admin = isset($grpDetails['group_admin'])?$grpDetails['group_admin']:'';
          $this->members = $usersInGrp;
          $this->con = $con;
          $this->id = $groupId;
          break;
        case 'insert':
          $name = $params['name'];
          $description = $params['description'];
          $adminId = $params['admin'];

          // Insert grp
          $stmt = $con->prepare("INSERT INTO rgroups (group_name, group_description, group_admin) VALUES ($name, $description, $adminId)");
          if(!$stmt->execute())
          {
            throw new Exception("Error creting group $name", 1);
          }

          // Set class vars
          $this->name = $name;
          $this->description = $description;
          $this->admin = $adminId;
          $this->members = array($adminId);

          $this->addUser($adminId, 'admin');
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
  }

  /**
  * Function getDetails($key)
  *
  * Returns the detail, depending on key
  *
  * @param - $key(String), the key that specifies detail to be returned
  * @return - $value(mixed), the detail
  */
  public function getDetail($key)
  {
    return $this->$key;
  }

  /**
  * Function addUser($userId, $rank)
  *
  * Adds an user to this group
  *
  * @param - $userId(int), the id of the user to add
  * @param - $rank(String), the rank of the user to add
  */
  public function addUser($userId, $rank)
  {
    // Localise stuff
    $con = $this->con;
    $grpId = $this->id;

    try
    {
      // Add row in ruser_groups
      $stmt = $con->prepare("INSERT INTO ruser_groups (group_group_id, group_user_id, group_user_rank)
                              VALUES ($grpId, $userId, $rank)");
      if(!$stmt->execute())
      {
        throw new Exception("Error fucking user in the ass-group", 1);
      }
      // Add user to the members array
      array_push($this->members, $userId);
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }
}




?>