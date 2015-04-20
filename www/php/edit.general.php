<?php
require_once '../../inc/init.php';
$response = array();
$response['error'] = '';
if(!isset($_GET['pid'], $_GET['ptype'], $_SERVER['HTTP_ROOMIES'], $_GET['s']) || $_SERVER['HTTP_ROOMIES'] != 'cactus' || !LOGGED_IN || ((isset($user2) && !$user2->isAuthorOf($_GET['pid'])) && (isset($owner) && $owner->isAuthorOf($_GET['pid']))))
{
  include __ROOT__.'/inc/html/notfound.php';
  exit();
}
$s = $_GET['s'];
$ptype = $_GET['ptype'];
$pid = $_GET['pid'];
try
{
  switch($s)
  {
    case(0):
      switch($ptype)
      {
        case(Review::TYPE):
          $pid = htmlentities($pid);
          $currentText = isset($_POST['review-current-'.$pid]) ? htmlentities($_POST['review-current-'.$pid]) : '';
          if(!$pid || !$currentText)
          {
            throw new Exception("Error. invalid params", 1);
          }
          $stmt = $con->prepare("SELECT post_text FROM rposts WHERE post_id = $pid");
          if(!$stmt->execute())
          {
            throw new Exception("GO BACH", 1);
          }
          $stmt->bindColumn(1, $textFromDb);
          $stmt->fetch();
          $output = "
          <textarea id='edited-review-$pid' name='value' class=' textarea ' name='reply25' id='reply-input-25' type='text' placeholder='Write a reply...' oninput='this.style.height=((this.value.match(/\\n/g)||[]).length+2)*1.1+'em';return false;' onkeydown='return event.shiftKey || ((event.keyCode === 13 &amp;&amp; this.value.trim()) ? (window.onclick({button:1,target:document.getElementById(\"save-review-button\")}), false) : event.keyCode !== 13);'>$textFromDb</textarea><button class=' link hidden ' style='' 
          data-ajax-url='$webRoot/php/edit.general.php?pid=$pid"."&ptype=".Review::TYPE."&s=0'
          data-ajax-post='edit-review '
          >Edit
          </button></div>
          ";
          $response['generate'] = $output;
          break;
        case(Reply::TYPE):
          $response['generate'] = 'fuck';
          break;
      }
      break;
    case (1);
      // save shit
      switch ($ptype) 
      {
        case Review::TYPE:
          $text = isset($_POST['edited-review-'.$pid]) ? htmlentities($_POST['edited-review-'.$pid]) : '';
          if(!$pid || !$text)
          {
            throw new Exception("Error. invalid params", 1);
          }
          $response['generate'] = $text;
          if(!$user2->updatePost($pid, $text))
          {
            throw new Exception("Your fetishes are too weird for the post to be updated: " . $user2->getError(), 1);
          }
          break;
        
        default:
          throw new Exception("Error. ptype wrong.", 1);
          break;
      }// switch
      break;
    case(-1):
      if(!$user2->isAuthorOf($pid))
      {
        throw new Exception("you are not a hacker, just a fucking no-lifer basement dweller", 1);
      }
      $user2->deletePost($pid);
      $response['generate'] = '';
      break;
    default:
      throw new Exception("Weird stuff here. aborting thread.", 1);
      break;
  }//switch $s
  echo json_encode($response);
  exit();
}
catch (Exception $e)
{
  $response['error'] = $e->getMessage();
  echo json_encode($response);
  exit();
}