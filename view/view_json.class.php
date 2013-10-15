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
 * @version   SVN: $Rev: 50 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/view/view_json.class.php $
 */

/**
 * ViewJson
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
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/view/view_json.class.php $
 */
class ViewJson extends View
{
    /**
     * レンダリングメソッド
     *
     * <pre>
     * セットされたデータ配列をJSONに変換して
     * 画面に出力する。
     * </pre>
     *
     * @param string $action_path viewのパス
     *
     * @return None
     * @access public
     */
    public function render($action_path = null)
    {
        $response =& Loader::loadLogic('response');
        $response->setContentType('application/json');

        $this->controller->setOutput(json_encode($this->vars));
    }


    /**
     * JSON取得メソッド
     *
     * <pre>
     * セットされたデータ配列のJSONを取得する。
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
        return json_encode($this->vars);
    }
}

