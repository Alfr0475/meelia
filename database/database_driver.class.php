<?php
/**
 * Meelia Database Driver
 *
 * <pre>
 * データベースドライバのベース。
 * 各データベースドライバは継承して作成する。
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

namespace meelia\database;

/**
 * DatabaseDriver
 *
 * <pre>
 * 各データベースを扱うためのインターフェース。
 * ドライバクラスで各データベースの機能を実装する。
 * </pre>
 *
 * @category Database
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL$
 */
abstract class DatabaseDriver
{
    protected $db_driver = '';

    protected $hostname;
    protected $username;
    protected $password;
    protected $port;

    protected $database;
    protected $charset;
    protected $collate;

    protected $con_id = false;
    protected $res_id = false;

    protected $querys = array();


    /**
     * コンストラクタ
     *
     * <pre>
     * 各種設定値を設定。
     * </pre>
     *
     * @param array $params 各種設定配列
     *
     * @return None
     * @access public
     */
    public function __construct($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->$key = $val;
                }
            }
        }

        if (!$this->initialize()) {
            showError('Uninitialized to database.');
        }
    }


    /**
     * デストラクタ
     *
     * <pre>
     * データベースから切断する。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function __destruct()
    {
        if (is_resource($this->con_id) or is_object($this->con_id)) {
            $this->close($this->con_id);
            logMessage('log', 'debug', '[Database] Close database.');
        }

        $this->con_id = false;
    }


    /**
     * 保持しているSQL情報を取得する
     *
     * <pre>
     * 保持しているSQL情報を取得する。
     * array(
     *     'time' => 実行時間,
     *     'sql'  => 実行SQL
     * );
     * </pre>
     *
     * @return array SQL情報配列
     * @access public
     */
    public function getQuerys()
    {
        return $this->querys;
    }


    /**
     * 初期化処理
     *
     * <pre>
     * データベース接続等の初期化処理を行う。
     * </pre>
     *
     * @return bool
     * @access private
     */
    private function initialize()
    {
        if (is_resource($this->con_id) or is_object($this->con_id)) {
            return true;
        }

        $this->con_id = $this->connect();

        if (!$this->con_id) {
            showError('Unable to connect to the database.');
        }

        logMessage('log', 'debug', '[Database] Connect database.');

        if ($this->database != '') {
            if (!$this->dbSelect()) {
                logMessage('log', 'error', '[Database] Unable to select database');
                showError(array(
                    'Error : ' . $this->getErrorNumber(),
                    $this->getErrorMessage(),
                    'Unable to select database'
                ));
            } else {
                if (!$this->dbSetCharset($this->charset, $this->collate)) {
                    return false;
                }

                return true;
            }
        }

        return true;
    }


    /**
     * データベース接続
     *
     * <pre>
     * データベースに接続する。
     * 接続リソースを返す事。
     * </pre>
     *
     * @return resource
     * @access public
     */
    abstract public function connect();

    /**
     * データベース切断
     *
     * <pre>
     * データベースから切断する。
     * </pre>
     *
     * @param resource $con_id 接続リソース
     *
     * @return None
     * @access protected
     */
    abstract protected function close($con_id);

    /**
     * データベース選択
     *
     * <pre>
     * データベースを切り替える。
     * データベースの指定はconfigで行う。
     * </pre>
     *
     * @return bool
     * @access public
     */
    abstract public function dbSelect();

    /**
     * クライアント文字コード設定
     *
     * <pre>
     * データベースクライアントの文字コードを設定する。
     * COLLATEを設定する事で照合順序も設定可能。
     * </pre>
     *
     * @param string $charset 文字コード
     * @param string $collate 照合順序
     *
     * @return bool
     * @access public
     */
    abstract public function dbSetCharset($charset, $collate);


    /**
     * 文字列エスケープ
     *
     * <pre>
     * 文字列をエスケープする。
     * </pre>
     *
     * @param string $string 文字列
     *
     * @return string エスケープ後の文字列
     * @access public
     */
    abstract public function escape($string);

    /**
     * SQL発行
     *
     * <pre>
     * SQLを発行する。
     * </pre>
     *
     * @param string $sql SQL文
     *
     * @return resource 結果リソース or boolen
     * @access protected
     */
    abstract protected function query($sql);

    /**
     * エラーメッセージ取得
     *
     * <pre>
     * 直前のエラーメッセージを取得する。
     * </pre>
     *
     * @return string エラーメッセージ
     * @access public
     */
    abstract public function getErrorMessage();

    /**
     * エラー番号取得
     *
     * <pre>
     * 直前のエラー番号を取得する。
     * </pre>
     *
     * @return integer エラー番号
     * @access public
     */
    abstract public function getErrorNumber();

    /**
     * データベースの接続を調べる
     *
     * <pre>
     * サーバーとの接続が有効かどうか調べる。
     * </pre>
     *
     * @return bool
     * @access public
     */
    abstract public function ping();

    // まだ実装してない。
    // 忘れない様にメモ
    /* abstract public function transaction_begin(); */
    /* abstract public function transaction_commit(); */
    /* abstract public function transaction_rollback(); */


    /**
     * SQLの実行。
     *
     * <pre>
     * SQLを実行してResultクラスを返す。
     * </pre>
     *
     * @return object Resultクラス
     * @access public
     */
    public function execute($sql)
    {
        // 接続が切れていたら再接続
        if (!$this->ping()) {
            $this->initialize();
        }

        // 実行SQLの保持
        $save_query_data = array();
        $save_query_data['sql'] = $sql;

        // クエリ実行時間測定開始
        list($sm, $ss) = explode(' ', microtime());

        $this->res_id = $this->query($sql);

        // クエリ実行時間測定終了
        list($em, $es) = explode(' ', microtime());

        // 実行時間の保持
        $save_query_data['time'] = ($em + $es) - ($sm + $ss);

        $this->querys[] = $save_query_data;


        if (!$this->res_id) {
            $message = $this->getErrorMessage();

            logMessage('log', 'error', $sql . ' : ' .$this->getErrorMessage());
            showError(array(
                'Error : ' . $this->getErrorNumber(),
                $this->getErrorMessage()
            ));
        }

        $result = $this->loadResultDriver();
        $result->setConId($this->con_id);
        $result->setResId($this->res_id);


        if (Config::get('app_debug_sql')) {
            // SELECT文のみEXPLAINを発行する
            if (preg_match('/^select/i', $sql)) {
                $res = $this->query('explain ' . $sql);
                $explain = $this->loadResultDriver();
                $explain->setConId($this->con_id);
                $explain->setResId($res);

                logMessage('explain', 'debug', 'explain ' . $sql);
                logMessage('explain', 'debug', json_encode($explain->result('array')));
            }
        }

        return $result;
    }


    /**
     * Resultクラスの取得
     *
     * <pre>
     * Resultクラスを取得する。
     * newできない場合は、新たにrequireする。
     * </pre>
     *
     * @return object Resultクラス
     * @access public
     */
    private function loadResultDriver()
    {
        $result_class = sprintf(
            'DatabaseResult%s',
            Util::camelizeUcc($this->db_driver)
        );

        if (!class_exists($result_class)) {
            $base_path = sprintf(
                '%s/database_result.class.php',
                ME_CORE_DATABASE_DIR
            );

            $result_path = sprintf(
                '%s/drivers/%s/database_result_%s.class.php',
                ME_CORE_DATABASE_DIR,
                $this->db_driver,
                $this->db_driver
            );

            require_once $base_path;
            require_once $result_path;

            logMessage('log', 'debug', '[load] '.$base_path);
            logMessage('log', 'debug', '[load] '.$result_path);
        }

        return new $result_class();
    }
}
