<?php
session_start();
session_destroy();
header('Location: admin.php'); // Redirect to login page after logout
exit;
