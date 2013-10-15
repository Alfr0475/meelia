<?php
/**
 * Meelia View
 *
 * <pre>
 * YAML出力
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
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/view/view_yaml.class.php $
 */

require_once ME_CORE_VENDOR_DIR . '/Spyc/spyc.php';

/**
 * ViewJson
 *
 * <pre>
 * YAML出力が要求された場合にこのクラスが使われる。
 * </pre>
 *
 * @category View
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/view/view_yaml.class.php $
 */
class ViewYaml extends View
{
    /**
     * レンダリングメソッド
     *
     * <pre>
     * セットされたデータ配列をYAMLに変換して
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
        $response->setContentType('application/yaml');

        $output = '';
        if (function_exists('yaml_emit')) {
            $output = yaml_emit($this->vars);
        } else {
            $output = Spyc::YAMLDump($this->vars);
        }

        $this->controller->setOutput($output);
    }


    /**
     * YAML取得メソッド
     *
     * <pre>
     * セットされたデータ配列のYAMLを取得する。
     * このメソッドでは画面出力はされない。
     * </pre>
     *
     * @param string $action_path viewのパス
     *
     * @return string YAML
     * @access public
     */
    public function fetch($action_path = null)
    {
        $output = '';
        if (function_exists('yaml_emit')) {
            $output = yaml_emit($this->vars);
        } else {
            $output = Spyc::YAMLDump($this->vars);
        }

        return $output;
    }
}

