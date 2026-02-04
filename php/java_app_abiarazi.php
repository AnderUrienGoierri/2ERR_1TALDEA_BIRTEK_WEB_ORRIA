<?php
// c:\xampp\htdocs\2ERR_1TALDEA_BIRTEK\php\launch_java_app.php

// Exekutablearen bide absolutua definitu
$exePath = "C:\\birtek_app_exekutablea\\BirtekAPP.exe";
$workingDir = "C:\\birtek_app_exekutablea";

// Komandoa eraiki
// cd /d unitatea eta direktorioa aldatzeko
// start "" izenburu huts batekin abiarazteko (start komandoaren sintaxirako beharrezkoa)

$command = "cd /d \"$workingDir\" && start \"\" \"$exePath\"";

// Execute the command
pclose(popen($command, "r"));

echo "Abiarazten: " . $command;
?>

