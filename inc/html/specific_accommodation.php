<?php
/*
Displays a specific accommodation
*/
require_once __ROOT__.'/inc/classes/Accommodation.php';
require_once __ROOT__.'/inc/classes/PokeBall.php';
if(isset($_GET['i']))
{
  $accomId = htmlentities($_GET['i']);
}
else
{
  $stmt = $con->prepare("SELECT accommodation_id FROM raccommodations ORDER BY accommodation_rating LIMIT 1");
  try
  {
    if(!$stmt->execute())
    {
      throw new PokeBall("Error getting accommodations from db", 1);
    }
    $stmt->bindColumn(1, $accomId);
    $stmt->fetch();
    if(!$stmt->rowCount())
    {
      throw new PokeBall("No accommodations in db", 1);
      
    }
  }
  catch (PokeBall $p)
  {
    echo $p->getMessage();
  }
}
// Set teh params for getting accom
$params['id'] = $accomId;
$accom = new Accommodation($con, 'get', $params);
if(!$accom->getError())
{
  $accomInfo = json_decode($accom->toJson(), 1);
}
else
{
  echo $accom->getError();
}

$ratingsArray = $accomInfo['ratingsArray'];
// Check if I have rated this
if (LOGGED_IN && in_array($user2->getCredential('id'), $ratingsArray[0]))
{
  foreach ($ratingsArray[0] as $key => $id)
  {
    // If this is my rating
    if($ratingsArray[0][$key] == $user2->getCredential('id'))
    {
      $myRating = ($ratingsArray[1][$key]/100.0)*5.0;
      break;
    }
  }
}
else
{
  $myRating = '';
}
$rating = $accomInfo['rating'] ? ($accomInfo['rating']/100.0)*5.0 : 'N/A';
// Get the optional pics
$opt_pics = '';
for($index=0; $index<$accomInfo['noOfPhotos']; $index++)
{
  $opt_pics .= 
  "
  <li class='li'>
    <div onclick=\"document.getElementById('pic-main').style='background-image: url($webRoot/media/img/acc/$accomId-" . $index . ");'\" class='scroll-area-pic' style='background-image:url(../media/img/acc/" . $accomInfo['id'] . "-" . $index . ")'></div>
  </li>
  ";
}
if($opt_pics)
{
  $opt_pics .= 
  "
  <li class='li'>
    <div onclick=\"document.getElementById('pic-main').style='background-image: url($webRoot/media/img/acc/$accomId);'\" class='scroll-area-pic' style='background-image:url(../media/img/acc/" . $accomInfo['id'] . ")'></div>
  </li>
  "; 
}
?>
<h2 class='h2'>
  <?=$accomInfo['name'];?>
</h2>
<div class='pic-wrapper'>
  <div id='pic-main' class='pic-main' style='background-image: url(<?=$webRoot?>/media/img/acc/<?=$accomId?>.jpg)'>
  </div>
  <div class="scroll-wrapper pic-thumbs">
    <div id="main-conversation-scroll" class='scroll-area'>
      <ul class='ul'>
        <?php if(!$accomInfo['noOfPhotos']){?><li class='ph ph-last ph-pic' data-placeholder='No pictures.'></li><?php }?>
        <?=$opt_pics?>
      </ul>
      <div class=" scroll-bar ">
        <div class=" scroll-tracker "></div>
      </div>
    </div>
  </div>
</div>
<div id='rating-container' style="margin-bottom: 5px;">Rating: <a class="rating-text"><?=$rating?></a>
  <?php if($myRating){?>My rating:<a class="rating-text"><?=$myRating?></a><?php }?>
</div>
<div class="ratings-box"
  onmouseover="firstChild.style.background='none'"
  onmouseout="firstChild.style.background='#e37314'"
  ><div class="star-background star-b0 star-main" style='width:<?=$accomInfo['rating'].'%'?>'></div>
  <?php
  $ajaxText = LOGGED_IN ? "star-main' data-ajax-success='generate' data-generate-container='rating-container' data-ajax-url='$webRoot/php/reviews.process.php?a=5&pid=$accomId&ptype=&text=" : "data-null='";
  $sh = !LOGGED_IN ? "sh-" : '';
  ?>
  <input class="<?=$sh?>star-cr" type="radio" name="rating" id="rating5" value="5"
  ><label class="<?=$sh?>star-label" for="rating5"><span class="star" data-hide='<?=$ajaxText?>5'></span></label>
  <input class="<?=$sh?>star-cr" type="radio" name="rating" id="rating4" value="4"
  ><label class="<?=$sh?>star-label" for="rating4"><span class="star" data-hide='<?=$ajaxText?>4'></span></label>
  <input class="<?=$sh?>star-cr" type="radio" name="rating" id="rating3" value="3"
  ><label class="<?=$sh?>star-label" for="rating3"><span class="star" data-hide='<?=$ajaxText?>3'></span></label>
  <input class="<?=$sh?>star-cr" type="radio" name="rating" id="rating2" value="2"
  ><label class="<?=$sh?>star-label" for="rating2"><span class="star" data-hide='<?=$ajaxText?>2'></span></label>
  <input class="<?=$sh?>star-cr" type="radio" name="rating" id="rating1" value="1"
  ><label class="<?=$sh?>star-label" for="rating1"><span class="star" data-hide='<?=$ajaxText?>1'></span></label>
</div>
<div class='acc-long-desc'>
  <?=$accomInfo['description'];?>
</div>
<div class='acc-long-comments' style="margin-top:1em;">
  <?php include_once __ROOT__.'/inc/html/accommodation_reviews.php'; ?>
</div>
<div class='acc-review-box' style="<?=(!LOGGED_IN || $user2->hasReviewed($accomId))?'display:none;':''?>">
<div class="textarea-holder" style="margin-top:1em;)"
  ><textarea class="textarea" id="text" placeholder="Write a review..."
             oninput="this.style.height=((this.value.match(/\n/g)||[]).length+2)*1.3+'em';return false"
             onkeydown="return !this.parentNode.nextSibling.firstChild.checked || (event.shiftKey || ((event.keyCode === 13 && this.value.trim()) ? (window.onclick({button:1,target:this.parentNode.nextSibling.nextSibling}), false) : event.keyCode !== 13));"></textarea>
</div
><label for="pressEnterToSend" class="cr-label"
  ><input type="checkbox" id="pressEnterToSend" onchange="var b=this.parentNode.nextSibling,c=b.className;b.className=this.checked?c+'hidden ':c.replace(' hidden ', ' ');rCookie.set('pressEnterToSend',this.checked,Infinity,'/')" class="cr">
  <span class="cr-button"></span>
  <span class="cr-text">Press Enter to send</span>
</label
><input type="submit" class="input-button block " value="Send"
        data-ajax-url="<?=$webRoot?>/php/reviews.process.php?a=1&pid=<?=$accomId?>&ptype=acc"
        data-ajax-post="text "
        data-hide="acc-review-box "
        data-ajax-success="generate"
        data-generate-container="_reviews ">
</div>
