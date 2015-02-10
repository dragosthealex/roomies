<?php
include '../../inc/init.php';

// The title of the page
$title = "Messages";
if(!LOGGED_IN)
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
// Page begins here, html and body tags are opened in head, closed in footer. Also, main div is closed in footers
?>

<div class="column-wrapper">
    <!--About me-->
    <div class="column-2">
      <div class="column-box">
        <div class="box-padding">
          <h2 class="h2">Conversations</h2>
          <?php /*get conversation*/?>
          <p class="text">

          </p>
        </div>
      </div>
    </div>
    <!--filters-->
    <div class="column-2">
      <div class="column-box">
        <div class="box-padding">
          <h2 class="h2">My Details</h2>
          <?php
          // retrieve list from database
          $details = $user->getDetails();
          foreach ($details as $detail)
          {
            echo "$detail <br>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>


<?php include __ROOT__."/inc/html/footer.php";?>