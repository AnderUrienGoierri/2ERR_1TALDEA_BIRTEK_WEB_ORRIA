<?php
session_start();
session_unset();
session_destroy();

// hasiera orrira eramaten du
header("Location: hasiera.php");
exit();
?>
