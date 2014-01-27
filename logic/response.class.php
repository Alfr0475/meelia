<?php
/**
 * Meelia Response
 *
 * <pre>
 * 出力管理
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Logic
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 49 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/response.class.php $
 */

use meelia\core\Config;
use meelia\core\Loader;

/**
 * LogicResponse
 *
 * <pre>
 * 出力管理クラス
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/response.class.php $
 */
class LogicResponse
{
    protected $headers = array();

    protected $mimes_mapping = array(
        'html' => 'text/html',
        'xml'  => 'text/xml',
        'json' => 'application/json',
        'yml'  => 'application/yaml',
    );

    /**
     * HTTPヘッダー設定
     *
     * <pre>
     * HTTPヘッダーを設定する。
     * </pre>
     *
     * @param string $header  HTTPヘッダー
     * @param bool   $replace 置換フラグ
     *
     * @return None
     *
     * @access public
     */
    public function setHeader($header, $replace = true)
    {
        $this->headers[] = array($header, $replace);
    }

    /**
     * ContentType設定
     *
     * <pre>
     * ContentTypeを設定する。
     * replaceが強制的にtrueなので
     * 多重で呼ばれても上書きされる。
     * </pre>
     *
     * @param string $mime_type mime_type
     * @param bool   $replace   置換フラグ
     *
     * @return None
     *
     * @access public
     */
    public function setContentType($mime_type = '')
    {
        $config_mimes = Config::get('mimes_mapping');

        // 拡張子とmime_typeのマッピング配列を
        // 設定ファイルで定義されているものとマージ
        if (is_array($config_mimes)) {
            $this->mimes_mapping = array_merge(
                $this->mimes_mapping,
                $config_mimes
            );
        }

        // もしmime_typeが指定されていなければ
        // URIの拡張子を見てmime_typeを設定する
        $uri = Loader::loadLogic('uri');
        if ($mime_type == '') {
            if (isset($this->mimes_mapping[$uri->getUriSuffix()])) {
                $mime_type = $this->mimes_mapping[$uri->getUriSuffix()];
            } else {
                $mime_type = $this->mimes_mapping['html'];
            }
        }

        $header = 'Content-Type: ' . $mime_type;

        $this->setHeader($header, true);
    }

    /**
     * HTTPヘッダー出力
     *
     * <pre>
     * HTTPヘッダーを出力する。
     * </pre>
     *
     * @return None
     *
     * @access public
     */
    public function outputHeader()
    {
        foreach($this->headers as $header){
            meelia\core\logMessage('log', 'debug', sprintf('[output] Header:%s', $header[0]));
            header($header[0], $header[1]);
        }
    }

    public function setStatusCode($code = 200, $text = '')
    {
        $status_array = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',

            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',

            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',

            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        if ($code == '' or !is_numeric($code)) {
            showError('Status codes must be numeric', 500);
        }

        if (isset($status_array[$code]) and $text == '') {
            $text = $status_array[$code];
        }

        if ($text == '') {
            showError('No status text available.  Please check your status code number or supply your own message text.', 500);
        }

        $server_protocol = false;
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $server_protocol = $_SERVER['SERVER_PROTOCOL'];
        }

        if ($server_protocol == 'HTTP/1.1' or $server_protocol == 'HTTP/1.0') {
            header($server_protocol . " {$code} {$text}", true, $code);
        } else {
            header("HTTP/1.1 {$code} {$text}", true, $code);
        }
    }
}

