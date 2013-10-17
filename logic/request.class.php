<?php
/**
 * Meelia Request
 *
 * <pre>
 * リクエスト管理
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Logic
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 53 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/request.class.php $
 */

/**
 * LogicRequest
 *
 * <pre>
 * リクエスト管理クラス
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/request.class.php $
 */
class LogicRequest
{
    /**
     * REQUEST_METHOD取得
     *
     * <pre>
     * REQUEST_METHODを取得する。
     * 小文字で取得。
     * </pre>
     *
     * @return string REQUEST_METHOD
     * @access public
     */
    public function getRequestMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Query取得
     *
     * <pre>
     * クエリーを指定して取得する。
     * キーを指定しなかった場合は配列で全て取得する。
     * </pre>
     *
     * @param string $key クエリーキー
     *
     * @return mixid キー指定なら配列。指定無しなら文字列
     * @access public
     */
    public function get($key = '')
    {
        if ($key) {
            if (array_key_exists($key, $_REQUEST)) {
                return $_REQUEST[$key];
            }
        } else {
            return $_REQUEST;
        }

        return null;
    }
}

