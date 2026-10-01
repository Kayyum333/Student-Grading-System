<?php

session_start();

// Destroy teacher login session
session_unset();
session_destroy();

// Redirect to teacher login
header("Location: teacher_login.php");
exit();

?>