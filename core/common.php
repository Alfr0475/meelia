<?php
/**
 * Meelia Common functions
 *
 * <pre>
 * 汎用関数群
 *
 * PHP versions 5
 * </pre>
 *
 * @category   Core
 * @package    Meelia
 * @author     Seiki Koga <seikikoga@gamania.com>
 * @copyright  2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @version    SVN: $Rev: 50 $
 * @access     public
 * @link       $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/common.php $
 */

/**
 * logMessage
 *
 * <pre>
 * ログ出力を行う。
 * </pre>
 *
 * @param string $prefix  ファイル名PREFIX
 * @param string $level   ログレベル名
 * @param string $message ログ内容
 *
 * @return None
 * @access public
 */
if (!function_exists('logMessage')) {
    function logMessage($prefix, $level, $message)
    {
        static $log;

        if (!Config::get('log_logging')) {
            return;
        }

        $log = Loader::loadCore('log');
        $log->write($prefix, $level, $message);
    }
}

/**
 * エラーページ出力
 *
 * <pre>
 * エラーページを出力する。
 * </pre>
 *
 * @param mixid   $message     エラー本文。配列でもいい
 * @param integer $status_code HTTPレスポンスコード
 * @param string  $heading     エラー見出し
 *
 * @return None
 * @access public
 */
if (!function_exists('showError')) {
    function showError($message, $status_code = 500, $heading = 'An Error Was Encountered')
    {
        $response =& Loader::loadLogic('response');
        $response->setContentType(); // 拡張子でmime_typeを判断するので引数無し
        $response->outputHeader();

        $error = Loader::loadCore('error');
        echo $error->showError($heading, $message, $status_code);
        exit();
    }
}

/**
 * 404ページ出力
 *
 * <pre>
 * 404ページを出力する。
 * </pre>
 *
 * @param string $page 対象ページ
 *
 * @return None
 * @access public
 */
if (!function_exists('show404')) {
    function show404($page = '')
    {
        $response =& Loader::loadLogic('response');
        $response->setContentType(); // 拡張子でmime_typeを判断するので引数無し
        $response->outputHeader();

        $error = Loader::loadCore('error');
        echo $error->show404($page);
        exit();
    }
}

