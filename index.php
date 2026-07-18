<?php
// index.php — entry point. Auth state lives in the browser (Firebase
// Auth), not on the server, so the actual "is this user logged in"
// redirect happens client-side in home.php. This just sends everyone
// there first.
header('Location: /home.php');
exit;
