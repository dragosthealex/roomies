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

if(!LOGGED_IN || !isset($_GET['a'], $_GET['pid'], $_GET['ptype'], $_SERVER['HTTP_ROOMIES'])
              || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include_once __ROOT__."/inc/html/notfound.php";
  exit();
}

try
{
  $response = array();
  switch ($_GET['a']) 
  {
    case 1:
      // Post some text somewhere (send a review/reply for now)
      $parentId = htmlentities($_GET['pid']);
      if(!isset($_POST['text']) && ($_GET['ptype']==1 && !isset($_POST['reply-input-'.$_GET['pid']])))
      {
        throw new Exception("Post must not be empty", 1);
      }
      if(!($_GET['pid'] && $_GET['ptype'] && is_numeric($_GET['pid'])))
      {
        throw new Exception('Some values are invlaid', 1);
      }

      if(isset($_POST['text']))
      {
        $text = $_POST['text'];
      }
      else if($_GET['ptype']==1 && isset($_POST['reply-input-'.$_GET['pid']]))
      {
        $text = $_POST['reply-input-'.$_GET['pid']];
      }

      //$text = isset($_POST['text']) ? $_POST['text'] : ($_GET['ptype']==1 && isset($_POST['reply-input-'.$_GET['pid']])) ? $_POST['reply-input-'.$_GET['pid']] : '';
      $type = htmlentities($_GET['ptype']);
      $text = htmlentities($text);
      // If some whitespace, don't do anything
      if(!trim($text))
      {
        echo json_encode($response);
        exit();
      }

      // make the post
      $response['generate'] = $user2->makePost($parentId, $text, $type);
      if($user2->getError())
      {
        throw new Exception("Error with posting: " . $user2->getError(), 1);
      }

      break;
    case 2:
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
    case 3:
    case 4:
      // Like/dislike a review or reply
      $like = $_GET['a'] == 4;
      $postId = htmlentities($_GET['pid']);
      $postType = htmlentities($_GET['ptype']);
      if(!$postId || !is_numeric($postId))
      {
        throw new Exception("The id of the post you want to like is invalid", 1);
      }
      $response['generate'] = $user2->like($postId, $postType, $like);
      if($user2->getError())
      {
        throw new Exception("Error liking post: " . $user2->getError(), 1);
      }
      break;
    case 5:
      // Rate accom
      $accomId = htmlentities($_GET['pid']);
      $rating = htmlentities($_GET['text']);
      if(!$accomId || !$rating || !is_numeric($rating))
      {
        throw new Exception("The accommodation Id or rate value is invalid", 1);
      }
      $response['generate'] = $user2->rateAccommodation($accomId, $rating);
      if($user2->getError())
      {
        throw new Exception("Error rating accommodation: " . $user2->getError(), 1);
      }
      break;
    case 6:
      // Delete a post
      $postId = htmlentities($_GET['pid']);
      $postType = htmlentities($_GET['ptype']);
      if(!$postId || !is_numeric($postId))
      {
        throw new Exception("A WHALE will eat you. The post id is invalid", 1);
      }
      if(!$user2->deletePost($postId))
      {
        throw new Exception("May the force be with you. Post $postId too stubborn to be deleted: " . $user2->getError(), 1); 
      }
      $response['success'] = "Post deleted";
      break;
    case 7:
      // Edit a post
      $postId = htmlentities($_GET['pid']);
      $postType = htmlentities($_GET['ptype']);
      if(!$postId || !is_numeric($postId))
      {
        throw new Exception("The program got scared. Id is invalid", 1);
      }
      if(!$user2->updatePost($postId, $text))
      {
        throw new Exception("Your fetishes are too weird for the post to be updated: " . $user2->getError(), 1);
      }
    default:
      throw new Exception("You tried to do an invalid action", 1);
      break;
  }
}
catch (Exception $e)
{
  $response['error'] = "Error processing your request: " . $e->getMessage();
  echo json_encode($response);
  exit();
}
echo json_encode($response);
?>