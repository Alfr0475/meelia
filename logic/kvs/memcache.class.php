<?php
/**
 * Meelia KVS Memcache
 *
 * <pre>
 * PHPのMemcacheモジュールを利用して
 * アクセスするラッパー。
 *
 * PHP versions 5
 * </pre>
 *
 * @category   Logic
 * @package    Meelia
 * @subpackage KVS
 * @author     Seiki Koga <seikikoga@gamania.com>
 * @copyright  2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    SVN: $Rev: 56 $
 * @access     public
 * @link       $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/kvs/memcache.class.php $
 */


/**
 * KvsMemcache
 *
 * <pre>
 * PHPのMemcacheモジュールを利用したラッパー。
 * KvsInterfaceの実装もしてる。
 * </pre>
 *
 * @category   Logic
 * @package    Meelia
 * @subpackage KVS
 * @author     Seiki Koga <seikikoga@gamania.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @access     public
 * @link       $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/kvs/memcache.class.php $
 */
class KvsMemcache implements KvsInterface
{
    private $mem = null;

    /**
     * コンストラクタ
     *
     * <pre>
     * memcacheをインスタンス化
     * </pre>
     *
     * @return None
     * @access public
     */
    public function __construct()
    {
        $this->mem = new Memcache();
    }

    /**
     * memcacheのコネクションプール設定
     *
     * <pre>
     * memcacheのコネクションプールにサーバーを設定する。
     * addされた時点では接続を行わない。
     * 実際に値を設定する際に初めて接続される。
     * </pre>
     *
     * @param array $servers Memcachedサーバー情報配列
     *
     * @return bool
     * @access public
     */
    public function kvsConfigureServer($servers)
    {
        foreach($servers as $server){
            // portは０が入ってくる可能性があるため
            // issetの判定しかしてない。
            // hostでUnixSocket指定をしている場合はportが０になるよ
            if (!isset($server['port'])) {
                $server['port'] = 11211;
            }

            $res = $this->mem->addServer(
                $server['host'],   // ホスト
                $server['port'],   // ポート
                true,              // 持続接続
                $server['weight']  // 接続比率
            );

            if (!$res) {
                logMessage('log', 'error', sprintf(
                    '[KVS memcache] unable connect to : %s:%s',
                    $server['host'],
                    $server['port']
                ));

                return false;
            }
        }

        return true;
    }

    /**
     * Memcachedから値を取得
     *
     * <pre>
     * Memcachedから値を取得する。
     * </pre>
     *
     * @param string $key キー
     *
     * @return mixed
     * @access public
     */
    public function kvsGet($key)
    {
        return $this->mem->get($key);
    }

    /**
     * Memcachedに値を設定
     *
     * <pre>
     * Memcachedに値を設定する。
     * </pre>
     *
     * @param string $key   キー
     * @param mixed  $value 値
     *
     * @return bool
     * @access public
     */
    public function kvsSet($key, $value)
    {
        return $this->mem->set($key, $value);
    }

    /**
     * Memcachedの値を削除
     *
     * <pre>
     * Memcachedの値を削除する。
     * </pre>
     *
     * @param string $key キー
     *
     * @return bool
     * @access public
     */
    public function kvsDelete($key)
    {
        return $this->mem->delete($key);
    }

    /**
     * Memcachedから切断
     *
     * <pre>
     * Memcachedから切断する。
     * </pre>
     *
     * @return bool
     * @access public
     */
    public function kvsClose()
    {
        return @$this->mem->close();
    }
}

