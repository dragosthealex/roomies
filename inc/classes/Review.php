<?php
/**
* Review class
*
* Represents a kind of comment, specifically a direct review to an accommodation
*/
require_once __ROOT__."/inc/classes/comment.php";

class Review extends Comment
{
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
        $date = date('d-m-Y');

        try
        {
          // Check if all values are there
          if(!$author || !$accId || !$text)
          {
            throw new Exception("Author, id and text values are mandatory", 1);
          }

          // Insert into database
          $stmt = $con->prepare("INSERT INTO rreviews (review_author, review_acc_id, review_text, review_date)
                                 VALUES ('$author', '$review_acc_id', '$text', '$date')");
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
          $this->likes = $result['review_rating'];
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
    $replies = "[\"\"";

    // Get the replies
    $stmt = $con->prepare("SELECT reply_id FROM rreplies WHERE parent_id = '$reviewId'");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting replies from database", 1);
      }

      // Go through every reply
      $stmt->bindColumn(1 $replyId);
      while($reply = $stmt->fetch())
      {
        // Prepare the params
        $params['id'] = $replyId;
        // Make new replies having the ids
        $reply = new Reply($con, 'get', $params);
        // Skip if we have any error with a reply
        if($reply->getError())
        {
          continue;
        }
        $replies .= $reply->toJson();
      }

      // Close and Return the replies
      $replies .= "]";
      return $replies;
    }
    catch (Exception $e)
    {
      $this->setError($e->getMessage());
    }
  }// function getReplies()
}// class Review

?>