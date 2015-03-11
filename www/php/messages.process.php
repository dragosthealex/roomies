<?php
/*
Process a message and inserts it into db
*/
require_once '../../inc/init.php';
require_once __ROOT__.'/inc/classes/message.php';
require_once __ROOT__.'/inc/classes/conversation.php';

if(!isset($_POST['message']) || !isset($_GET['receiver']) || !isset($_SERVER['HTTP_ROOMIES']) || $_SERVER['HTTP_ROOMIES'] != 'cactus')
{
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

header('Content-type: application/json');

$response = array();

// Get stuff
$id1 = $user->getIdentifier('id');
$id2 = htmlentities($_GET['receiver']);
$messageText = htmlentities(trim($_POST['message']));

// If empty message, don't do anything
if ($messageText != '')
{
  // Read all messages in this conv
  $conversation = new Conversation($con, $id1, $id2);
  $conversation->readMessages();

  // Insert message in DB
  $message = new Message($con, 'text', array($id1, $id2, $messageText));
}

echo json_encode($response);
?>