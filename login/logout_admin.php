<?php
session_start();
session_destroy();
header("Location: ../login/login_admin.php");
exit();
?>
