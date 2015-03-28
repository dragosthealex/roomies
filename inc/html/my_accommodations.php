<?php
$errorMsg = '';
// Gets all accommodations from db for the logged owner and displays them
$accommodations = json_decode($owner->getAccommodations(),1);
$errorMsg .= $owner->getError();



?>

<h2 class="h2">
  My Accommodations
</h2>
</div>
<div class="all-accommodations">
  <ul class="ul">
    <?php
      foreach ($accommodations as $key => $acc) 
      {
        $accId = $acc['id'];
        $accName = $acc['name'];
        $accNoReviews = count($acc['reviews']);
        $accRating = $acc['rating'];
        $accDescription = $acc['description'];

        include __ROOT__.'/inc/html/accommodation-box.php';
      }
    ?>
  </ul>
</div>
<div>