<?php

// Date & Time
date_default_timezone_set('UTC');

//Database constants
define('CLIENT', 'dropship_db');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '646862');
define('DB_NAME', CLIENT);

//Software
define('SITENAME', 'Dopamine');
define('APP_VERSION', '1.0.0');

define('DS', DIRECTORY_SEPARATOR);
define('DATADIR', '.'.DS.'..'.DS.'..'.DS.'dropship-seller'.DS.'data');
define('DATA', DS . 'data');
define('ROW_LIMIT', 50);

?>