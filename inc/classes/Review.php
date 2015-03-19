<?php
/**
* Review class
*
* Represents a kind of comment, specifically a direct review to an accommodation
*/
require_once __ROOT__."/inc/classes/Comment.php";

class Review extends Comment
{
  // The type of the post
  const TYPE = 0;
  // Overriding Post vars
  // private $idColumn = 'post_id';
  // private $tableName = 'rrevies';
  // private $likesColumn = 'post_likes';
  /**
  * Constructor
  *
  * Constructs a Review object. If $action is 'get', its $params['id'] should be an id, and it will get a review from db
  * If $action is 'insert', it will have $params['author'], ['accId'], ['text']
  *
  */
  public function __construct($con, $action, $params)
  {
    switch ($action) {
      case 'insert':
        // Validate the params
        $author = isset($params['author'])?htmlentities($params['author']):'';
        $accId = isset($params['accId'])?htmlentities($params['accId']):'';
        $text = isset($params['text'])?htmlentities($params['text']):'';
        $date = date('Y-m-d');

        try
        {
          // Check if all values are there
          if(!$author || !$accId || !$text)
          {
            throw new Exception("Author, id and text values are mandatory", 1);
          }

          // Insert into database
          $stmt = $con->prepare("INSERT INTO rposts (post_author, post_parent_id, post_text, post_date, post_type)
                                 VALUES ('$author', '$accId', '$text', '$date', " . Review::TYPE . ")");
          if(!$stmt->execute())
          {
            throw new Exception("Error while inserting new review in database", 1);
          }
          
          
          // Get the id
          $id = $con->lastInsertId('post_id');

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
          $stmt = $con->prepare("SELECT * FROM rposts WHERE post_id = $id AND post_type = " . Review::TYPE . "");
          $stmt->execute();

          // Something wrong if no accommodation with given id
          if(!$stmt->rowCount())
          {
            throw new Exception("No reviews with this id", 1);
          }
          // Fetch the result
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Set the instance vars
          $this->id = $id;
          $this->likesArray = $result['post_likes'] ? explode(':', $result['post_likes']) : array();
          $this->likesNo = $result['post_likes_no'] ? $result['post_likes_no'] : 0;
          $this->date = $result['post_date'];
          $this->author = $result['post_author'];
          $this->parent = $result['post_parent_id'];
          $this->con = $con;
          $this->text = $result['post_text'];
        }
        catch (Exception $e)
        {
          $this->setError($e->getMessage());
        }
        break;
      default:
        $this->setError("Weird input");
        break;
    }
  }// function __construct

  // Gets the replies for this review
  protected function getReplies()
  {
    // Localise stuff
    $con = $this->con;
    $reviewId = $this->id;
    $accId = $this->parent;
    $replies = array();
    $type = Reply::TYPE;
    // Get the replies
    $stmt = $con->prepare("SELECT post_id FROM rposts WHERE post_parent_id = '$reviewId' AND post_type = " . $type);
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting replies from database", 1);
      }

      // Go through every reply
      $stmt->bindColumn(1, $replyId);
      while($reply = $stmt->fetch())
      {
        // Prepare the params
        $params['id'] = $replyId;
        // Make new replies having the ids
        $reply = new Reply($con, 'get', $params);
        // Skip if we have any error with a reply
        if($reply->getError())
        {
          $this->errorMsg .= "Error with reply $replyId: " . $reply->getError();
          continue;
        }
        array_push($replies, json_decode($reply->toJson(), 1));
      }

      // Close and Return the replies
      return $replies;
    }
    catch (Exception $e)
    {
      $this->errorMsg = "Error with review $reviewId: " . $e->getMessage();
    }
  }// function getReplies()

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
      $stmt = $con->prepare("SELECT post_likes, post_likes_no FROM rposts  WHERE post_id = $id AND post_type = " . Review::TYPE . "");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting likes from table for post type " . Review::TYPE . ", $id", 1);
      }
      $stmt->bindColumn(1, $likesArray);
      $stmt->bindColumn(2, $likesNo);
      $stmt->fetch();

      // Turn string in array
      $likesArray= isset($likesArray[0])? explode(":", $likesArray) : array();

      // Return likes array and set the class var
      $this->likesArray = $likesArray;
      $this->likesNo = $likesNo;
      return $likesArray;
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
    $id = $this->id;

    try
    {
      // Update the likes array in class
      $this->likesArray = $likes;
      // Turn likes in string
      $likesArray= implode(":", $likes);

      // Update the table in db
      $stmt = $con->prepare("UPDATE rposts SET post_likes = '$likesArray', post_likes_no = post_likes_no+($liked) WHERE post_id = $id AND post_type = " . Review::TYPE);
      if(!$stmt->execute())
      {
        throw new Exception("Error updating likes for post type " . Review::TYPE . ", $id", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function setLikes
}// class Review

?>