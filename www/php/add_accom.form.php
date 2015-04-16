<?php
// Processes the input got from add accommodation form
include '../../inc/init.php';

if(!OWNER_LOGGED_IN || !isset($_POST['add-accom-submit']))
{
  include __ROOT__.'/inc/html/notfound.php';
  exit();
}

try
{
  if(!isset($_POST['name'], $_POST['price'], $_POST['address'], $_POST['details'], $_FILES['main_photo'], $_POST['city']) 
    || !($_POST['name'] && $_POST['price'] && $_POST['address'] && $_POST['details'] && $_FILES['main_photo'] && $_POST['city']))
  {
    throw new Exception('missing', 1);
  }

  $params['name'] = trim(htmlentities($_POST['name']));
  $params['price'] = trim(htmlentities($_POST['price']));
  $params['address'] = trim(htmlentities($_POST['address']));
  $params['description'] = trim(htmlentities($_POST['details']));
  $params['city'] = trim(htmlentities($_POST['city']));
  $params['author'] = $owner->getCredential('id');
  $params['noPhotos'] = isset($_FILES['opt_photos']['name']) && $_FILES['opt_photos']['name']?count($_FILES['opt_photos']['name']):0;
  $params['main_photo'] = 'main_photo';
  $params['sec_photos'] = 'opt_photos';

  // Try to create new accommodation
  $newAcc = new Accommodation($con, 'insert', $params);
  if($newAcc->getError())
  {
    $_SESSION['exception'] = $newAcc->getError();
    //throw new Exception('insert', 1);
    echo $newAcc->getError();
    exit();
  }
  // Send owner to the new accommodation if succeeded
  header("Location: " . $webRoot . "/accommodation/?i=" . $newAcc->getId());
  exit();
}
catch (Exception $e)
{
  header("Location: ../?error=" . $e->getMessage());
  exit();
}


?>