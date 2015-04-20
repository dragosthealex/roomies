<?php
$reviews = $accomInfo['reviews'];
$userId = LOGGED_IN ? $user2->getCredential('id') : '';
$userImage = LOGGED_IN ? $user2->getCredential('image') : '';
include_once __ROOT__.'/inc/classes/Reply.php';
include_once __ROOT__.'/inc/classes/Review.php';

$output = "<ul class='ul' id='reviews'><li class='li review-header'><a name='reviews'>Reviews</a></li>";
// Loop through reviews

foreach ($reviews as $review) 
{ 
  // Localise stuff
  $authorId = $review['authorId'];
  $author = new OtherUser($con, $authorId);
  $authorName = $author->getName();
  $style = "background-size: cover;background-position: center center;";
  $authorImage = $author->generateProfilePicture('review-pic', $style);
  if($authorImage[0]=='/')$authorImage=$webRoot.$authorImage;
  $postDate = $review['date'];
  $postText = $review['text'];
  $postLikesNo = $review['likesNo'];
  $postLikes = isset($review['likesArray'][0]) ? $review['likesArray'] : array();
  $postReplies = isset($review['replies'][0]) ? $review['replies'] : array();
  $postRepliesNo = count($postReplies);
  $postId = $review['id'];

  $likeHide = in_array($userId, $postLikes) ? "hidden" : '';
  $dislikeHide = !in_array($userId, $postLikes) ? "hidden" : '';


  if($author->getError())
  {
    echo $author->getError();
    continue;
  } 

  $output .= 
  "
  <li class='li review-box' id='review-$postId'>
    <div class='author-details'>
        $authorImage
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
    ";
    if(LOGGED_IN)
    {
      $output .=
    "
    <div class='like-buttons like-reply'>
      <span class='minidrop-container like-button like-button-review$postId $likeHide' id='likeReview$postId'>
        <a data-ajax-url='$webRoot/php/reviews.process.php?a=4&pid=$postId&ptype='
           data-ajax-text='Liking...'
           data-ajax-hide='like-button-review$postId dislikeReview$postId'
           data-generate-container='review-$postId-likesNo'
           data-ajax-success='generate'
           class='' style='cursor:pointer;'>Like</a>
      </span>
      <span class='minidrop-container like-button like-button-review$postId $dislikeHide' id='dislikeReview$postId'>
        <a data-ajax-url='$webRoot/php/reviews.process.php?a=3&pid=$postId&ptype=0'
           data-ajax-text='Dislinking...'
           data-ajax-hide='like-button-review$postId likeReview$postId'
           data-generate-container='review-$postId-likesNo'
           data-ajax-success='generate'
           class='' style='cursor:pointer;'>Dislike</a>
      </span>
      - <a class='a' onclick=\"document.getElementById('reply-input-$postId').focus();\" href='#reply$postId' data-toggle='replies-container-$postId'>Reply</a> | <a id='review-$postId-likesNo'>$postLikesNo</a> Likes
    </div>
    ";
    }
  if($postRepliesNo)
  {
    $output .= 
    "
    <div class='review-header' style='border-top: 1px solid #d5d1d0; padding-top: 5px; text-align: right; padding-right: 10px;'>
      $postRepliesNo <a class='click-me' data-toggle='replies-container-$postId'>Replies</a>
    </div>
    ";
  }
  $output .= "<div id='replies-container-$postId' class='hidden'><ul id='reply-box-$postId' class='reply-box ul' style='padding-bottom:0.5em;'>";
  // Loop through replies
  foreach ($postReplies as $reply)
  {
    //var_dump($reply);
    $replyId = $reply['id'];
    $replyAuthorId = $reply['authorId'];
    $replyAuthor = new OtherUser($con, $replyAuthorId);
    $style = 'background-size:cover; background-position:center;';
    $replyAuthorImage = $replyAuthor->generateProfilePicture('reply-pic', $style);
    if($replyAuthorImage[0]=='/')$replyAuthorImage=$webRoot.$replyAuthorImage;
    $replyAuthorName = $replyAuthor->getName();
    if($replyAuthor->getError())
    {
      continue;
    }
    $replyLikesNo = $reply['likesNo'];
    $replyLikes =$reply['likesNo'] ? $reply['likesArray'] : array();
    $replyText = $reply['text'];
    $replyDate = $reply['date'];

    $likeHide = in_array($userId, $replyLikes) ? "hidden" : '';
    $dislikeHide = !in_array($userId, $replyLikes) ? "hidden" : '';

    $output .=
    "
    <li class='li reply' id='hide'>
      $replyAuthorImage
      <div class='reply-text'>
        <a class='link' href='$webRoot/profile/$replyAuthorId'>$replyAuthorName</a> - $replyText
      </div>
      <div class='like-buttons'>
    ";
    if(LOGGED_IN)
    {
      $output .=
      "
      <span class='minidrop-container like-button like-button-Reply$replyId $likeHide' id='likeReply$replyId'>
        <a data-ajax-url='$webRoot/php/reviews.process.php?a=4&pid=$replyId&ptype=1'
           data-ajax-text='Liking...'
           data-ajax-hide='like-button-Reply$replyId dislikeReply$replyId'
           data-ajax-success='generate'
           data-generate-container='reply-$replyId-likesNo'
           class='' style='cursor:pointer;'>Like</a>
      </span>
      <span class='minidrop-container like-button like-button-Reply$replyId $dislikeHide' id='dislikeReply$replyId'>
        <a data-ajax-url='$webRoot/php/reviews.process.php?a=3&pid=$replyId&ptype=1'
           data-ajax-text='Dislinking...'
           data-ajax-hide='like-button-Reply$replyId likeReply$replyId'
           data-ajax-success='generate'
           data-generate-container='reply-$replyId-likesNo'
           class='' style='cursor:pointer;'>Dislike</a>
      </span>
      | 
      ";
    }
    $output .=
    "
      <a id='reply-$replyId-likesNo'>$replyLikesNo</a> Likes | On $replyDate
    </li>
    ";
  }// foreach

  $hideIfLoggedOut = !LOGGED_IN ?'display:none;':'';
  $style = "background-size: cover;
background-position: center center;
width: 1.7em;
height: 1.7em;";
  $userImage = $user2->generateProfilePicture('reply-pic', $style);
  $output .= 
  "
  </ul><ul class='reply-box ul'>
  <li class='li reply reply-$postId' style='$hideIfLoggedOut;'>
    $userImage
    <textarea name='reply$postId' id='reply-input-$postId' class='input reply-input' type='text' placeholder='Write a reply...' oninput=\"this.style.height=((this.value.match(/\\n/g)||[]).length+2)*1.1+'em';return false;\" onkeydown=\"return event.shiftKey || ((event.keyCode === 13 && this.value.trim()) ? (window.onclick({button:1,target:this.nextSibling}), false) : event.keyCode !== 13);\"></textarea><button class='hidden' data-ajax-url='$webRoot/php/reviews.process.php?ptype=1&a=1&pid=$postId' data-ajax-post='reply-input-$postId' data-generate-container='_reply-box-$postId' data-ajax-success='generate'>
  </li></ul></div>
  ";

}// foreach
$output .= "</ul>";
echo $output;
?>

<script type="text/javascript">
  var hideAll = document.getElementsByClassName('reply-box');
  for(i = 0; i < hideAll.length; i ++)
  {
    //hideAll[i].style.display = 'none';
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