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
  // private $idColumn = 'review_id';
  // private $tableName = 'rrevies';
  // private $likesColumn = 'review_likes';
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
          $stmt = $con->prepare("INSERT INTO rreviews (review_author, review_acc_id, review_text, review_date)
                                 VALUES ('$author', '$accId', '$text', '$date')");
          if(!$stmt->execute())
          {
            throw new Exception("Error while inserting new review in database", 1);
          }
          // Get the id
          $id = $con->lastInsertId('review_id');

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
          $stmt = $con->prepare("SELECT * FROM rreviews WHERE review_id = $id");
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
          $this->likesArray = $result['review_likes'] ? explode(':', $result['review_likes']) : array();
          $this->likesNo = $result['review_likes_no'] ? $result['review_likes_no'] : 0;
          $this->date = $result['review_date'];
          $this->author = $result['review_author'];
          $this->parent = $result['review_acc_id'];
          $this->con = $con;
          $this->text = $result['review_text'];
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
    // Get the replies
    $stmt = $con->prepare("SELECT comment_id FROM rcomments WHERE comment_parent_id = '$reviewId'");
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
        array_push($replies, $reply->toJson());
      }

      // Close and Return the replies
      return json_encode($replies);
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
      $stmt = $con->prepare("SELECT review_likes, review_likes_no FROM rreviews  WHERE review_id = $id");
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
      $stmt = $con->prepare("UPDATE rreviews SET review_likes = '$likesArray', review_likes_no = review_likes_no+($liked) WHERE review_id = $id");
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
}// class Review

?>