<?php
/*
Process a message and inserts it into db
*/
require_once '../../inc/init.php';
require_once __ROOT__.'/inc/classes/message.php';

$headers = getallheaders();
if(!isset($_GET['message']) || !isset($_GET['receiver']) || !isset($headers['Roomies']) || $headers['Roomies'] != 'cactus')
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}
else
{
  $id1 = $user->getIdentifier('id');
  $id2 = htmlentities($_GET['receiver']);

  // Escape stuff
  $messageText = htmlspecialchars($_GET['message']);

  // Values for setting the message
  $values[0] = $id1;
  $values[1] = $id2;
  $values[2] = $messageText;
  // Insert message in DB
  $message = new Message($con, 'text', $values);
}
?>