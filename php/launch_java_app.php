<?php
// c:\xampp\htdocs\2ERR_1TALDEA_BIRTEK\php\launch_java_app.php

// Define absolute paths for reliability
$projectRoot = "c:\\xampp\\htdocs\\2ERR_1TALDEA_BIRTEK";
$javaDir = $projectRoot . "\\java";
$binDir = $javaDir . "\\bin";
$libDir = $javaDir . "\\lib\\*"; // Wildcard for all jar dependencies

// The main class to run
$mainClass = "birtek_interfaze_grafikoa.LoginPanela";

// Construct the command
// 'start /B' runs it in the background on Windows
// 'javaw' runs it without a persistent console window (optional, 'java' works too but keeps console)
$command = "start /B javaw -cp \"$binDir;$libDir\" $mainClass";

// Execute the command
// pclose(popen(...)) is a trick to spawn a process without waiting for it to finish in PHP
pclose(popen($command, "r"));

echo "Lanzando: " . $command;
?>

