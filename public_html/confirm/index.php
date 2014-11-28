<?php
/*
Set REQUIRE_SESSION (false). Set REQUIRE_NO_SESSION (true). Initalise. Receive
_GET['code'] from url. Select from temp table where code = code. If no entries,
output error message and exit. Output the rest of the registration form. Recieve
those _POST, and if valid, move temp. user into user insert details into
database.
*/