<?php
// The form that lets the logged in owner to add a new property (accommodation)




?>

<h2 class="h2">
  Add property
</h2>
<p class="text">
  The following details are mandatory:
</p>
<input class="input block" style="max-width:75%;" type="text" name="name" required placeholder="Property Name">
<input class="input block" style="max-width:75%;" type="text" name="price" required placeholder="Average Price per Week">
<select class="select" type="select" required>
  <option class="option" value="">Select City</option>
  <option class="option" value="1">Manchester</option>
</select>
<input class="input block" style="max-width:75%;" type="text" name="address" required placeholder="Street and no.">
<p clas="text">
  Main Picture
</p>
<div class="file-upload link-button" style="float:left;">
  <span>Upload</span>
  <input class="upload" type="file" name="main_photo" required value="Main picture">
</div>
<div class="acc-pic short" style="float:left;">
</div>