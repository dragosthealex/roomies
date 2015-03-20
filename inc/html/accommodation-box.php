<li class='li' style='overflow: hidden's>
  <div class="accom-box-title" style="margin:0">
    <a style='margin: 5px;' href='<?=$webRoot?>/accommodation/?i=<?=$accId?>' class='link'>
      <?=$acc['name']?>
    </a>

    <div class="ratings-box accom-box-rating" style="margin-bottom:0px;"
      ><div class="star-background star-b0" style='width:<?=$acc['rating'].'%'?>'></div>
      <input class="star-cr" type="radio" name="rating" id="rating5" value="5">
      <label class="star-label" for="rating5"><span class="star"></span></label>
      <input class="star-cr" type="radio" name="rating" id="rating4" value="4">
      <label class="star-label" for="rating4"><span class="star"></span></label>
      <input class="star-cr" type="radio" name="rating" id="rating3" value="3">
      <label class="star-label" for="rating3"><span class="star"></span></label>
      <input class="star-cr" type="radio" name="rating" id="rating2" value="2">
      <label class="star-label" for="rating2"><span class="star"></span></label>
      <input class="star-cr" type="radio" name="rating" id="rating1" value="1">
      <label class="star-label" for="rating5"><span class="star"></span></label>
    </div>
    <span class="text" style="float:right; margin:0;"><?=$acc['rating']*5.0/100.0?>/5</span>
  </div>
  <div class='acc-pic short' style='background-image: url(<?=$webRoot?>/media/img/acc/<?=$acc['id']?>.jpg);'>
  </div>
  <div class='acc-short-desc'>
    <?=$acc['description']?>
  </div>
</li>