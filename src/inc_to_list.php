<?php
// Redirects to index.php
if ($executeResult) {
    error_reporting(E_ALL); 
    ini_set("display_errors", 1);
    header('Location: ../../');
    exit;
}
?>