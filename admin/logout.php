<?php
session_start();
// Admin session ni poorthiga clear chesthunnam
session_destroy();
// Thirigi main login page ki redirect chesthunnam
header("Location: ../login.php");
exit();
?>