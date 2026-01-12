<?php
session_start();
session_unset();
session_destroy();

// Frontend handled redirection if needed
header("Content-Type: application/json");
echo json_encode(['success' => true]);
?>

