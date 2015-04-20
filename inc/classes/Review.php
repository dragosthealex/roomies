<?php
/**
* Review class
*
* Represents a kind of comment, specifically a direct review to an accommodation
*/
//require_once '../init.php';
require_once __ROOT__."/inc/classes/Comment.php";
include_once __ROOT__.'/inc/lib/banbuilder-master/src/CensorWords.php';
use Snipe\BanBuilder\CensorWords;

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

          $censor = new CensorWords();
          $censor->setReplaceChar("*");
          $text = $censor->censorString($text);

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
          $this->text = nl2br($result['post_text']);
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


  /**
  * Public function stringPost()
  *
  * Returns this review imediately after it's created
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
    $replyLikes = isset($this->likesArray[0])?$this->likesArray:array();
    $date = $this->date;
    $authorId = $this->author;
    $author = new OtherUser($con, $authorId);
    $hideIfLoggedOut = 'hidden';

    if(LOGGED_IN)
    {
      $userImage = $user2->generateProfilePicture('reply-pic', 'background-size:cover; background-position:center;width:1.7em;height:1.7em;');
      $hideIfLoggedOut = '';
    } 
    else
    {
      $userImage = '';
    }

    $webRoot = '..';
    if($author->getError())
    {
      $this->errorMsg = "Error with the author for post $id: " . $author->getError();
      return 0;
    }
    $style = 'background-size: cover;background-position: center center;';
    $authorName = $author->getName();
    $authorImage = $author->generateProfilePicture('review-pic', $style);

    $likeHide = in_array($user2->getCredential('id'), $replyLikes) ? "hidden" : '';
    $dislikeHide = !in_array($user2->getCredential('id'), $replyLikes) ? "hidden" : '';
    // Construct the reply
    $review =
    "
    <li class='li review-box' id='review-$id'>
      <div class='author-details'>
          $authorImage
          <div class='author-text'>
            <div class='author-name'>
              <a class='link' href='../profile/$authorId'>$authorName</a>
            </div>
            <div class='date-text'>
              $date
            </div>
          </div>
      </div>
      <div class='review-text'>
        $text
      </div>
      <div class='like-buttons like-reply'>
        <span class='minidrop-container like-button like-button-review$id $likeHide' id='likeReview$id'>
          <a data-ajax-url='../php/reviews.process.php?a=4&pid=$id&ptype='
             data-ajax-text='Liking...'
             data-ajax-hide='like-button-review$id dislikeReview$id'
             data-generate-container='review-$id-likesNo'
             data-ajax-success='generate'
             class='' style='cursor:pointer;'>Like</a>
        </span>
        <span class='minidrop-container like-button like-button-review$id $dislikeHide' id='dislikeReview$id'>
          <a data-ajax-url='../php/reviews.process.php?a=3&pid=$id&ptype=0'
             data-ajax-text='Dislinking...'
             data-ajax-hide='like-button-review$id likeReview$id'
             data-generate-container='review-$id-likesNo'
             data-ajax-success='generate'
             class='' style='cursor:pointer;'>Dislike</a>
        </span>
        <a class='a' onclick=\"document.getElementById('reply-input-$id').focus()\" href='#reply$id' data-toggle='replies-container-$id'>Reply</a> | <a id='review-$id-likesNo'>$likesNo</a>  Likes
      </div>
      <div id='replies-container-$id' class='hidden'><ul id='reply-box-$id' class='reply-box ul' style='padding-bottom:0.5em;'>
        </ul><ul class='reply-box ul'>
          <li class='li reply reply-$id' style='$hideIfLoggedOut;'>
            $userImage
            <textarea name='reply$id' id='reply-input-$id' class='input reply-input' type='text' placeholder='Write a reply...' oninput=\"this.style.height=((this.value.match(/\\n/g)||[]).length+2)*1.1+'em';return false;\" onkeydown=\"return event.shiftKey || ((event.keyCode === 13 && this.value.trim()) ? (window.onclick({button:1,target:this.nextSibling}), false) : event.keyCode !== 13);\"></textarea><button class='hidden' data-ajax-url='$webRoot/php/reviews.process.php?ptype=1&a=1&pid=$id' data-ajax-post='reply-input-$id' data-generate-container='_reply-box-$id' data-ajax-success='generate'>
          </li>
        </ul>
      </div>
    ";
    return $review;
  }// function stringPost

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
      $this->likesNo = $this->likesNo + $liked;
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