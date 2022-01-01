<?php

$host = $_SERVER['HTTP_HOST'];

session_set_cookie_params(0, '/', $host, false, true);
ini_set('session.cookie_lifetime', 31536000);

session_start();

?>