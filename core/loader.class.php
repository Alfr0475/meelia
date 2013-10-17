<?php
/**
 * Meelia Loader
 *
 * <pre>
 * 各種クラスの動的ロード。
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Core
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 49 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/loader.class.php $
 */

/**
 * Loader
 *
 * <pre>
 * 各種クラスの動的ロード。
 * 各ファイルでincludeするのではなく
 * 必要になった際に該当メソッドを呼び出すことで
 * クラスをロードしている。
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/loader.class.php $
 */
class Loader
{
    /**
     * インスタンス配列
     *
     * <pre>
     * Loaderを通してnewされたインスタンス。
     * </pre>
     *
     * @var array 各インスタンスの配列
     */
    static protected $object_array = array(
        'core'       => array(),
        'controller' => array(),
        'model'      => array(),
        'logic'      => array(),
        'database'   => array(),
    );


    /**
     * クラスをロード
     *
     * <pre>
     * 指定されたクラスをincludeする。
     * 既にincludeした事があれば、既存のインスタンスを返す。
     * $forceにtrueをセットした場合のみ、強制的にnewし直す。
     * このメソッドは各種インターフェースメソッドから
     * 呼び出される。
     * </pre>
     *
     * @param string $class クラス名
     * @param string $type  クラス種別
     * @param bool   $force 強制ロード
     *
     * @return object
     * @access public
     */
    protected static function &loadClass($class_path, $type, $force)
    {
        $found_flg = false;

        $app_file_name = $class_path . '.class.php';
        $app_file_path = '';
        $core_file_name = $app_file_name;
        $core_file_path = $app_file_path;

        switch($type){
            case 'core':
                $core_file_path = ME_CORE_DIR . '/' . $core_file_name;
                $app_file_path  = '';
                break;
            case 'controller':
                $core_file_path = ME_CORE_CONTROLLER_DIR . '/' . $core_file_name;
                $app_file_path  = ME_APP_CONTROLLER_DIR . '/' . $app_file_name;
                break;
            case 'model':
                $core_file_path = ME_CORE_MODEL_DIR . '/' . $core_file_name;
                $app_file_path  = ME_APP_MODEL_DIR . '/' . $app_file_name;
                break;
            case 'logic':
                $core_file_path = ME_CORE_LOGIC_DIR . '/' . $core_file_name;
                $app_file_path  = ME_APP_LOGIC_DIR . '/' . $app_file_name;

                break;
        }

        $explode_class_path = explode('/', $class_path);
        $class              = array_pop($explode_class_path);
        $load_class         = Util::camelizeUcc($type) . Util::camelizeUcc($class);

        if ($force === false &&
            array_key_exists($class, self::$object_array[$type])
        ) {
            return self::$object_array[$type][$class];
        }

        unset(self::$object_array[$type][$class]);

        if (file_exists($core_file_path)) {
            $found_flg = true;

            include_once $core_file_path;

            if (!class_exists($load_class)) {
                showError(sprintf('Undefined class: %s', $load_class));
            }

            self::$object_array[$type][$class] = new $load_class();
        }

        if (!isset(self::$object_array[$type][$class]) && file_exists($app_file_path)) {
            $found_flg = true;

            include_once $app_file_path;

            if (!class_exists($load_class)) {
                showError(sprintf('Undefined class: %s', $load_class));
            }

            self::$object_array[$type][$class] = new $load_class();
        }

        if (!$found_flg) {
            showError(sprintf('%s class file not found.', $type));
        }

        return self::$object_array[$type][$class];
    }

    /**
     * コントローラーロード
     *
     * <pre>
     * コントローラーをincludeする。
     * 既にincludeした事があれば、既存のインスタンスを返す。
     * $forceにtrueをセットした場合のみ、強制的にnewし直す。
     * </pre>
     *
     * @param string $class クラス名
     * @param bool   $force 強制ロード
     *
     * @return object
     * @access public
     */
    public static function &loadController($class, $force = false)
    {
        return self::loadClass($class, 'controller', $force);
    }


    /**
     * モデルロード
     *
     * <pre>
     * モデルをincludeする。
     * 既にincludeした事があれば、既存のインスタンスを返す。
     * $forceにtrueをセットした場合のみ、強制的にnewし直す。
     * </pre>
     *
     * @param string $class クラス名
     * @param bool   $force 強制ロード
     *
     * @return object
     * @access public
     */
    public static function &loadModel($class, $force = false)
    {
        return self::loadClass($class, 'model', $force);
    }


    /**
     * ロジックロード
     *
     * <pre>
     * ロジックをincludeする。
     * 既にincludeした事があれば、既存のインスタンスを返す。
     * $forceにtrueをセットした場合のみ、強制的にnewし直す。
     * </pre>
     *
     * @param string $class クラス名
     * @param bool   $force 強制ロード
     *
     * @return object
     * @access public
     */
    public static function &loadLogic($class, $force = false)
    {
        return self::loadClass($class, 'logic', $force);
    }


    /**
     * コアロード
     *
     * <pre>
     * コアをincludeする。
     * 既にincludeした事があれば、既存のインスタンスを返す。
     * $forceにtrueをセットした場合のみ、強制的にnewし直す。
     * </pre>
     *
     * @param string $class クラス名
     * @param bool   $force 強制ロード
     *
     * @return object
     * @access public
     */
    public static function &loadCore($class, $force = false)
    {
        return self::loadClass($class, 'core', $force);
    }


    /**
     * データベースロード
     *
     * <pre>
     * データベースドライバを取得する。
     * 既にデータベースに接続されていれば、既存のインスタンスを返す。
     * $forceにtrueをセットした場合のみ、強制的に接続し直す。
     * </pre>
     *
     * @param string $database データベース設定名
     * @param bool   $force    強制ロード
     *
     * @return object
     * @access public
     */
    public static function &loadDatabase($database, $force = false)
    {
        if ($force === false &&
            array_key_exists($database, self::$object_array['database'])) {
            return self::$object_array['database'][$database];
        }

        $config_key = 'database_' . $database;
        $db_config  = Config::get($config_key);


        require_once ME_CORE_DATABASE_DIR . '/database_driver.class.php';
        require_once ME_CORE_DATABASE_DIR . '/drivers/'.$db_config['db_driver'].'/database_driver_'.$db_config['db_driver'].'.class.php';

        $driver_class = sprintf('DatabaseDriver%s', Util::camelizeUcc($db_config['db_driver']));

        $resource = new $driver_class($db_config);

        self::$object_array['database'][$database] = $resource;

        return $resource;
    }
}

