<?php
/**
 * Meelia Error
 *
 * <pre>
 * エラー処理
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Core
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 51 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/error.class.php $
 */

/**
 * CoreError
 *
 * <pre>
 * エラー処理関連。
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/error.class.php $
 */
class CoreError
{
    /**
     * エラー出力処理
     *
     * <pre>
     * エラーを出力する。
     * デフォルトでは500 Internal Server Errorになるが
     * $status_codeを変更する事でエラーコードを変更できる。
     * 使用するテンプレートはerror_拡張子.php
     * </pre>
     *
     * @param string  $heading     エラー見出し
     * @param mixid   $message     エラー本文。配列でもいい
     * @param integer $status_code HTTPレスポンスコード
     *
     * @return string エラーページ
     * @access public
     */
    public function showError($heading, $message, $status_code = 500){
        // HTTPレスポンスコードとContent-Typeの設定
        $response =& Loader::loadLogic('response');
        $response->setStatusCode($status_code);

        $uri = Loader::loadLogic('uri');
        if ($uri->getUriSuffix() == 'html') {
            // 要求レスポンスがHTMLの場合は<p>タグで囲む
            if (is_array($message)) {
                $message = '<p>' . implode('</p><p>', $message) . '</p>';
            } else {
                $message = '<p>' . $message . '</p>';
            }
        } else {
            // HTML以外ならタブ区切り
            if (is_array($message)) {
                $message = implode("\t", $message);
            }
        }

        $core_template_path = ME_CORE_ERROR_DIR . '/error_' . $uri->getUriSuffix() . '.php';
        $app_template_path  = ME_APP_ERROR_DIR . '/error_' . $uri->getUriSuffix() . '.php';

        ob_start();

        // app側でテンプレートを用意しているならapp側を使う
        // 無ければcore側を使う。
        // 両方にあった場合はapp側優先
        if (file_exists($app_template_path)) {
            include $app_template_path;
        } else {
            include $core_template_path;
        }

        $buffer = ob_get_clean();
        return $buffer;
    }


    /**
     * 404ページ
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
    public function show404($page = ''){
        $heading = "404 Page Not Found";
        $message = "The page you requested was not found.";

        logMessage('log', 'error', '404 Page Not Found --> ' . $page);

        return $this->showError($heading, $message, 404);
    }
}

