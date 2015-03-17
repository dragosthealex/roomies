<?php
/**
* Abstract class Post
*
* Represents a postable object
*
*/
require_once __ROOT__.'/inc/classes/Base.php';
abstract class Post extends Base
{
  // The type of the post
  const TYPE = -1;
  // The name of the table
  private $tableName;
  // The name of the likes column
  private $likesColumn;
  // The name of the id column
  private $idColumn;
  // The array containing user that liked this post
  protected $likesArray;
  // The number of likes
  protected $likesNo;

  /**
  * Function like($postId, $value)
  *
  * Likes this post if value is true, dislikes else
  * @param - $postId(int), the post to be liked
  * @param - $value(boolean), true to like, false to dislike
  */
  public function like($postId, $value)
  {
    // Localise stuff
    $con = $this->con;
    $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';

    try
    {
      // If the user is not in session it's weird
      if(!$userId)
      {
        throw new Exception("Error getting user from session when trying to like/dislike. You a sneaky one", 1);
      }
      // Gets the likes array and update it correspondingly
      $likesArray= $this->getLikes();
      if($value)
      {
        array_push($likesArray, $userId);
      }
      else
      {
        if(!isset($likes[$userId]))
        {
          throw new Exception("Apparently you have already disliked this post. Good luck with this attitude, fgt", 1);
        }
        unset($likes[$userId]);
      }
      // Update the database with new array
      $this->updateLikes();
    }// try
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function like

  // private function to get likes
  private function getLikes()
  {
    // Localise stuff
    $con = $this->con;
    $likesColumn = $this->likesColumn;
    $table = $this->tableName;
    $id = $this->id;

    try
    {
      // Get the likes array from db
      $stmt = $con->prepare("SELECT $likesColumn FROM $tableName  WHERE $idColumn = $id");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting likes from table for post type " . TYPE . ", $id", 1);
      }
      $stmt->bindColumn(1, $likesArray);
      $stmt->fetch();

      // Turn string in array
      $likesArray= $likesArray? explode(":", $likesArray) : array();

      // Return likes array and set the class var
      $this->likes = $likes;
      return $likes;
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function getLikes

  // private function to set likes
  protected function setLikes($likesArray)
  {
    // Localise stuff
    $con = $this->con;
    $likesColumn = $this->likesColumn;
    $table = $this->tableName;
    $id = $this->id;

    try
    {
      // Update the likes array in class
      $this->likes = $likes;
      // Turn likes in string
      $likesArray= implode(":", $likes);

      // Update the table in db
      $stmt = $con->prepare("UPDATE $tableName SET $likesColumn = '$likesArray' WHERE $idColumn = $id");
      if(!$stmt->execute())
      {
        throw new Exception("Error updating likes for post type " . TYPE . ", $id", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function setLikes
}// class Post


?>