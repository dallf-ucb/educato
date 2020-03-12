<?php  
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('ROOT', str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));
require('app/core.php');

$dispatch = new dispatcher();
$dispatch->dispatch();

?>