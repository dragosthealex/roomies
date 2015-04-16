<?php
/**
* Reply class
*
* Represents a reply, to a user. Extends the post, because it hase the same things, the parent being a review (possbile extension for this)
*/
require_once __ROOT__.'/inc/classes/Comment.php';

class Reply extends Comment
{
  // The type of the post
  const TYPE = 1;
  // Overriding Post vars
  // private $idColumn = 'post_id';
  // private $tableName = 'rposts';
  // private $likesColumn = 'post_likes';

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
        $date = date('Y-m-d');

        try
        {
          // Check if all values are there
          if(!$author || !$reviewId || !$text)
          {
            throw new Exception("All values are mandatory", 1);
          }

          // Insert into database
          $stmt = $con->prepare("INSERT INTO rposts (post_author, post_parent_id, post_text, post_date, post_type)
                                 VALUES ('$author', '$reviewId', '$text', '$date', ". Reply::TYPE . ")");
          if(!$stmt->execute())
          {
            throw new Exception("Error while inserting new reply in database", 1);
            
          }
          // Get the id
          $id = $con->lastInsertId('post_id');

          // Set the instance variables
          $this->id = $id;
          $this->con = $con;
          $this->author = $author;
          $this->parent = $reviewId;
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
          $stmt = $con->prepare("SELECT * FROM rposts WHERE post_id = $id AND post_type = " . Reply::TYPE . "");
          if(!$stmt->execute())
          {
            //throw new Exception("Error getting replies. fuck", 1);
            throw new Exception("SELECT * FROM rposts WHERE post_id = $id AND post_type = " . Reply::TYPE . "", 1);
          }

          // Something wrong if no accommodation with given id
          if(!$stmt->rowCount())
          {
            throw new Exception("No replies with this id", 1);
          }
          // Fetch the result
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Set the instance vars
          $this->id = $id;
          $this->likesNo = $result['post_likes_no'];
          $this->likesArray = $result['post_likes'] ? explode(':', $result['post_likes']) : array();
          $this->date = $result['post_date'];
          $this->author = $result['post_author'];
          $this->parent = $result['post_parent_id'];
          $this->con = $con;
          $this->text = nl2br($result['post_text']);

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

  /**
  * Public function stringPost()
  *
  * Returns this reply imediately after it's created
  * 
  * @return - $reply(String) - this reply as string
  */
  public function stringPost($user2)
  {
    // Localise stuff
    $con = $this->con;
    $text = nl2br($this->text);
    $id = $this->id;
    $likesNo = $this->likesNo;
    $replyLikes = isset($this->likesArray[0]) ? $this->likesArray : array();
    $date = $this->date;
    $authorId = $this->author;
    $author = new OtherUser($con, $authorId);
    $authorName = $author->getName();
    $authorImage = '../' . $author->getCredential('image');

    $likeHide = in_array($user2->getCredential('id'), $replyLikes) ? "hidden" : '';
    $dislikeHide = !in_array($user2->getCredential('id'), $replyLikes) ? "hidden" : '';
    // Construct the reply
    $reply = 
    "
    <li class='li reply' id='hide'>
      <div class='reply-pic' style='background-image: url($authorImage);background-size:cover; background-position:center;'>
      </div>
      <div class='reply-text'>
        <a class='link' href='../profile/$authorId'>$authorName</a> - $text
      </div>
      <div class='like-buttons'>
      <span class='minidrop-container like-button like-button-Reply$id $likeHide' id='likeReply$id'>
        <a data-ajax-url='../php/reviews.process.php?a=4&pid=$id&ptype=1'
           data-ajax-text='Liking...'
           data-ajax-hide='like-button-Reply$id dislikeReply$id'
           data-ajax-success='generate'
           data-generate-container='reply-$id-likesNo'
           class='' style='cursor:pointer;'>Like</a>
      </span>
      <span class='minidrop-container like-button like-button-Reply$id $dislikeHide' id='dislikeReply$id'>
        <a data-ajax-url='../php/reviews.process.php?a=3&pid=$id&ptype=1'
           data-ajax-text='Dislinking...'
           data-ajax-hide='like-button-Reply$id likeReply$id'
           data-ajax-success='generate'
           data-generate-container='reply-$id-likesNo'
           class='' style='cursor:pointer;'>Dislike</a>
      </span>
      | <a id='reply-$id-likesNo'>$likesNo</a> Likes | On $date
    </li>
    ";

    return $reply;
  }// function stringPost

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
      $stmt = $con->prepare("SELECT post_likes, post_likes_no FROM rposts  WHERE post_id = $id AND post_type = " . Reply::TYPE . "");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting likes from table for post type " . Reply::TYPE . ", $id", 1);
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
    $likesColumn = $this->likesColumn;
    $table = $this->tableName;
    $id = $this->id;

    try
    {
      // Update the likes array in class
      $this->likesArray = $likes;
      $this->likesNo = $this->likesNo + $liked;
      // Turn likes in string
      $likesArray= implode(":", $likes);

      // Update the table in db
      $stmt = $con->prepare("UPDATE rposts SET post_likes = '$likesArray', post_likes_no = post_likes_no+($liked) WHERE post_id = $id AND post_type = " . Reply::TYPE . "");
      if(!$stmt->execute())
      {
        throw new Exception("Error updating likes for post type " . Reply::TYPE . ", $id", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function setLikes
}// class Reply


?>