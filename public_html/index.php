<?php
/*
Do not initialise REQUIRE_SESSION. We do not need to check that. Initialise. Output
homepage content.
*/

// Includes the init file
require_once '../inc/init.php';

// If logged out, show homepage, then exit the script.
if(!LOGGED_IN)
{
?>
<!--html code for logged out homepage-->





<?php
}// if(!LOGGED_IN)
exit();

// Else, we show the homepage for logged in users
?>
<!--html code for logged in homepage-->