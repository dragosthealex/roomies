<?php
/**
* Comment class
*
* Represents any type of comment. has an author, a parent (where it is posted), a date, a text, and a number of "likes"
*
*/
require_once __ROOT__.'/inc/classes/base.php';
abstract class Comment extends Base
{
  // The id of the comment
  protected $id;
  // The id of the author
  protected $author;
  // The id of the parent
  protected $parent;
  // The date of the comment
  protected $date;
  // The text of the comment
  protected $text;
  // The number of likes
  protected $likes;

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
    $likes = $this->likes;

    // Get author name
    $author = new User($con, $authorId);
    $authorName = $author->getName();

    // Get the replies
    $replies = $this->getReplies();

    // Construct the json
    $jsonArray = array(
              "id"          => "$id",
              "authorName"  => "$authorName",
              "authorId"    => "$authorId",
              "text"        => "$text",
              "likes"       => "$likes",
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