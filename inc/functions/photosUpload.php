<?php
/*
Function that uploads a photo
has three parameters:
1. an int, defining the folder where it should upload
2. the name of the input forms
3. the base name of the file (usually as an id)
4. true if primary (only the base name) and false if secondary (with added key)
*/
function photoUpload($location, $inputName, $baseName, $primary)
{
  try
  {
    if(!$primary && !isset($_FILES[$inputName]["error"][1]))
    {  
      foreach ($_FILES[$inputName]["error"] as $key => $error)
      {
        if(!$error == UPLOAD_ERR_OK)
        {
          throw new Exception("Failed check: $error.", 1);
        }
        $tmpName = $_FILES[$inputName]['tmp_name'][$key];
        $name = $baseName . "-" . $key;
        // Validate name, size and type
        if(file_exists($name))
        {
          throw new Exception("File $name exists already.", 1);
        }
        if($_FILES[$inputName]["size"][$key] > 5000000)
        {
          throw new Exception("File $name is too large. It must be at most 5Mb.", 1);
        }
        $check = getimagesize($tmpName);
        if(!$check)
        {
          throw new Exception("File is not an image.", 1);
        }
        $mime = $check['mime'];
        $extension = end((explode(".", $_FILES[$inputName]["name"][$key])));

        if(!($mime == 'image/jpg' || $mime == 'image/jpeg' || $mime == 'image/png' || $mime == 'image/gif'))
        {
          throw new Exception("File is $extension , not an accepted image. png, gif, jpg and jpeg formats only.", 1);
        }

        return (move_uploaded_file($tmpName, $location.$name.".".$extension))?"ok":"Moving of new file failed for $tmpName.";
      }
    }
    else
    {
      $error = $_FILES[$inputName]["error"];
      $tmpName = $_FILES[$inputName]['tmp_name'];

      if(!$error == UPLOAD_ERR_OK)
      {
        throw new Exception("Failed check for $tmpName: $error", 1);
      }
      $name = $baseName;
      // Validate name, size and type
      if(file_exists($name))
      {
        throw new Exception("File $name exists already", 1);
      }
      if($_FILES[$inputName]["size"] > 5000000)
      {
        throw new Exception("File $name is too large", 1);
      }
      $check = getimagesize($tmpName);
      if(!$check)
      {
        throw new Exception("File is not an image.", 1);
      }
      $mime = $check['mime'];
      $extension = end((explode(".", $_FILES[$inputName]["name"])));

      if(!($mime == 'image/jpg' || $mime == 'image/jpeg' || $mime == 'image/png' || $mime == 'image/gif'))
      {
        throw new Exception("File is $extension , not an accepted image. png, gif, jpg and jpeg formats only.", 1);
      }

      return (move_uploaded_file($tmpName, $location.$name.".".$extension))?"ok":"Moving of new file failed for $tmpName.";
    }
  }// try
  catch (Exception $e)
  {
    return $e->getMessage();
  }
}// function
?>