<?php
// This thing process stuff

require_once '../../init.php';

$headers = getallheaders();
if(!isset($headers['Roomies']) || $headers['Roomies'] != 'cactus')
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

$offset = 0;
if(isset($_GET['offset']))
{
  $offset = $_GET['offset'];
}

// Return stuff to ajax
echo $user->getNotifMessages($offset);

?>