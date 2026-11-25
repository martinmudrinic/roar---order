<?php
/*
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Logs the administrator out and destroys the active session.
@ASSESSME.ANALYZE: YES
*/

session_start();
session_unset();
session_destroy();

header("Location: ../login.php");
exit;