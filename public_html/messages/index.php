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




<?php include __ROOT__."/inc/html/footer.php";?>