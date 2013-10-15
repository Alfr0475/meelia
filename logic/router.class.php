<?php
/**
 * Meelia Router
 *
 * <pre>
 * URLとプログラムのマッピング
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
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/router.class.php $
 */

/**
 * LogicRouter
 *
 * <pre>
 * URLから実行するプログラムを決定するクラス。
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/router.class.php $
 * @access   public
 */
class LogicRouter
{
    protected $directory = '';
    protected $class     = '';
    protected $args      = array();

    protected $default_controller;

    /**
     * ルーティング処理
     *
     * <pre>
     * URLから各種情報を設定する。
     * </pre>
     *
     * @return None
     *
     * @access public
     */
    public function setRouting()
    {
        logMessage('log', 'debug', '[start] Router::setRouting');

        //app/config/router.inc.phpの
        //デフォルト設定されたコントローラーを呼ぶ
        $this->default_controller = Config::get('router_default_controller');

        //URIの情報を解析して保持
        $uri =& Loader::loadLogic('uri');
        $uri->fetchUriString();

        if ($uri->getUriString() == '/') {
            $this->configureDefaultController();
        } else {
            $this->recurrenceParseUri($uri->getSegmentsAll());
        }

        logMessage('log', 'debug', '[end] Router::setRouting');
    }

    /**
     * URI解析処理
     *
     * <pre>
     * URIの階層を再帰的に処理していき
     * クラス名、メソッド名、引数等を取得する。
     * </pre>
     *
     * @param array $segments 処理している階層のURI配列
     *
     * @return None
     *
     * @access protected
     */
    protected function recurrenceParseUri($segments)
    {
        $file_path = implode('/', $segments) . '.class.php';

        if (file_exists(ME_APP_CONTROLLER_DIR . '/' . $file_path)) {
            $uri =& Loader::loadLogic('uri');

            $all_segments   = $uri->getSegmentsAll();
            $segments_count = count($segments);

            // 全てのセグメント要素配列から、現在解析中のセグメント数 - 1を
            // 抽出することでディレクトリの配列とする。
            // 常にこのメソッドで処理する$segmentsの最後の要素はクラス要素のため。
            $directory_ary = array_slice($all_segments, 0, $segments_count - 1);

            // $segments_countは要素数になっているから-1で最後の要素指定
            $this->class     = $segments[$segments_count - 1];

            // $all_segmentsの$segments_count以降の要素を取得する。
            // ディレクトリ/コントローラー/それ以降。つまりURI引数部分
            $this->args      = array_slice($all_segments, $segments_count);
            $this->directory = implode('/', $directory_ary);
        } else {
            // $segmentsが2以上あるなら1個減らして再帰処理
            if (count($segments) > 1) {
                $segments = array_slice($segments, 0, count($segments) - 1);
                $this->recurrenceParseUri($segments);
            }
        }
    }

    /**
     * デフォルトクラスの設定
     *
     * <pre>
     * URIが空の時に実行されるクラスを設定する。
     * </pre>
     *
     * @return None
     *
     * @access protected
     */
    protected function configureDefaultController()
    {
        // default_controllerに/が含まれているか確認する
        // 含まれている場合は階層構造になってる
        // /で始まっている場合や、/で終わってる場合もある
        if (strpos($this->default_controller, '/') !== false) {
            $path = explode('/', $this->default_controller);

            // $pathのcountが2以上であれば階層構造になっている
            // なのでディレクトリのパスを取得する
            if (count($path) >= 2) {
                // $pathの最後の要素がクラス要素になるので
                // それ以外を抽出して/で結合してディレクトリパスにする
                $directory_ary   = array_slice($path, 0, count($path) - 1);
                $this->directory = implode('/', $directory_ary);
            }

            // $pathの最後の要素がクラス要素
            $this->class = $path[count($path) - 1];
        } else {
            $this->class = $this->default_controller;
        }
    }

    /**
     * コントローラーのパス取得
     *
     * <pre>
     * コントローラーのディレクトリパスを返す。
     * </pre>
     *
     * @return string ディレクトリパス
     *
     * @access public
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * コントローラーのクラス取得
     *
     * <pre>
     * コントローラーのクラス名を返す。
     * </pre>
     *
     * @return string クラス名
     *
     * @access public
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * コントローラーの引数配列取得
     *
     * <pre>
     * コントローラーの引数配列を返す。
     * </pre>
     *
     * @return array 引数配列
     *
     * @access public
     */
    public function getArgs()
    {
        return $this->args;
    }
}

