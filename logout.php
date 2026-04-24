<?php
session_start();

/* destroy all sessions */
session_unset();
session_destroy();

/* redirect to homepage or login */
header("Location: index.html");
exit();
?>
