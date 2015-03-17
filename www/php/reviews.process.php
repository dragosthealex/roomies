<?php
// Will process data sent by ajax
/*
gets vars:
action = (review/reply/like/dislike/rate)
pid = (accomId/reviewId)
ptype = (review/reply)
text = (reviewText/replyText/rating)
*/
require_once '../../inc/init.php';

if(!LOGGED_IN || !isset($_GET['action'], $_GET['pid'], $_POST['text'], $_GET['ptype'], $_SERVER['HTTP_ROOMIES'])
              || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

try
{
  $response = array();
  switch ($_GET['action']) 
  {
    case 'review':
      // Send a review
      $accId = htmlentities($_GET['pid']);
      $text = htmlentities($_POST['text']);
      if(!$accId || !is_numeric($accId))
      {
        throw new Exception("The accommodation Id is invalid", 1);
      }
      if(!$text)
      {
        throw new Exception("The review text is invalid", 1);
      }
      $user2->sendReview($accId, $text);
      if($user2->getError())
      {
        throw new Exception("Error with sending review: " . $user2->getError(), 1);
      }
      $response['success'] = "Review sent";
      break;
    case 'reply':
      // Send a reply to a review
      $reviewId = htmlentities($_GET['pid']);
      $text = htmlentities($_POST['text']);
      if(!$reviewId || !is_numeric($reviewId))
      {
        throw new Exception("The review Id you tried to reply on is invalid", 1);
      }
      if(!$text)
      {
        throw new Exception("The reply text is invalid", 1);
      }
      $user2->sendReply($reviwId, $text);
      if($user2->getError())
      {
        throw new Exception("Error with sending reply: " . $user2->getError(), 1);
      }
      break;
    case 'dislike':
    case 'like':
      // Like a review or reply
      $like = $action == 'like';
      $postId = htmlentities($_GET['pid']);
      $postType = htmlentities($_GET['ptype']);
      if(!$postId || !is_numeric($postId) || !$postType)
      {
        throw new Exception("The id of the post you want to like is invalid", 1);
      }
      $user2->like($postId, $postType, $like);
      if($user2->getError())
      {
        throw new Exception("Error liking post: " . $user2->getError(), 1);
      }
      break;
    case 'rate':
      $accomId = htmlentities($_GET['pid']);
      $rating = htmlentities($_POST['text']);
      if(!$accomId || !$rating || !is_numeric($rating))
      {
        throw new Exception("The accommodation Id or rate value is invalid", 1);
      }
      $user2->rateAccommodation($accomId, $rating);
      if($user2->getError())
      {
        throw new Exception("Error rating accommodation: " . $user2->getError(), 1);
      }
      break;
    default:
      throw new Exception("You tried to do an invalid action", 1);
      break;
  }
}
catch (Exception $e)
{
  $response['error'] = "Error processing your request: " . $e->getMessage();
}
echo json_encode($response);
?>