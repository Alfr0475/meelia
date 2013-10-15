<?php
/**
 * Meelia Session
 *
 * <pre>
 * セッション管理
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

/**
 * LogicSession
 *
 * <pre>
 * セッション管理クラス。
 * 現在はファイル保存のみの対応。
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL$
 */
class LogicSession
{
    /**
     * コンストラクタ
     *
     * <pre>
     * このクラスがインスタンス化された時点で
     * セッションが開始される。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function __construct()
    {
        if (!$this->isRead()) {
            $this->create();
        } else {
            $this->update();
        }
    }

    /**
     * セッションデータ格納
     *
     * <pre>
     * セッションデータを格納する。
     * </pre>
     *
     * @param string $key   セッションキー
     * @param mixid  $value セッション値
     *
     * @return None
     * @access public
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * セッションデータ取得
     *
     * <pre>
     * セッションデータを取得する。
     * </pre>
     *
     * @param string $key セッションキー
     *
     * @return void
     * @access public
     */
    public function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * セッションデータ存在確認
     *
     * <pre>
     * cookieからセッションデータの存在を確認する。
     * session.save_pathが設定されていない場合は
     * デフォルトの/tmpから探す。
     * </pre>
     *
     * @return None
     * @access public
     */
    protected function isRead()
    {
        if (array_key_exists(session_name(), $_COOKIE)) {
            $sid = $_COOKIE[session_name()];

            if (session_save_path()) {
                $sid_file_path = session_save_path() . '/sess_' . $sid;
            } else {
                // session.save_pathが設定されていない場合は/tmp
                $sid_file_path = '/tmp/sess_' . $sid;
            }

            if (file_exists($sid_file_path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * セッションID更新
     *
     * <pre>
     * セッションIDを更新する。
     * 更新されない状態で連続アクセスすると
     * 弾かれてしまうので注意。
     * </pre>
     *
     * @return None
     * @access public
     */
    protected function update()
    {
        session_start();

        // セッション情報を退避して削除
        $tmp = $_SESSION;
        $_SESSION = array();
        session_destroy();

        // セッションIDを更新
        $this->regenerate();

        // セッションを再開して退避した情報を格納
        session_start();
        $_SESSION = $tmp;
    }

    /**
     * セッション開始
     *
     * <pre>
     * セッションを開始する。
     * セッションハイジャック対策の為、$_SESSIONを初期化。
     * </pre>
     *
     * @return None
     * @access public
     */
    protected function create()
    {
        session_start();
        $_SESSION = array();
    }

    /**
     * セッション終了
     *
     * <pre>
     * セッションを終了する。
     * 終了時にcookieも削除する。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function end()
    {
        // Cookieの削除
        setcookie(session_name(), '', time()-42000);

        $_SESSION = array();
        session_destroy();
    }

    /**
     * セッションIDを更新する。
     *
     * <pre>
     * セッションIDを更新する。
     * </pre>
     *
     * @return None
     * @access public
     */
    protected function regenerate()
    {
        session_regenerate_id(true);
    }
}

