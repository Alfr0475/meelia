<?php
/**
 * Meelia Database Driver MySQL
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
 * DatabaseDriverMysql
 *
 * <pre>
 * MySQLドライバクラス。
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
class DatabaseDriverMysql extends DatabaseDriver
{
    protected $db_driver = 'mysql';

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
        if ($this->port) {
            $this->hostname .= ':' . $this->port;
        }

        // 問題が出た際に、情報をエラーとして表示しない
        return @mysql_connect(
            $this->hostname,
            $this->username,
            $this->password,
            true
        );
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
        @mysql_close($con_id);
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
        return @mysql_select_db($this->database, $this->con_id);
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
        static $use_set_names;

        if (!isset($use_set_names)) {
            // mysql_set_charset()はバージョン依存
            // PHP >= 5.2.3 and MySQL >= 5.0.7
            $is_php   = version_compare(PHP_VERSION, '5.2.3', '>=');
            $is_mysql = version_compare(mysql_get_server_info(), '5.0.7', '>=');

            $use_set_names = ($is_php && $is_mysql) ? false : true;
        }

        if ($use_set_names) {
            $sql = sprintf(
                'SET NAMES %s COLLATE %s',
                $this->escape($charset),
                $this->escape($collate)
            );

            return @mysql_query($sql, $this->con_id);
        } else {
            return @mysql_set_charset($charset, $this->con_id);
        }
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
        return mysql_real_escape_string($string, $this->con_id);
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
        return @mysql_query($sql, $this->con_id);
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
        return mysql_error($this->con_id);
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
        return mysql_errno($this->con_id);
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
        return mysql_ping($this->con_id);
    }
}

