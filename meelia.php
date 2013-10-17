<?php
/**
 * Meelia
 *
 * <pre>
 * Meeliaフレームワークのベースファイル。
 * 主に各種パスの設定がされている。
 *
 * ME_ROOT_DIRとME_APP_DIRは作成するアプリによって
 * パスが変わるのでここでは定義しない。
 *
 * PHP versions 5
 * </pre>
 *
 * @package    Meelia
 * @subpackage Core
 * @category   Framework base
 * @author     Seiki Koga <seikikoga@gamania.com>
 * @copyright  2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    SVN: $Rev: 49 $
 * @access     public
 * @link       $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/meelia.php $
 */

define('ME_CORE_DIR',            ME_SYSTEM_DIR . '/core');
define('ME_CORE_CONTROLLER_DIR', ME_SYSTEM_DIR . '/controller');
define('ME_CORE_MODEL_DIR',      ME_SYSTEM_DIR . '/model');
define('ME_CORE_VIEW_DIR',       ME_SYSTEM_DIR . '/view');
define('ME_CORE_LOGIC_DIR',      ME_SYSTEM_DIR . '/logic');
define('ME_CORE_ERROR_DIR',      ME_SYSTEM_DIR . '/error');
define('ME_CORE_DATABASE_DIR',   ME_SYSTEM_DIR . '/database');
define('ME_CORE_TEST_DIR',       ME_SYSTEM_DIR . '/tests');
define('ME_CORE_VENDOR_DIR',     ME_SYSTEM_DIR . '/vendor');


define('ME_APP_MODEL_DIR',      ME_APP_DIR     . '/model');
define('ME_APP_CONTROLLER_DIR', ME_APP_DIR     . '/controller');
define('ME_APP_VIEW_DIR',       ME_APP_DIR     . '/view');
define('ME_APP_LOGIC_DIR',      ME_APP_DIR     . '/logic');
define('ME_APP_CONFIG_DIR',     ME_APP_DIR     . '/config');
define('ME_APP_ERROR_DIR',      ME_APP_DIR     . '/error');
define('ME_APP_TMP_DIR',        ME_APP_DIR     . '/tmp');
define('ME_APP_LOG_DIR',        ME_APP_TMP_DIR . '/log');

require_once ME_CORE_VENDOR_DIR . '/autoload.php';

require_once ME_CORE_DIR . '/common.php';
require_once ME_CORE_DIR . '/dispatcher.class.php';
require_once ME_CORE_DIR . '/controller.class.php';
require_once ME_CORE_DIR . '/view.class.php';
require_once ME_CORE_DIR . '/loader.class.php';
require_once ME_CORE_DIR . '/config.class.php';
require_once ME_CORE_DIR . '/log.class.php';
require_once ME_CORE_DIR . '/util.class.php';

