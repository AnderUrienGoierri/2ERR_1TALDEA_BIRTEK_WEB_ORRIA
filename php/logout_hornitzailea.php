<?php
session_start();
session_unset();
session_destroy();

// Finalize and redirect
header("Location: hasiera.php");
exit();
?>
