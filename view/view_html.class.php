<?php
/**
 * Meelia View
 *
 * <pre>
 * HTML出力
 *
 * PHP versions 5
 * </pre>
 *
 * @category  View
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev$
 * @access    public
 * @link      $HeadURL$
 */

use meelia\core\View;
use meelia\core\Controller;

/**
 * ViewHtml
 *
 * <pre>
 * HTML出力が要求された場合にこのクラスが使われる。
 * </pre>
 *
 * @category View
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL$
 */
class ViewHtml extends View
{
    protected $ext  = '.php';

    /**
     * レンダリングメソッド
     *
     * <pre>
     * テンプレートの変数を置換して出力変数に代入する。
     * このメソッドが呼ばれたら画面に出力される。
     * </pre>
     *
     * @param string $action_path viewのパス
     *
     * @return None
     * @access public
     */
    public function render($action_path = null)
    {
        //HTMLとして表示するよう設定する
        $response =& Loader::loadLogic('response');
        $response->setContentType('text/html');

        //$action_pathからテンプレートのパスを返す
        //(nullなら現在のコントローラー名のディレクトリ直下にあるパスを返す)
        $view_file = $this->getViewFile($action_path);

        //テンプレートに記述している変数を実行して出力内容を生成
        $content = $this->extract($view_file, $this->vars);

        //上で生成されたHTMLを表示
        $this->controller->setOutput($content);
    }


    /**
     * テンプレート取得メソッド
     *
     * <pre>
     * テンプレートの変数を置換してHTMLを返す。
     * このメソッドでは画面出力はされない。
     * </pre>
     *
     * @param string $action_path viewのパス
     *
     * @return string HTML
     * @access public
     */
    public function fetch($action_path = null)
    {
        $view_file = $this->getViewFile($action_path);

        return $this->extract($view_file, $this->vars);
    }


    /**
     * テンプレートパス取得メソッド
     *
     * <pre>
     * $action_pathからテンプレートのパスを返す。
     * $action_pathがnullだった場合は
     * 現在のコントローラー名のディレクトリ直下にあるパスを返す。
     *
     * $this->getViewFile();             // 現在のコントローラー/アクションのビュー
     * $this->getViewFile('hoge');       // 現在のコントローラー/hogeのビュー
     * $this->getViewFile('hoge/moge');  // 現在のコントローラー/hoge/mogeのビュー
     * $this->getViewFile('/hoge');      // hogeのビュー
     * $this->getViewFile('/hoge/moge'); // hoge/mogeのビュー
     * </pre>
     *
     * @param string $action_path viewのパス
     *
     * @return string HTML
     * @access protected
     */
    protected function getViewFile($action_path = null)
    {
        $slash_pos = strpos($action_path, '/');
        if ($slash_pos === false) {
            // 階層指定されてない
            if (is_null($action_path)) {
                $action_path = $this->controller->getName() . '/' . $this->controller->getAction();
            } else {
                $action_path = $this->controller->getName() . '/' . $action_path;
            }
        } elseif ($slash_pos === 0) {
            // 絶対パス指定されてる
            $action_path = substr($action_path, 1);
        } else {
            // 相対パス指定されてる
            $action_path = $this->controller->getName() . '/' . $action_path;
        }

        return ME_APP_VIEW_DIR . '/' . $action_path . $this->ext;
    }


    /**
     * テンプレート変数置換メソッド
     *
     * <pre>
     * 実際にテンプレートに記述している変数を実行して
     * 出力内容を生成する。
     * </pre>
     *
     * @param string $file テンプレートのパス
     * @param array  $vars テンプレート変数配列
     *
     * @return string HTML
     * @access protected
     */
    protected function extract($file, $vars)
    {
        if (!file_exists($file)) {
            showError("{$file} is not found.");
        }

        extract($vars, EXTR_SKIP);
        ob_start();
        ob_implicit_flush(false);
        include $file;
        $out = ob_get_clean();
        return $out;
    }
}

