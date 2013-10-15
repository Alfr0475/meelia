<?php
/**
 * Meelia Dispatcher
 *
 * <pre>
 * リクエストをコントローラーに紐付ける。
 * アクションを確定する等するクラスファイル。
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
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/dispatcher.class.php $
 */

/**
 * Dispatcher
 *
 * <pre>
 * class概要
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/dispatcher.class.php $
 */
class Dispatcher
{
    /**
     * 起動メソッド
     *
     * <pre>
     * Meeliaの起動メソッド。
     * index.phpからこのメソッドを呼ぶことで
     * コントローラーのアクションを呼ぶことが出来る。
     * </pre>
     *
     * @return None
     * @access public
     */
    public static function invoke()
    {
        //ログ：メソッド開始
        logMessage('log', 'debug', '[start] Dispatcher::invoke');

        //meelia/logic/benchmarkを読込み
        //時間測定開始
        $benchmark =& Loader::loadLogic('benchmark');
        $benchmark->mark('total_execution_time_start');

        //meelia/logic/routerを読込み
        //URLからクラス、メソッド、引数など各種情報を設定
        $router =& Loader::loadLogic('router');
        $router->setRouting();

        logMessage(
            'log',
            'info',
            sprintf(
                'DIR:%s CLASS:%s ARGS:%s',
                $router->getDirectory(),
                $router->getClass(),
                implode(',', $router->getArgs())
            )
        );

        if ($router->getDirectory()) {
            //コントローラーディレクトリのパスがある場合
            //コントローラーディレクトリとクラス名を作成
            $controller_path = $router->getDirectory() . '/' . $router->getClass();
        } else {
            //コントローラーのクラス名を作成
            $controller_path = $router->getClass();
        }

        //コントローラーを読み込む
        $controller = Loader::loadController($controller_path);
        $controller->initialize($router->getClass());

        logMessage('log', 'debug', '[start] Controller::beforeProcess');
        //コントローラーのアクションの前処理
        $controller->beforeProcess();
        logMessage('log', 'debug', '[end] Controller::beforeProcess');

        //コントローラーのアクションを実行
        //プロファイラーも追加する
        $controller->execAction();

        logMessage('log', 'debug', '[start] Controller::afterProcess');
        //コントローラーのアクションの後処理
        $controller->afterProcess();
        logMessage('log', 'debug', '[end] Controller::afterProcess');

        //VIEW
        //ヘッダーの出力
        $response = Loader::loadLogic('response');
        $response->outputHeader();

        //出力結果の表示
        echo $controller->getOutput();

        logMessage('log', 'debug', '[end] Dispatcher::invoke');
    }
}

