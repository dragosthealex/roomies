<?php
/*
Set REQUIRE_SESSION (false). Set REQUIRE_NO_SESSION (true). Initalise. Receive
possible _POST['email'], _POST['password1'], and _POST['password2'] from
register form. Validate and check against database. If valid, create unique
validation code, add this to temp table with email and encrypted password, send
mail for validation (for now, just header to
../confirm?code=<confirmationcode>), and output confirm message. If any
invalid, output error messages along with form.
*/
?>