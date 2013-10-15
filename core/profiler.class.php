<?php
/**
 * Meelia Profiler
 *
 * <pre>
 * 情報ツールバー管理
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
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/profiler.class.php $
 */

/**
 * CoreProfiler
 *
 * <pre>
 * 情報ツールバー管理クラス
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/profiler.class.php $
 */
class CoreProfiler
{
    protected $available_sections = array(
        'config',
        'time',
        'memory',
        'query',
    );


    /**
     * 情報ツールバー生成
     *
     * <pre>
     * 情報ツールバーを生成する。
     * </pre>
     *
     * @return string HTML
     * @access public
     */
    public function run()
    {
        $output = '';

        // JavaScriptとCSSを出力
        $output = implode("\n", array(
            '<script type="text/javascript">',
            $this->getJavaScript(),
            '</script>',
            '<style type="text/css">',
            $this->getStyleSheet(),
            '</style>',
        ));

        // 各セクションの取得
        $output_sections = '';
        $output_titles   = '';
        foreach ($this->available_sections as $section) {
            $function = 'getSection'.ucfirst($section);

            if ($section == 'time') {
                $benchmark = Loader::loadLogic('benchmark');
                $output_titles .= sprintf('<li><a href="JavaScript:;" onClick="meeliaProfilerShow(\'%s\')">%sms</a></li>', $section, $benchmark->elapsedTime('total_execution_time_start', 'total_execution_time_end', 3)*1000) . "\n";
                $output_sections .= $this->{$function}() . "\n";
            } elseif ($section == 'memory') {
                $output_titles .= sprintf('<li>%s KB</li>', round(memory_get_usage()/1024, 2)) . "\n";
            } else {
                $output_titles .= sprintf('<li><a href="JavaScript:;" onClick="meeliaProfilerShow(\'%s\')">%s</a></li>', $section, $section) . "\n";
                $output_sections .= $this->{$function}() . "\n";
            }
        }

        $output .= implode("\n", array(
            '<div id="meelia_profiler">',
            '  <div id="meelia_profiler_bar">',
            '    <ul id="meelia_profiler_menu">',
            $output_titles,
            '    </ul>',
            '  </div>',
            $output_sections,
            '</div>',
        ));

        return $output;
    }

    /**
     * 設定情報系のパネル生成
     *
     * <pre>
     * 設定情報系のパネルHTMLを生成する。
     * </pre>
     *
     * @return string HTML
     * @access protected
     */
    protected function getSectionConfig()
    {
        //
        // 設定ファイルの出力
        //
        $all_config_array = Config::get();
        asort($all_config_array);

        $config_data_array = array();
        foreach ($all_config_array as $key => $value) {
            // キーから設定種別を抽出
            $exploded = explode('_', $key, 2);
            $type = $exploded[0];

            if (!isset($config_data_array[$type])) {
                $config_data_array[$type] = array();
            }

            $config_data_array[$type][$exploded[1]] = $value;
        }

        $config_output = '';
        $config_output .= '<div id="meelia_profiler_detail_config_config" style="display:none">';
        foreach ($config_data_array as $type => $config_data){
            $config_output .= sprintf("<h3>%s.inc.php :</h3>", $type);
            $config_output .= '  <pre>';
            foreach ($config_data as $key => $value) {
                $config_output .= $key . " => " . var_export($value, true) . "\n";
            }
            $config_output .= '  </pre>';
        }
        $config_output .= '</div>';


        //
        // リクエストデータの出力
        //
        $router = Loader::loadLogic('router');
        $request_output = '';
        $request_output .= '<div id="meelia_profiler_detail_config_request" style="display:none">';
        $request_output .= '  <h3>ROUTER</h3>';
        $request_output .= '  <pre>';
        $request_output .= sprintf("Directory : %s\nClass : %s\nArgs : %s\n", $router->getDirectory(), $router->getClass(), var_export($router->getArgs(), true));
        $request_output .= '  </pre>';
        $request_output .= '  <h3>GET</h3>';
        $request_output .= '  <pre>';
        $request_output .= var_export($_GET, true) . "\n";
        $request_output .= '  </pre>';
        $request_output .= '  <h3>POST</h3>';
        $request_output .= '  <pre>';
        $request_output .= var_export($_POST, true) . "\n";
        $request_output .= '  </pre>';
        $request_output .= '  <h3>REQUEST</h3>';
        $request_output .= '  <pre>';
        $request_output .= var_export($_REQUEST, true) . "\n";
        $request_output .= '  </pre>';
        $request_output .= '  <h3>HEADER</h3>';
        $request_output .= '  <pre>';
        $request_output .= var_export(apache_request_headers(), true) . "\n";
        $request_output .= '  </pre>';
        $request_output .= '</div>';


        //
        // レスポンスデータの出力
        //
        $response_output = '';
        $response_output .= '<div id="meelia_profiler_detail_config_response" style="display:none">';
        $response_output .= '  <h3>HEADER</h3>';
        $response_output .= '  <pre>';
        $response_output .= var_export(apache_response_headers(), true) . "\n";
        $response_output .= '  </pre>';
        $response_output .= '  <h3>COOKIE</h3>';
        $response_output .= '  <pre>';
        $response_output .= var_export($_COOKIE, true) . "\n";
        $response_output .= '  </pre>';
        $response_output .= '</div>';


        // サーバー情報の出力
        $server_output = '';
        $server_output .= '<div id="meelia_profiler_detail_config_server" style="display:none">';
        $server_output .= '  <h3>SERVER</h3>';
        $server_output .= '  <pre>';
        $server_output .= var_export($_SERVER, true) . "\n";
        $server_output .= '  </pre>';
        $server_output .= '  <h3>SESSION</h3>';
        $server_output .= '  <pre>';
        if (isset($_SESSION)) {
            $server_output .= var_export($_SESSION, true) . "\n";
        } else {
            $server_output .= var_export(array(), true) . "\n";
        }
        $server_output .= '  </pre>';
        $server_output .= '</div>';


        // PHP設定情報の出力
        $php_output = '';
        $php_output .= '<div id="meelia_profiler_detail_config_php" style="display:none">';
        $php_output .= '  <h3>PHP</h3>';
        $php_output .= '  <pre>';
        $php_output .= var_export(ini_get_all(), true) . "\n";
        $php_output .= '  </pre>';
        $php_output .= '  <h3>EXTENSION</h3>';
        $php_output .= '  <pre>';
        $php_output .= var_export(get_loaded_extensions(), true);
        $php_output .= '  </pre>';
        $php_output .= '</div>';


        $output = implode("\n", array(
            '<div id="meelia_profiler_detail_config" class="meelia_profiler_panel" style="display:none">',
            ' <h1>Configuration</h1>',
            ' <h2>ConfigFiles <a href="JavaScript:;" onClick="JavaScript:meeliaProfilerToggle(\'meelia_profiler_detail_config_config\');">&gt;&gt;&gt;</a></h2>',
            $config_output,
            ' <h2>Request <a href="JavaScript:;" onClick="JavaScript:meeliaProfilerToggle(\'meelia_profiler_detail_config_request\');">&gt;&gt;&gt;</a></h2>',
            $request_output,
            ' <h2>Response <a href="JavaScript:;" onClick="JavaScript:meeliaProfilerToggle(\'meelia_profiler_detail_config_response\');">&gt;&gt;&gt;</a></h2>',
            $response_output,
            ' <h2>Server <a href="JavaScript:;" onClick="JavaScript:meeliaProfilerToggle(\'meelia_profiler_detail_config_server\');">&gt;&gt;&gt;</a></h2>',
            $server_output,
            ' <h2>PHP <a href="JavaScript:;" onClick="JavaScript:meeliaProfilerToggle(\'meelia_profiler_detail_config_php\');">&gt;&gt;&gt;</a></h2>',
            $php_output,
            '</div>',
        ));

        return $output;
    }

    /**
     * 実行時間系のパネル生成
     *
     * <pre>
     * 実行時間系のパネルHTMLを生成する。
     * </pre>
     *
     * @return string HTML
     * @access protected
     */
    protected function getSectionTime()
    {
        $benchmark = Loader::loadLogic('benchmark');
        $mark_array = $benchmark->getAllMark();

        $time_output = '';
        foreach ($mark_array as $key => $value) {
            if (preg_match("/(.+?)_end/i", $key, $match)) {
                if (isset($mark_array[$match[1].'_end']) and isset($mark_array[$match[1].'_start'])) {
                    $time_output .= '<tr>';
                    $time_output .= '  <td>';
                    $time_output .= ucwords(str_replace(array('-', '_'), ' ', $match[1]));
                    $time_output .= '  </td>';
                    $time_output .= '  <td>';
                    $time_output .= $benchmark->elapsedTime($match[1].'_start', $key);
                    $time_output .= '  </td>';
                    $time_output .= '</tr>';
                }
            }
        }

        $output = implode("\n", array(
            '<div id="meelia_profiler_detail_time" class="meelia_profiler_panel" style="display:none">',
            ' <h1>Timer</h1>',
            ' <table border="1" cellspacing="0", cellpadding="5">',
            '   <tr style="background-color:#000; color:#FFF">',
            '     <th>Marked name</th>',
            '     <th>Executed time</th>',
            '   </tr>',
            $time_output,
            ' </table>',
            '</div>',
        ));

        return $output;
    }

    /**
     * SQL系のパネル生成
     *
     * <pre>
     * SQL系のパネルHTMLを生成する。
     * </pre>
     *
     * @return string HTML
     * @access protected
     */
    protected function getSectionQuery()
    {
        $dbs = array();

        $all_config_array = Config::get();
        foreach ($all_config_array as $key => $value) {
            if (preg_match('/^database_(.+)$/i', $key, $match)) {
                //$dbs[$match[1]] = Loader::loadDatabase($match[1]);
            }
        }

        $database_output = '';
        foreach ($dbs as $db_key => $db) {
            $querys = $db->getQuerys();

            $database_output .= sprintf('<h2>%s : %d</h2>', $db_key, count($querys));
            $database_output .= '<table border="1" cellspacing="0" cellpadding="5">';
            $database_output .= '  <tr style="background-color:#000; color:#FFF">';
            $database_output .= '    <th>ExecTime</th>';
            $database_output .= '    <th>Query</th>';
            $database_output .= '  </tr>';
            foreach ($querys as $sql_data) {
                $database_output .= sprintf('<tr><td>%s</td><td>%s</td></tr>', number_format($sql_data['time'], 4), $sql_data['sql']);
            }
            $database_output .= '</table>';
        }

        $output = implode("\n", array(
            '<div id="meelia_profiler_detail_query" class="meelia_profiler_panel" style="display:none">',
            ' <h1>Query</h1>',
            $database_output,
            '</div>',
        ));

        return $output;
    }

    /**
     * JavaScript定義
     *
     * <pre>
     * 情報ツールバーの動作に必要な
     * JavaScript文を返す。
     * </pre>
     *
     * @return string HTML
     * @access protected
     */
    protected function getJavaScript()
    {
        return implode("\n", array(
            'function meeliaProfilerShow(profile_id){',
            '  element = document.getElementById(\'meelia_profiler_detail_\' + profile_id);',
            'console.log(element);',
            '',
            '  var panelElements = document.getElementByClassName(\'meelia_profiler_panel\');',
            '  for (var i = 0; i < panelElements.length; i++) {',
            '    if (panelElements[i] != element) {',
            '      panelElements[i].style.display = \'none\';',
            '    }',
            '  }',
            '',
            '  meeliaProfilerToggle(element);',
            '}',
            '',
            'function meeliaProfilerToggle(element){',
            '  if (typeof element == \'string\') {',
            '    element = document.getElementById(element);',
            '  }',
            '',
            '  if (element) {',
            '    element.style.display = element.style.display == \'none\' ? \'\' : \'none\';',
            '  }',
            '}',
            '',
            'if (!document.getElementByClassName) {',
            '  document.getElementByClassName = function(cl) {',
            '    var retnode = [];',
            '    var myclass = new RegExp(\'\\\\b\'+cl+\'\\\\b\');',
            '    var elem = document.getElementsByTagName(\'*\');',
            '    for (var i = 0; i < elem.length; i++) {',
            '      var classes = elem[i].className;',
            '      if (myclass.test(classes)) retnode.push(elem[i]);',
            '    }',
            '    return retnode;',
            '  };',
            '}',
        ));
    }

    /**
     * CSS定義
     *
     * <pre>
     * 情報ツールバーの見た目に必要な
     * CSS文を返す。
     * </pre>
     *
     * @return string HTML
     * @access protected
     */
    protected function getStyleSheet()
    {
        return implode("\n", array(
            '#meelia_profiler {',
            '  padding: 0px;',
            '  margin: 0px;',
            '  font-size: 12px;',
            '  color: #333;',
            '  text-align: left;',
            '  line-height: 12px;',
            '}',
            '',
            '#meelia_profiler_bar {',
            '  position: fixed;',
            '  padding: 1px 0px;',
            '  margin: 0px;',
            '  top: 0px;',
            '  right: 0px;',
            '  opacity: 0.80;',
            '  z-index: 10000;',
            '}',
            '',
            '#meelia_profiler_menu {',
            '  padding: 5px;',
            '  padding-left: 0px;',
            '  margin: 0px;',
            '  display: inline;',
            '  background-color: #DDD',
            '}',
            '',
            '#meelia_profiler_menu li{',
            '  display: inline;',
            '  list-style: none;',
            '  margin: 0px;',
            '  padding: 0px 6px;',
            '}',
            '',
            '.meelia_profiler_panel {',
            '  position: absolute;',
            '  left: 0px;',
            '  top: 0px;',
            '  width: 98%;',
            '  padding: 0px 1%;',
            '  margin: 0px;',
            '  z-index: 9999;',
            '  background-color: #EFEFEF;',
            '  border-bottom: 1px solid #AAA;',
            '}',
        ));
    }
}

