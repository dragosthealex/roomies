<?php
/*
Contains the username and password fields used if register with facebook
*/
?>
<input type="email" disabled name="registerEmail" placeholder="Email" class="input block" required value="<?=$fbEmail?>">
<input type="password" name="registerPassword" placeholder="Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
<input type="password" name="registerConfirmPassword" placeholder="Confirm Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
<input type="text" name="registerUsername" placeholder="Username" class="input block" required pattern=".{4,25}" title="4 to 25 characters">
