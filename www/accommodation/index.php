<?php
/*
This is the accommodation index page
*/

include_once '../../inc/init.php';

// The title of the page
$title = "Accommodation";

// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
<div class="column-wrapper">
  <div class="column-2">
    <div class="column-box">
      <div class="box-padding">
        <?php require_once __ROOT__.'/inc/html/all_accommodations.php';?>
      </div>
    </div>
  </div>
  <div class="column-2">
    <div class="column-box">
      <div class="box-padding">
        <?php require_once __ROOT__.'/inc/html/specific_accommodation.php';?>
      </div>
    </div>
  </div>
</div>
<?php require_once __ROOT__."/inc/html/footer.php";?>