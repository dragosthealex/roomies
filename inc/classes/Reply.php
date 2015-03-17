<?php
/**
* Reply class
*
* Represents a reply, to a user. Extends the comment, because it hase the same things, the parent being a review (possbile extension for this)
*/
require_once __ROOT__.'/inc/classes/Comment.php';

class Reply extends Comment
{
  // The type of the post
  const TYPE = 1;
  // Overriding Post vars
  // private $idColumn = 'comment_id';
  // private $tableName = 'rcomments';
  // private $likesColumn = 'comment_likes';

  /**
  * Constructor
  *
  * Constructs a Reply object. If $action is 'get', its $params['id'] should be an id, and it will get a reply from db
  * If $action is 'insert', it will have $params['author'], ['reviewId'], ['text']
  *
  */
  public function __construct($con, $action, $params)
  {
    // Set table, likes column
    switch ($action)
    {
      case 'insert':
        // Validate the params
        $author = isset($params['author'])?htmlentities($params['author']):'';
        $reviewId = isset($params['reviewId'])?htmlentities($params['reviewId']):'';
        $text = isset($params['text'])?htmlentities($params['text']):'';
        $date = date('d-m-Y');

        try
        {
          // Check if all values are there
          if(!$author || !$accId || !$text)
          {
            throw new Exception("All values are mandatory", 1);
          }

          // Insert into database
          $stmt = $con->prepare("INSERT INTO rreplies (reply_author, reply_review_id, reply_text, reply_date)
                                 VALUES ('$author', '$reviewId', '$text', '$date')");
          if(!$stmt->execute())
          {
            throw new Exception("Error while inserting new review in database", 1);
          }
          // Get the id
          $id = $con->lastInsertId('reply_id');

          // Set the instance variables
          $this->id = $id;
          $this->con = $con;
          $this->author = $author;
          $this->parent = $accId;
          $this->date = $date;
          $this->text = $text;
        }
        catch (Exception $e)
        {
          $this->setError($e->getMessage());
        }
        break;
      case 'get':
        // Validate the id
        $id = isset($params['id'])?htmlentities($params['id']):'';

        // Get from db
        // Get the details from db
        try
        {
          $stmt = $con->prepare("SELECT * FROM rreplies WHERE reply_id = $id");
          $stmt->execute();

          // Something wrong if no accommodation with given id
          if(!$stmt->rowCount())
          {
            throw new Exception("No replies with this id", 1);
          }
          // Fetch the result
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Set the instance vars
          $this->id = $id;
          $this->likesNo = $result['reply_likesNo'];
          $this->likesArray = $result['reply_likes'] ? explode(':', $result['reply_likes']) : array();
          $this->date = $result['reply_date'];
          $this->author = $result['reply_author'];
          $this->parent = $result['reply_review_id'];
          $this->con = $con;
          $this->text = $result['review_text'];
        }
        catch (Exception $e)
        {
          $this->errorMsg = $e->getMessage();
        }
        break;
      default:
        $this->setError("Weird input");
      break;
    }
  }
  // Gets the replies for this reply. To be implemented
  protected function getReplies()
  {
    return "[\"\"]";   
  }

  protected function getLikes()
  {
    // Localise stuff
    $con = $this->con;
    $likesColumn = $this->likesColumn;
    $table = $this->tableName;
    $id = $this->id;

    try
    {
      // Get the likes array from db
      $stmt = $con->prepare("SELECT comment_likes, comment_likes_no FROM rcomments  WHERE comment_id = $id");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting likes from table for post type " . TYPE . ", $id", 1);
      }
      $stmt->bindColumn(1, $likesArray);
      $stmt->bindColumn(2, $likesNo);
      $stmt->fetch();

      // Turn string in array
      $likesArray= isset($likesArray[0])? explode(":", $likesArray) : array();
      // Return likes array and set the class var
      $this->likesArray = $likesArray;
      $this->likesNo = $likesNo;
      return $likes;
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function getLikes

  protected function setLikes($likes, $liked)
  {
    // Localise stuff
    $con = $this->con;
    $likesColumn = $this->likesColumn;
    $table = $this->tableName;
    $id = $this->id;

    try
    {
      // Update the likes array in class
      $this->likesArray = $likes;
      // Turn likes in string
      $likesArray= implode(":", $likes);

      // Update the table in db
      $stmt = $con->prepare("UPDATE rcomments SET comment_likes = '$likesArray', comment_likes_no = comment_likes_no+$liked WHERE comment_id = $id");
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
}// class Reply


?>