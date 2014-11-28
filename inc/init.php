<?php
/*
Start the session. Define LOGGED_IN. Set $ioStatus. Set $user. Include config.
Connect to database. If connection fails, output error message. If
REQUIRE_SESSION is true, if not logged in, output 400 forbidden. Else, if
REQUIRE_NO_SESSION is true and logged in, output 404 not found.
e.g.
if (REQUIRE_SESSION)
  if(!LOGGED_IN)
    //400
else if (REQUIRE_NO_SESSION && LOGGED_IN)
  //404
This prevents REQUIRE_NO_SESSION being checked if REQUIRE_SESSION is true.
*/
?>