<?php
session_start();

session_destroy();

header("Location: rider_login.php");
exit();
?>