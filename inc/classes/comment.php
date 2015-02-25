<?php
/**
* Comment class
*
* Represents any type of comment. has an author, a parent (where it is posted), a date, a text, and a number of "likes"
*
*/
abstract class Comment
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
  // The error msg
  protected $errorMsg = '';

  /**
  * Function getError()
  *
  * Returns the error msg
  *
  * @return - $errorMsg(String), error message
  */
  public function getError()
  {
    return $this->errorMsg;
  }

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

    // Construct the json
    $json = "{\"id\"          : \"$id\",
              \"authorName\"  : \"$authorName\",
              \"authorId\"    : \"$authorId\",
              \"text\"        : \"$text\",
              \"likes\"       : \"$likes\",
              \"date\"        : \"$date\",
              \"parentId\"    : \"$parentId\",
             }";
    // Return it;
    return $json;
  }
}// class Comment
?>