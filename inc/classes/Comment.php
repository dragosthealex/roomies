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

    // Get author name
    $author = new OtherUser($con, $authorId);
    $authorName = $author->getName();

    // Get the replies
    $replies = $this->getReplies();

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
              "replies"     => "$replies");
    
    // Return it;
    return json_encode($jsonArray);
  }

  // Gets the replies of this comment as an array string
  abstract protected function getReplies();

}// class Comment
?>