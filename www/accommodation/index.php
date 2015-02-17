<?
/*
This is the accommodation index page
*/

include '../../inc/init.php';

// The title of the page
$title = "Accommodation";
if(!LOGGED_IN)
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";

?>



<?php require_once __ROOT__."/inc/html/footer.php";?>