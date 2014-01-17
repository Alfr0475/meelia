<?php
/**
 * Meelia Config
 *
 * <pre>
 * 設定ファイルアクセス
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Core
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 57 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/config.class.php $
 */

namespace meelia\core;

/**
 * Config
 *
 * <pre>
 * 設定ファイルにアクセスする為のクラス。
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/config.class.php $
 * @access   public
 */
class Config
{
    /**
     * 設定配列
     *
     * <pre>
     * 設定ファイルから読み出された設定配列。
     * </pre>
     *
     * @var array 設定配列
     */
    static protected $config_array = array();


    /**
     * 設定ファイル読込
     *
     * <pre>
     * 設定ファイルを読み込む。
     * 既に読み込まれている場合は再読み込みしない。
     * $forceがtrueの時のみ再読み込みを行う。
     * 設定ファイルでは配列$configに対して
     * キーを設定していく方式だが
     * $configが定義されていない場合は無視される。
     * </pre>
     *
     * @param bool $force 再読み込みフラグ
     *
     * @return bool
     * @access public
     */
    public static function load($force = false)
    {
        //既に読み込まれている場合は再読み込みしない
        if ($force === false && self::$config_array) {
            return true;
        }

        //public/index.php（任意）とmeelia/meelia.phpで設定した
        //app/configフォルダがなかったらエラーメッセージ
        if (!file_exists(ME_APP_CONFIG_DIR)) {
            showError('config dir path not found.');
        }
        $found_file = false;

        //app/configにあるincファイルを全部読む
        foreach (glob(ME_APP_CONFIG_DIR . '/*.inc.php') as $file) {
            $found_file = true;

            include $file;
            //読込んだファイルをログに記録
            logMessage('log', 'debug', '[load] '.$file);

            if (!isset($config) || !is_array($config)) {
                continue;
            }

            // 設定ファイル名から拡張子を取り除いたものを
            // prefixとして扱う。
            $config_prefix = basename($file, '.inc.php') . '_';

            foreach ($config as $key => $value) {
                self::$config_array[$config_prefix . $key] = $value;
            }

            unset($config);
        }

        return $found_file;
    }


    /**
     * 設定取得
     *
     * <pre>
     * 設定を取得する。
     * キーを指定しない場合は配列で全て返す。
     * </pre>
     *
     * @param string $key 設定名
     *
     * @return string
     * @access public
     */
    public static function get($key = '')
    {
        if ($key) {
            if (array_key_exists($key, self::$config_array)) {
                return self::$config_array[$key];
            }
        } else {
            return self::$config_array;
        }

        return null;
    }

    /**
     * 設定セット
     *
     * <pre>
     * 設定をセットする。
     * </pre>
     *
     * @param string $key   設定名
     * @param mixid  $value 設定値
     *
     * @return None
     * @access public
     */
    public static function set($key, $value)
    {
        self::$config_array[$key] = $value;
    }
}
