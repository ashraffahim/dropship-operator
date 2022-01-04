<?php

//Configuration & helpers
require_once '../vendor/session_helper.php';
require_once '../app/config/config.php';
require_once '../vendor/procedure_helper.php';

//Autoload Class | Call Methods
require_once '../vendor/autoload.php';

$router = new libraries\Route();
$router->dispatch($_SERVER['QUERY_STRING']);


?>