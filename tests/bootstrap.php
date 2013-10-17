<?php
error_reporting(E_ALL | E_STRICT);

define('ME_ROOT_DIR', realpath(dirname(__FILE__) . '/../'));
define('ME_APP_DIR', ME_ROOT_DIR . '/app');
define('ME_SYSTEM_DIR', ME_ROOT_DIR);

define('TEST_DB_HOSTNAME', 'localhost');
define('TEST_DB_USERNAME', 'root');
define('TEST_DB_PASSWORD', '0okm9ijn!!');
define('TEST_DB_DATABASE', 'meelia_test');

require_once ME_ROOT_DIR . '/meelia.php';
