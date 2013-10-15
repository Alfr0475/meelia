<?php
/**
 * Meelia Uri
 *
 * <pre>
 * URI管理。
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Logic
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 57 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/uri.class.php $
 */

/**
 * LogicUri
 *
 * <pre>
 * URI管理クラス。
 * URI系の解析等をするクラス。
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/uri.class.php $
 */
class LogicUri
{
    protected $uri_string = '';
    protected $uri_suffix = 'html';

    protected $segments = array();

    /**
     * URIから解析情報を設定する
     *
     * <pre>
     * URIの情報を解析して保持する。
     * </pre>
     *
     * @return None
     *
     * @access public
     */
    public function fetchUriString()
    {
        logMessage('log', 'debug', '[start] Uri::fetchUriString');

        //URI情報を取得（QUERY_STRING以外）
        $this->uri_string = $this->detectUri();
        //URIから拡張子を設定
        $this->fetchUriSuffix();
        //URIから拡張子を削除
        $this->removeUriSuffix();
        //URIを分割
        $this->explodeSegments();

        logMessage('log', 'debug', '[end] Uri::fetchUriString');
    }

    /**
     * URIを取得
     *
     * <pre>
     * URIを取得する。
     * QUERY_STRINGは含まれない。
     * </pre>
     *
     * @return string PATH_INFO URI
     *
     * @access public
     */
    public function getUriString()
    {
        return $this->uri_string;
    }

    /**
     * URIを解析してPATH_INFO取得
     *
     * <pre>
     * PATH_INFO文字列を取得する。
     * mod_rewriteにも対応。
     * </pre>
     *
     * @return string PATH_INFO URI
     * @access protected
     */
    protected function detectUri()
    {
        if (!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        $uri = $this->parseArgument();

        // PATH_INFOとQUERY_STRINGに分割
        $parts = preg_split('/\?/i', $uri, 2);
        $uri   = $parts[0];

        // グローバル変数の内容を再定義する
        $this->setEnvironmentVariable($parts);

        if ($uri == '/' || empty($uri)) {
            return '/';
        }

        $uri = parse_url($uri, PHP_URL_PATH);

        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }

    /**
     * 環境変数の再定義
     *
     * <pre>
     * URIの解析情報から環境変数を再定義する。
     * </pre>
     *
     * @param array $parts URIを?で分割した配列
     *
     * @return None
     *
     * @access protected
     */
    protected function setEnvironmentVariable($parts)
    {
        if (isset($parts[1])) {
            $_SERVER['QUERY_STRING'] = $parts[1];
            parse_str($_SERVER['QUERY_STRING'], $_GET);
        } else {
            $_SERVER['QUERY_STRING'] = '';
            $_GET                    = array();
        }
    }

    /**
     * URIの解析処理
     *
     * <pre>
     * URIを解析する。
     * mod_rewriteにも対応。
     * </pre>
     *
     * @return string 解析後のURI
     *
     * @access protected
     */
    protected function parseArgument()
    {
        // mod_rewriteとクライアント提供のパス(PATH_INFO)対応
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            // path/to/index.php/hoge/moge?hoge=moge => /hoge/moge?hoge=moge
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
            // path/to/hoge/moge?hoge=moge => /hoge/moge?hoge=moge
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        return $uri;
    }

    /**
     * URIの拡張子設定
     *
     * <pre>
     * URIをから拡張子を設定する。
     * 拡張子がない場合はhtmlになる。
     * </pre>
     *
     * @return None
     *
     * @access protected
     */
    protected function fetchUriSuffix()
    {
        if (preg_match('/\.(\w+)$/i', $this->uri_string, $matches)) {
            $this->uri_suffix = strtolower($matches[1]);
        }
    }

    /**
     * URIの拡張子取得
     *
     * <pre>
     * URIの拡張子を取得する。
     * 拡張子がない場合はhtmlになる。
     * </pre>
     *
     * @return string 拡張子
     *
     * @access public
     */
    public function getUriSuffix()
    {
        return $this->uri_suffix;
    }

    /**
     * URIから拡張子を削除
     *
     * <pre>
     * URIから拡張子を削除する。
     * 拡張子がない場合は特に何もしない。
     * </pre>
     *
     * @return None
     *
     * @access protected
     */
    protected function removeUriSuffix()
    {
        $uri_suffix = preg_quote($this->uri_suffix);
        $pattern    = '/\.'.$uri_suffix.'$/';

        $this->uri_string = preg_replace($pattern, '', $this->uri_string);
    }

    /**
     * URIを分割
     *
     * <pre>
     * URIを分割する。
     * </pre>
     *
     * @return None
     *
     * @access protected
     */
    protected function explodeSegments()
    {
        $explode_uri = explode('/', $this->uri_string);
        foreach ($explode_uri as $val) {
            if ($val != '') {
                $this->segments[] = $val;
            }
        }
    }

    /**
     * URIから各セグメント取得
     *
     * <pre>
     * URIから指定したindexのセグメントを取得する。
     * </pre>
     *
     * @param integer $index セグメントのインデックス値
     *
     * @return string selected segment
     *
     * @access public
     */
    public function getSegments($index)
    {
        if (array_key_exists($index, $this->segments)) {
            return $this->segments[$index];
        }

        return null;
    }

    /**
     * セグメント配列を取得
     *
     * <pre>
     * セグメント配列を取得する。
     * </pre>
     *
     * @return array セグメント配列
     *
     * @access public
     */
    public function getSegmentsAll()
    {
        return $this->segments;
    }
}

