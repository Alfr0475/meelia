<?php
/**
 * Meelia Database Driver MySQLi
 *
 * <pre>
 * MySQLに対してのアクセスを実装する。
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Database
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev$
 * @access    public
 * @link      $HeadURL$
 */

/**
 * DatabaseDriverMysqli
 *
 * <pre>
 * MySQLiドライバクラス。
 * MySQLに対してのアクセス実装クラス。
 * </pre>
 *
 * @category Database
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL$
 * @access   public
 */
class DatabaseDriverMysqli extends DatabaseDriver
{
    protected $db_driver = 'mysqli';
    protected $instance  = null;

    /**
     * MySQLへ接続
     *
     * <pre>
     * MySQLに対しての接続を行う。
     * ポートが指定されていない場合はデフォルトの3306が使用される。
     * </pre>
     *
     * @return resource
     * @access public
     */
    public function connect()
    {
        $port = 3306;
        if ($this->port) {
            $port = $this->port;
        }

        $this->instance = new mysqli($this->hostname, $this->username, $this->password, "", $this->port);

        return $this->instance;
    }


    /**
     * MySQL切断
     *
     * <pre>
     * MySQLから切断する。
     * </pre>
     *
     * @param resource $con_id 接続リソース
     *
     * @return None
     * @access protected
     */
    protected function close($con_id)
    {
        $this->instance->close();
    }


    /**
     * MySQLデータベース選択
     *
     * <pre>
     * 使用データベースをアクティブにする。
     * </pre>
     *
     * @return bool
     * @access public
     */
    public function dbSelect()
    {
        return $this->instance->select_db($this->database);
    }


    /**
     * MySQL文字コード設定
     *
     * <pre>
     * クライアントの文字コードを設定する。
     * COLLATEがある場合は照合順序も設定する。
     * </pre>
     *
     * @param string $charset 文字コード
     * @param string $collate 照合順序
     *
     * @return bool
     * @access public
     */
    public function dbSetCharset($charset, $collate)
    {
        return $this->instance->set_charset($charset);
    }


    /**
     * 文字列エスケープ
     *
     * <pre>
     * 文字列をMySQL用にエスケープ処理する。
     * </pre>
     *
     * @param string $string 文字列
     *
     * @return string エスケープ後の文字列
     * @access public
     */
    public function escape($string)
    {
        return $this->instance->real_escape_string($string);
    }


    /**
     * SQL実行
     *
     * <pre>
     * SQLを実行する。
     * </pre>
     *
     * @param string $sql SQL文
     *
     * @return resource 結果リソース or boolen
     * @access protected
     */
    protected function query($sql)
    {
        return $this->instance->query($sql);
    }


    /**
     * MySQLのエラーメッセージ取得
     *
     * <pre>
     * 直前のSQLのエラーメッセージを取得する。
     * </pre>
     *
     * @return string エラーメッセージ
     * @access public
     */
    public function getErrorMessage()
    {
        return $this->instance->error;
    }


    /**
     * MySQLのエラー番号取得
     *
     * <pre>
     * 直前のSQLのエラー番号を取得する。
     * </pre>
     *
     * @return string エラー番号
     * @access public
     */
    public function getErrorNumber()
    {
        return $this->instance->errno;
    }


    /**
     * データベースの接続を調べる
     *
     * <pre>
     * サーバーとの接続が有効がどうか調べる。
     * </pre>
     *
     * @return bool
     * @access public
     */
    public function ping()
    {
        return $this->instance->ping();
    }
}

