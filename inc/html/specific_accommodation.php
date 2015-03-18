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
?>
<h2 class='h2'>
  <?=$accomInfo['name'];?>
</h2>
<div class='pic-wrapper'>
  <div class='pic-main' style='background-image: url(<?=$webRoot?>/media/img/acc/<?=$accomId?>.jpg)'>
  </div>
  <div class='scroll-wrapper pic-thumbs'>
    <div class='scroll-area'>
      <ul class='ul'>
        <li class='ph ph-last ph-pic' data-placeholder='No pictures.'></li>
      </ul>
    </div>
  </div>
</div>
  <div style="margin-bottom: 5px;">Rating: </div>
  <div class="ratings-box">
        <div onmouseover="barHover(this)" onmouseout="barUnHover(this)" class="star-background star-b0" style='width:<?=$accomInfo['rating'].'%'?>'></div>

        <input type="radio" class="star-cr" name="rating" id="rating1">
          <label for="rating1" onmouseover="barHover(this)" onmouseout="barUnHover(this)" class="star" data-hide="star-b0" on>
           
          </label>
        <input type="radio" class="star-cr" name="rating" id="rating2">
          <label for="rating2" onmouseover="barHover(this)" onmouseout="barUnHover(this)" class="star" data-hide="star-b0">
           
          </label>
        <input type="radio" class="star-cr" name="rating" id="rating3">
          <label class="star" onmouseover="barHover(this)" onmouseout="barUnHover(this)" for="rating3" data-hide="star-b0">
           
          </label>
        <input type="radio" class="star-cr" name="rating" id="rating4">
          <label class="star" onmouseover="barHover(this)" onmouseout="barUnHover(this)" for="rating4" data-hide="star-b0">
           
          </label>
        <input type="radio" class="star-cr" name="rating" id="rating5">
          <label class="star" onmouseover="barHover(this)" onmouseout="barUnHover(this)" for="rating5" data-hide="star-b0">
           
          </label>

        
  </div>

<div class='acc-long-desc'>
  <?=$accomInfo['description'];?>
</div>
<div class='acc-long-comments' style="margin-top:1em;">
  <?php include_once __ROOT__.'/inc/html/accommodation_reviews.php'; ?>
</div>
<div style="<?=($user2->hasReviewed($accomId))?'display:none;':''?>">
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
        data-ajax-url="../php/reviews.process.php?a=1&pid=<?=$accomId?>&ptype=acc"
        data-ajax-post="text">
</div>

<script type="text/javascript">
  var bar = document.getElementsByClassName('star-background');

  function barHover(el) {
    bar[0].style.background ='none';
  }

  function barUnHover(el) {
    bar[0].style.background ='#e37314';
  }
</script>
