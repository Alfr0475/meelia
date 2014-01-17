<?php
/**
 * Meelia Controller
 *
 * <pre>
 * コントローラーのベース。
 * 基本的にAPP側のコントローラーはこれを継承して作成する。
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Core
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 57 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/controller.class.php $
 */

namespace meelia\core;

/**
 * Controller
 *
 * <pre>
 * コントローラーのベースクラス。
 * APP側のコントローラーはこのクラスを継承して作成する。
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/controller.class.php $
 */
abstract class Controller
{
    private $name;
    private $action;
    private $action_function;
    private $view;

    private $view_mapping = array(
        'html' => 'ViewHtml',
        'json' => 'ViewJson',
        'xml'  => 'ViewXml',
        'yml'  => 'ViewYaml'
    );

    protected $output;

    /**
     * 初期化処理
     *
     * <pre>
     * Viewをインスタンス化する。
     * </pre>
     *
     * @param string $name コントローラークラス名
     *
     * @return None
     * @access public
     */
    public function initialize($name)
    {
        $request = Loader::loadLogic('request');

        $this->name   = $name;
        $this->action = $request->getRequestMethod();
        $this->view   = $this->getFactoryView();

        // REQUEST_METHODによって呼び出すコントローラーのメソッドを変える。
        // GETだったら呼び出されるメソッドはexecuteGetになる。
        $this->action_function = strtolower(
            'execute' . Util::camelizeUcc($request->getRequestMethod())
        );
    }

    /**
     * 指定された拡張子のViewインスタンス取得
     *
     * <pre>
     * URIで指定された拡張子用のViewを取得する。
     * </pre>
     *
     * @return string コントローラー名
     * @access public
     */
    public function getFactoryView()
    {
        $config_view_mapping = Config::get('app_view_mapping');

        // 拡張子とViewクラス名のマッピング配列を取得
        if (is_array($config_view_mapping)) {
            $this->view_mapping = array_merge(
                $this->view_mapping,
                $config_view_mapping
            );
        }

        $uri = Loader::loadLogic('uri');

        $class = $this->view_mapping['html'];
        if (array_key_exists($uri->getUriSuffix(), $this->view_mapping)) {
            $class = $this->view_mapping[$uri->getUriSuffix()];
        }

        if (!class_exists($class)) {
            // CamelCase -> under_score
            // e修飾子を付けることで変換後部分がPHPコードとして認識される
            // 大文字を抜き出して、大文字の前に_を付けてる
            // lcfirstをする事で先頭文字に_が付かないようにしてる
            $file = Util::toSnakeCase($class) . '.class.php';
            $view_file_path = ME_CORE_VIEW_DIR . '/' . $file;

            // Meeliaで用意してあるViewクラス群を確認
            if (!file_exists($view_file_path)) {
                showError('View class not found.');
            } else {
                include_once $view_file_path;

                // ファイルがあってもクラスが存在しなければエラー
                if (!class_exists($class)) {
                    showError('View class not found.');
                }
            }
        }

        return new $class($this);
    }

    /**
     * コントローラー名取得
     *
     * <pre>
     * コントローラー名を取得する。
     * コントローラー名は初期化処理時に設定する。
     * </pre>
     *
     * @return string コントローラー名
     * @access public
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * アクション名取得
     *
     * <pre>
     * アクション名を取得する。
     * アクション名はsetterで設定する。
     * </pre>
     *
     * @return string アクション名
     * @access public
     */
    public function getAction()
    {
        return $this->action;
    }


    /**
     * Viewインスタンス取得
     *
     * <pre>
     * Viewのインスタンスを取得する。
     * コントローラーをインスタンス化した時点で
     * Viewもインスタンス化される。
     * </pre>
     *
     * @return object Viewインスタンス
     * @access public
     */
    public function getView()
    {
        return $this->view;
    }


    /**
     * 画面出力文字列取得
     *
     * <pre>
     * 画面に出力する文字列を取得する。
     * 画面に出力される最終文字列が格納されている。
     * </pre>
     *
     * @return string 出力文字列
     * @access public
     */
    public function getOutput()
    {
        return $this->output;
    }


    /**
     * 出力文字列設定
     *
     * <pre>
     * 出力用文字列を格納する。
     * このメンバ変数に格納されている文字列が出力される。
     * </pre>
     *
     * @param string $output 出力文字列
     *
     * @return None
     * @access public
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }


    /**
     * アクション存在チェック
     *
     * <pre>
     * コントローラーに指定したアクションのメソッドが
     * 存在するかのチェックメソッド。
     * </pre>
     *
     * @return bool
     * @access public
     */
    public function existAction()
    {
        $request = Loader::loadLogic('request');
        $action_function = strtolower(
            'execute' . Util::camelizeUcc($request->getRequestMethod())
        );

        if (method_exists($this, $action_function)) {
            return true;
        }

        return false;
    }


    /**
     * アクション予約語チェック
     *
     * <pre>
     * コントローラーでアクション名が使えるかを
     * 確認するメソッド。
     * </pre>
     *
     * @param string $action アクション名
     *
     * @return bool
     * @access public
     */
    public function reservedAction($action)
    {
        $methods = get_class_methods($this);
        if (in_array($action, $methods)) {
            return false;
        }

        return true;
    }


    /**
     * アクションの前処理
     *
     * <pre>
     * アクションが実行される前に処理されるメソッド。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function beforeProcess()
    {
    }


    /**
     * アクションの後処理
     *
     * <pre>
     * アクションが実行された後に処理されるメソッド。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function afterProcess()
    {
    }


    /**
     * 出力の前処理
     *
     * <pre>
     * 出力処理を行う前に処理されるメソッド。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function beforeRender()
    {
    }


    /**
     * View変数のセット
     *
     * <pre>
     * View用の変数を設定する。
     * 配列でもKeyValue形式で指定しても大丈夫。
     * </pre>
     *
     * @param mixed $key   配列かキー
     * @param mixed $value 値
     *
     * @return None
     * @access public
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->view->setVarsValue($k, $v);
            }
        } else {
            $this->view->setVarsValue($key, $value);
        }
    }


    /**
     * アクション実行
     *
     * <pre>
     * 一通り、アクションのチェックをしたら
     * アクションを実行する。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function execAction()
    {
        //コントローラーでアクション名が使えるかチェック
        if (!$this->reservedAction($this->action)) {
            show404($this->name . '/' . $this->action);
        }

        //コントローラーに指定したアクションのメソッドが使えるかチェック
        if (!$this->existAction()) {
            show404($this->name . '/' . $this->action);
        }

        $method = new ReflectionMethod($this, $this->action_function);
        if (!$method->isPublic()) {
            show404($this->name . '/' . $this->action);
        }

        $router   = Loader::loadLogic('router');
        $callback = array($this, $this->action_function);
        $args     = $router->getArgs();

        logMessage(
            'log',
            'debug',
            sprintf(
                '[start] %s::%s',
                get_class($this),
                $this->action_function
            )
        );

        //コールバックにかかる処理時間を計る
        $benchmark =& Loader::loadLogic('benchmark');
        $benchmark->mark('controller_execution_time_start');
        call_user_func_array($callback, $args);
        $benchmark->mark('controller_execution_time_end');

        logMessage(
            'log',
            'debug',
            sprintf(
                '[end] %s::%s',
                get_class($this),
                $this->action_function
            )
        );

        //出力結果を生成する
        $this->render();
    }


    /**
     * 描画処理
     *
     * <pre>
     * Viewを呼び出して画面に描画する。
     * 二重で呼び出されないようにチェック。
     * </pre>
     *
     * @param string $action_path アクションパス
     *
     * @return None
     * @access public
     */
    public function render($action_path = null)
    {
        static $is_rendered = false;

        if ($is_rendered) {
            return;
        }

        //描画の前処理
        logMessage('log', 'debug', '[start] Controller::beforeRender');
        $this->beforeRender();
        logMessage('log', 'debug', '[end] Controller::beforeRender');

        //処理時間と使用メモリー量（KB単位）を計測
        $benchmark =& Loader::loadLogic('benchmark');
        $elapsed = $benchmark->elapsedTime('total_execution_time_start', 'total_execution_time_end');
        $memory  = round(memory_get_usage()/1024, 2) . 'KB';

        $this->set('elapsed_time', $elapsed);
        $this->set('memory_usage', $memory);

        //指定した形式の出力結果を生成する
        logMessage('log', 'debug', '[start] View::render');
        $this->view->render($action_path);
        logMessage('log', 'debug', '[end] View::render');

        $is_rendered = true;

        //ProfilerをHTML($this->output)に追記
        if (Config::get('app_profiler')) {
            if (preg_match("|</body>.*?</html>|is", $this->output)) {
                $profiler = Loader::loadCore('profiler');
                $this->output  = preg_replace("|</body>.*?</html>|is", '', $this->output);
                $this->output .= $profiler->run();
                $this->output .= '</body></html>';
            }
        }
    }
}
