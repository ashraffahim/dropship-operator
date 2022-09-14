<?php

// Date & Time
date_default_timezone_set('UTC');

//Database constants
define('CLIENT', 'grap_db');
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
define('LOGO', 'https://grap.store/assets/img/grap-logo.png');
define('ROW_LIMIT', 10);

// Server
define('SMTP', 'mail.alghaim.com');
define('SMTP_PORT', '2525');

?>