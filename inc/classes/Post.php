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
  // The name of the table
  protected $tableName;
  // The name of the likes column
  protected $likesColumn;
  // The name of the id column
  protected $idColumn;
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
        if(!in_array($userId, $likesArray))
        {
          throw new Exception("Apparently you have already disliked this post. Good luck with this attitude, fgt", 1);
        }
        foreach ($likesArray as $key => $userId)
        {
          if($likesArray[$key] == $userId)
          {
            unset($likesArray[$key]);
          }
        }
      }
      $liked = $value ? 1 : -1;
      // Update the database with new array
      $this->setLikes($likesArray, $liked);
    }// try
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function like

  // private function to get likes
  abstract protected function getLikes();

  // private function to set likes
  abstract protected function setLikes($likesArray, $liked);
  
}// class Post


?>