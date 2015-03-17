<?php
$reviews = $accomInfo['reviews'];
$userId = $user2->getCredential('id');

include_once __ROOT__.'/inc/classes/Reply.php';
include_once __ROOT__.'/inc/classes/Review.php';

$output = ' <div class="review-header">Reviews</div>';
// Loop through reviews

foreach ($reviews as $review) 
{ 
  $review = json_decode($review, true);
  // Localise stuff
  $authorId = $review['authorId'];
  $author = new OtherUser($con, $authorId);
  $authorName = $author->getName();
  $authorImage = $author->getCredential('image');
  $postDate = $review['date'];
  $postText = $review['text'];
  $postLikesNo = $review['likesNo'];
  $postLikes = isset($review['likesArray'][0]) ? $review['likesArray'] : array();
  $postReplies = isset($review['replies'][1]) ? json_decode($review['replies'],true) : array();
  $postRepliesNo = count($postReplies);
  $postId = $review['id'];

  $likeHide = in_array($userId, $postLikes) ? "style='display:none;'" : '';
  $dislikeHide = !in_array($userId, $postLikes) ? "style='display:none;'" : '';


  if($author->getError())
  {
    echo $author->getError();
    continue;
  } 

  $output .= 
  "
  <div class='review-box' id='review-$postId'>
    <div class='author-details'>
        <div class='review-pic' style='background-image: url($webRoot"."$authorImage); background-size:cover; background-position:center;'>
        </div>
        <div class='author-text'>
          <div class='author-name'>
            <a class='link' href='$webRoot/profile/$authorId'>$authorName</a>
          </div>
          <div class='date-text'>
            $postDate
          </div>
        </div>
    </div>
    <div class='review-text'>
      $postText
    </div>
    <div class='like-reply'>
      $postLikesNo 
      <span class='minidrop-container like-button like-button-review$postId' id='likeReview$postId' $likeHide>
        <a data-ajax-url='../php/reviews.process.php?a=4&pid=$postId&ptype=0'
           data-ajax-text='Liking...'
           data-ajax-hide='like-button-review$postId dislikeReview$postId'
           class=''>Like</a>
      </span>
      <span class='minidrop-container like-button like-button-review$postId' id='dislikeReview$postId' $dislikeHide>
        <a data-ajax-url='../php/reviews.process.php?a=3&pid=$postId&ptype=0'
           data-ajax-text='Dislinking...'
           data-ajax-hide='like-button-review$postId likeReview$postId'
           class=''>Dislike</a>
      </span>
      - $postRepliesNo Reply
    </div>
  ";
  if($postRepliesNo)
  {
    $output .= 
    "
    <div class='review-header' style='border-top: 1px solid #d5d1d0; padding-top: 5px; text-align: right; padding-right: 10px;'>
      <a class='click-me' onclick='toggleReply(this)'>Replies</a>
    </div>
    ";
  }

  $output .= "<div class='reply-box'>";
  // Loop through replies
  foreach ($postReplies as $reply)
  {
    $reply = json_decode($reply, true);
    //var_dump($reply);
    $replyId = $reply['id'];
    $replyAuthorId = $reply['authorId'];
    $replyAuthor = new OtherUser($con, $replyAuthorId);
    $replyAuthorImage = $replyAuthor->getCredential('image');
    $replyAuthorName = $replyAuthor->getName();
    if($replyAuthor->getError())
    {
      continue;
    }
    $replyLikesNo = $reply['likesNo'];
    $replyLikes =$reply['likesNo'] ? json_decode($reply['likes']) : array();
    $replyText = $reply['text'];
    $replyDate = $reply['date'];

    $likeDislike = in_array($userId, $replyLikes) ? 'Dislike' : 'Like';

    $output .=
    "
     <div class='reply' id='hide'>
      <div class='author-details'>
        <div class='reply-text'>
          <div class='reply-pic' style='background-image: url($webRoot"."$replyAuthorImage);background-size:cover; background-position:center;'>
          </div>
          <a class='link' href='$webRoot/profile/$replyAuthorId'>$replyAuthorName</a> - $replyText;
        </div>
      </div>
    </div>
    ";
  }// foreach
  $output .= "</div>";

  $output .="</div>";
}// foreach
echo $output;
?>

<script type="text/javascript">
  var hideAll = document.getElementsByClassName('reply-box');
  for(i = 0; i < hideAll.length; i ++)
  {
    hideAll[i].style.display = 'none';
  }

  function toggleReply(param) {
    var parent = param.parentNode;
    var nextItem = parent.nextElementSibling;
    if (nextItem.style.display == 'none') {
      nextItem.style.display = '';
    } else {
      nextItem.style.display = 'none';
    }
  }
</script>