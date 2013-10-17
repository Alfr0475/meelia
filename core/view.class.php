<?php
/**
 * Meelia View
 *
 * <pre>
 * Viewクラス。
 * 各Viewは継承する事。
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Core
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 39 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/view.class.php $
 */

/**
 * View
 *
 * <pre>
 * Viewの基本クラス。
 * 各Viewはこのクラスを継承する事。
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/view.class.php $
 */
abstract class View
{
    protected $controller;
    protected $vars = array();

    /**
     * コンストラクタ
     *
     * <pre>
     * Viewのコンストラクタ。
     * Controllerとの紐付けを行なっている。
     * </pre>
     *
     * @param object $controller コントローラーインスタンス
     *
     * @return None
     * @access public
     */
    public function __construct($controller)
    {
        $this->controller = $controller;
    }


    /**
     * コントローラー取得
     *
     * <pre>
     * コントローラーインスタンスを取得する。
     * Viewがインスタンス化された際に
     * コントローラーのインスタンスがメンバ変数に格納される。
     * </pre>
     *
     * @return object コントローラーのインスタンス
     * @access public
     */
    public function getController()
    {
        return $this->controller;
    }


    /**
     * View変数群の取得
     *
     * <pre>
     * View用の変数群取得。
     * KeyValue形式でView用変数が格納されている。
     * </pre>
     *
     * @return array View用変数群
     * @access public
     */
    public function getVars()
    {
        return $this->vars;
    }


    /**
     * View変数群の値設定
     *
     * <pre>
     * View用の変数を定義設定する。
     * $keyに設定したKey名がViewで変数として利用できる。
     * </pre>
     *
     * @param string $key   変数名
     * @param mixed  $value 値
     *
     * @return None
     * @access public
     */
    public function setVarsValue($key, $value)
    {
        $this->vars[$key] = $value;
    }


    /**
     * レンダリングメソッド
     *
     * <pre>
     * 画面に出力する。
     * このメソッドが呼ばれたら画面に出力される。
     * </pre>
     *
     * @param string $action_path viewのパス
     *
     * @return None
     * @access public
     */
    abstract public function render($action_path = null);

    /**
     * テンプレート取得メソッド
     *
     * <pre>
     * 出力内容を返す。
     * このメソッドでは画面出力はされない。
     * </pre>
     *
     * @param string $action_path viewのパス
     *
     * @return string OutputText
     * @access public
     */
    abstract public function fetch($action_path = null);
}

