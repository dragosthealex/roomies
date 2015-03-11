<?php
// DEPRECATED
// This thing process stuff

require_once '../../init.php';

if(!isset($_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus')
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