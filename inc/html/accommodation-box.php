<?php $accId = $acc['id']; $accomId = $accId?>
<div class="box">
<div class="box-padding">
<li class='li' style='overflow: hidden's>
  <div class="accom-box-title" style="margin:0">
    <a style='margin: 5px;' href='<?=$webRoot?>/accommodation/?i=<?=$accId?>' class='link'>
      <?=$acc['name']?>
    </a>

    <div class="ratings-box accom-box-rating" style="margin-bottom:0px;"
      ><div class="star-background star-b0" style='width:<?=$acc['rating'].'%'?>'></div>
      <input class="sh-star-cr" type="radio" name="rating" id="rating5-<?=$accId?>" value="5">
      <label class="sh-star-label" for="rating5-<?=$accId?>"><span class="star"></span></label>
      <input class="sh-star-cr" type="radio" name="rating" id="rating4-<?=$accId?>" value="4">
      <label class="sh-star-label" for="rating4-<?=$accId?>"><span class="star"></span></label>
      <input class="sh-star-cr" type="radio" name="rating" id="rating3-<?=$accId?>" value="3">
      <label class="sh-star-label" for="rating3-<?=$accId?>"><span class="star"></span></label>
      <input class="sh-star-cr" type="radio" name="rating" id="rating2-<?=$accId?>" value="2">
      <label class="sh-star-label" for="rating2-<?=$accId?>"><span class="star"></span></label>
      <input class="sh-star-cr" type="radio" name="rating" id="rating1-<?=$accId?>" value="1">
      <label class="sh-star-label" for="rating5-<?=$accId?>"><span class="star"></span></label>
    </div>
    <span class="text" style="float:right; margin:0;"><?=$acc['rating']*5.0/100.0?>/5</span>
  </div>
  <div class='acc-pic short' style='background-image: url(<?=$webRoot?>/media/img/acc/<?=$accomId?>.jpg), url(<?=$webRoot?>/media/img/acc/<?=$accomId?>.png), url(<?=$webRoot?>/media/img/acc/<?=$accomId?>.jpeg)'>
  </div>
  <div class='acc-short-desc'>
    <?=$acc['description']?>
  </div>
  <a style="float:right;" class="link" href="<?=$webRoot?>/accommodation/?i=<?=$accId?>#reviews">
    <?=count($acc['reviews'])?> Review<?count($acc['reviews'])>1?'s':''?>
  </a>
</li>
</div>
</div>