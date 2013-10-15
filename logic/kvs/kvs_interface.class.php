<?php
/**
 * Meelia KVS Interface
 *
 * <pre>
 * ファイル概要
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
 * @version    SVN: $Rev$
 * @access     public
 * @link       $HeadURL$
 */

/**
 * KvsInterface
 *
 * <pre>
 * 各KVSクラスのインターフェイス。
 * 各KVSクラスはこのインターフェイスを必ず実装する事。
 * </pre>
 *
 * @category   Logic
 * @package    Meelia
 * @subpackage KVS
 * @author     Seiki Koga <seikikoga@gamania.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @access     public
 * @link       $HeadURL$
 */
interface KvsInterface
{
    /**
     * KVSのコネクションプール設定
     *
     * <pre>
     * KVSのコネクションプールにサーバーを設定する。
     * 以下の形式であれば大体のKVS系ライブラリは対応できるハズ。
     *
     * array(
     *     'host'   => サーバーホスト,
     *     'port'   => サーバーポート,
     *     'weight' => 選択比率(ライブラリによっては無いかも),
     * );
     * </pre>
     *
     * @param array $servers KVSサーバー情報配列
     *
     * @return bool
     * @access public
     */
    public function kvsConfigureServer($servers);

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
    public function kvsGet($key);

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
    public function kvsSet($key, $value);

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
    public function kvsDelete($key);

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
    public function kvsClose();
}

