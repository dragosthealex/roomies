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
<div class="box-padding">
  Here da details, i.e. rating etc
</div>  
<div class='acc-long-desc'>
  <?=$accomInfo['description'];?>
</div>
<div class='acc-long-comments'>
  <ul class='ul'>
  </ul>
</div>