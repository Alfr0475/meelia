<?php
/**
 * Meelia View
 *
 * <pre>
 * XML出力
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

/**
 * ViewJson
 *
 * <pre>
 * XML出力が要求された場合にこのクラスが使われる。
 * </pre>
 *
 * @category View
 * @package  Core
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL$
 */
class ViewXml extends View
{
    /**
     * レンダリングメソッド
     *
     * <pre>
     * セットされたデータ配列をXMLに変換して
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
        $response =& Loader::loadCore('response');
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
     * XML取得メソッド
     *
     * <pre>
     * セットされたデータ配列のXMLを取得する。
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

