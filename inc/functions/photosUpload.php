<?php
/*
Function that uploads a photo
has three parameters:
1. an int, defining the folder where it should upload
2. the name of the input forms
3. the base name of the file (usually as an id)
*/
function photosUpload($location, $inputName, $baseName)
{
  $location = locationMap($location);
  foreach ($_FILES[$inputName]["error"] as $key => $error)
  {
    if($error == UPLOAD_ERR_OK)
    {
      // TODO: VALIDATION
      $tmpName = $_FILES[$inputName]['tmp_name'][$key];
      $name = $baseName . "-" . $key;
      move_uploaded_file($tmpName, $location.$name);
    }
  }
}

function locationMap($location)
{
  switch ($location) {
    case 0:
      return $webRoot.'/media/img/accommodation/';
      break;
    case 1:
      return $webRoot.'/media/img/usr/'
    default:
      break;
  }
}



?>