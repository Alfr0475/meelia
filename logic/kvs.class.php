<?php
/**
 * Meelia KVS
 *
 * <pre>
 * KVSインターフェイス
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Logic
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev$
 * @access    public
 * @link      $HeadURL$
 */

require_once ME_CORE_LOGIC_DIR . '/kvs/kvs_interface.class.php';

/**
 * LogicKvs
 *
 * <pre>
 * KVS系のインターフェイスクラス。
 * MemcacheやRedis等、このクラスを継承して作る。
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL$
 */
class LogicKvs
{
    private $instance = null;


    /**
     * コンストラクタ
     *
     * <pre>
     * 使用するKVSの確定と接続を行う。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function __construct()
    {
        // KVS名からファイルパスを作成
        // logic/kvs/にKVSの名前で各KVSクラスが置いてある
        $kvs_driver = Config::get('kvs_driver');
        $file_path = ME_CORE_LOGIC_DIR . '/kvs/' . $kvs_driver . '.class.php';

        if (file_exists($file_path)) {
            require_once $file_path;

            // KVS名からクラス名を作成
            //
            // 例)
            // Memcache : KvsMemcache
            // Redis    : KvsRedis
            $class_name = 'Kvs' . Util::camelizeUcc($kvs_driver);

            if (class_exists($class_name)) {
                $this->instance = new $class_name();

                // KVSのコネクションプールにサーバーを登録
                $res = $this->configureServer();
                if (!$res) {
                    logMessage('log', 'error', '[KVS] Invalid configure.');
                    showError('Invalid KVS configure');
                }

                logMessage('log', 'debug', '[KVS] connect KVS.');
            } else {
                showError(array('Class does not exists.', $class_name));
            }
        } else {
            showError(array('File does not exists.', $file_path));
        }
    }

    /**
     * デストラクタ
     *
     * <pre>
     * KVSから切断する。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function __destruct()
    {
        $res = $this->close();
        if (!$res) {
            logMessage('log', 'error', '[KVS] Close faild.');
        }

        logMessage('log', 'debug', '[KVS] Close KVS.');
    }

    /**
     * KVSインスタンス取得
     *
     * <pre>
     * KVSインスタンスを取得する。
     * </pre>
     *
     * @return object
     * @access public
     */
    protected function getInstance()
    {
        return $this->instance;
    }

    protected function configureServer()
    {
        return $this->instance->kvsConfigureServer(Config::get('kvs_servers'));
    }

    /**
     * KVSから値を取得
     *
     * <pre>
     * KVSから値を取得する。
     * </pre>
     *
     * @param string $key キー
     *
     * @return mixed
     * @access public
     */
    public function get($key)
    {
        return $this->instance->kvsGet(Config::get('kvs_prefix') . $key);
    }

    /**
     * KVSに値を設定
     *
     * <pre>
     * KVSに値を設定する。
     * </pre>
     *
     * @param string $key   キー
     * @param mixed  $value 値
     *
     * @return bool
     * @access public
     */
    public function set($key, $value)
    {
        return $this->instance->kvsSet(Config::get('kvs_prefix') . $key, $value);
    }

    /**
     * KVSの値を削除
     *
     * <pre>
     * KVSの値を削除する。
     * </pre>
     *
     * @param string $key キー
     *
     * @return bool
     * @access public
     */
    public function delete($key)
    {
        return $this->instance->kvsDelete($key);
    }

    /**
     * KVSから切断
     *
     * <pre>
     * KVSから切断する。
     * </pre>
     *
     * @return bool
     * @access public
     */
    protected function close()
    {
        return $this->instance->kvsClose();
    }
}

