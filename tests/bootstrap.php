<?php
error_reporting(E_ALL | E_STRICT);

define('ME_ROOT_DIR', realpath(dirname(__FILE__) . '/../'));
define('ME_APP_DIR', ME_ROOT_DIR . '/app');
define('ME_SYSTEM_DIR', ME_ROOT_DIR);

require_once ME_ROOT_DIR . '/meelia.php';
