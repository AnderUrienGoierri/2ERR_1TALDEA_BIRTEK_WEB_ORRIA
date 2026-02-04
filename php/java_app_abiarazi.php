<?php
// c:\xampp\htdocs\2ERR_1TALDEA_BIRTEK\php\launch_java_app.php

// Define absolute path to the executable
$exePath = "C:\\birtek_app_exekutablea\\BirtekAPP.exe";
$workingDir = "C:\\birtek_app_exekutablea";

// Construct the command
// cd /d changes drive and directory
// start "" launches with a blank title (needed for start command syntax sometimes)
// /B not used here to ensure it detaches properly, but if we want hidden console we might use it. 
// However, BirtekAPP.exe likely handles its own window.
$command = "cd /d \"$workingDir\" && start \"\" \"$exePath\"";

// Execute the command
pclose(popen($command, "r"));

echo "Abiarazten: " . $command;
?>

