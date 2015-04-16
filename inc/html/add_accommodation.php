<?php
// The form that lets the logged in owner to add a new property (accommodation)
$error = '';
if(isset($_GET['error']))
{
  switch ($_GET['error'])
  {
    case 'missing':
      $error = "All values must be completed";
      break;
    case 'insert':
      $error = "Error inserting the new property: ";
      $error .= isset($_SESSION['exception'])?$_SESSION['exception']:'';
      break;
    default:
      # code...
      break;
  }
}
// get all cities in db
$cities = '';
$stmt = $con->prepare("SELECT filter_value, map_uni_city FROM rfiltersmap WHERE (map_uni_city IS NOT NULL AND map_uni_city != '') ORDER BY filter_value ASC");
$stmt->execute();
$stmt->bindColumn(1, $key);
$stmt->bindColumn(2, $city);
while($stmt->fetch())
{
  $cities .= "<option class='option' value='$key'>" . ucfirst($city) . "</option>";
}

?>

<h2 class="h2">
  Add property
</h2>
<p class="error">
  <?=$error?>
</p>
<p class="text">
  The following details are mandatory:
</p>
<form enctype="multipart/form-data" id="add-property-form" method="POST" action="php/add_accom.form.php">
  <input class="input block" style="max-width:75%;" type="text" name="name" required placeholder="Property Name">
  <input class="input block" style="max-width:75%;" type="text" name="price" required placeholder="Average Price per Week">
  <select class="select" type="select" required name="city">
    <option class="option" value="">Select City/Town</option>
    <?=$cities?>
  </select>
  <input class="input block" style="max-width:75%;" type="text" name="address" required placeholder="Street and no.">
  <div class="textarea-holder">
    <textarea class="textarea" name="details" form="add-property-form" required placeholder="Add a description..." style="resize:vertical;"></textarea>
  </div>
  <p class="text">
    Main Picture
  </p>
  <div style="overflow:hidden;">
    <div class="file-upload link-button" style="float:left;">
      <span>Browse</span>
      <input id="main_photo" class="upload photo-input" type="file" name="main_photo" required value="Main picture" data-files-limit='1' data-thumbnail-container='main-pic-thumb'>
    </div>
    <div id='main-pic-thumb' style="float:left;"
      ><div class="acc-pic short" style="background-image:url(media/img/acc/default.gif);"></div
    ></div>
  </div>
  <p class="text">
    Optional Pictures (maximum 5)
  </p>
  <div style="overflow:hidden;">
    <div class="file-upload link-button" style="float:left;">
      <span>Browse</span>
      <input id="opt_photos" class="upload photo-input" type="file" name="opt_photos[]" value="Main picture" data-files-limit='5' data-thumbnail-container='opt-pic-thumbs'>
    </div>
    <div id='opt-pic-thumbs' style="float:left;">
    </div>
  </div>
  <input type="submit" value="submit" name="add-accom-submit" class="input-button block">
</form>