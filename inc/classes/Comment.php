<?php
/**
* Comment class
*
* Represents any type of comment. has an author, a parent (where it is posted), a date, a text, and a number of "likes"
*
*/
require_once __ROOT__.'/inc/classes/Post.php';
abstract class Comment extends Post
{
  // The id of the author
  protected $author;
  // The id of the parent
  protected $parent;
  // The date of the comment
  protected $date;
  // The text of the comment
  protected $text;

  /**
  * Function toJson()
  *
  * Returns the contents as a json
  *
  * @return - $json(String), the json string
  */
  public function toJson()
  {
    // Localise stuff
    $id = $this->id;
    $authorId = $this->author;
    $parentId = $this->parent;
    $date = $this->date;
    $text = $this->text;
    $likesNo = $this->likesNo;
    $likesArray = isset($this->likesArray[0])?$this->likesArray:array();
    $con = $this->con;

    try
    {
      // Get author name
      $author = new OtherUser($con, $authorId);
      if($author->getError())
      {
        throw new Exception("Error getting author with id $authorId. Strange sh*t goin' on: " . $author->getError(), 1); 
      }
      $authorName = $author->getName();

      // Get the replies
      $replies = $this->getReplies();
      if($this->getError())
      {
        throw new Exception("nyanyanyanyanayanyanyan. Error getting replies for post $id " . $this->getError(), 1);
      }
      // Construct the json
      $jsonArray = array(
                "id"          => "$id",
                "authorName"  => "$authorName",
                "authorId"    => "$authorId",
                "text"        => "$text",
                "likesNo"     => "$likesNo",
                "likesArray"  => $likesArray,
                "date"        => "$date",
                "parentId"    => "$parentId",
                "replies"     => $replies);
      
      // Return it;
      return json_encode($jsonArray);
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }

  // Gets the replies of this comment as an array string
  abstract protected function getReplies();

}// class Comment
?>