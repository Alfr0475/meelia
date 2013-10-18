<?php
/**
 * Meelia Log
 *
 * <pre>
 * ログ出力
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Core
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 49 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/log.class.php $
 */

/**
 * Log
 *
 * <pre>
 * Log出力を行うクラス。
 * 基本的にファイル出力。
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/log.class.php $
 */
class CoreLog
{
    protected $enable = true;
    protected $log_path;
    protected $threshold;
    protected $date_format = 'Y-m-d H:i:s';
    protected $levels = array(
        'ERROR' => 1,
        'INFO'  => 2,
        'DEBUG' => 3,
        'ALL'   => 4
    );

    public function __construct()
    {
        $logging     = Config::get('log_logging');
        $log_path    = Config::get('log_dir_path');
        $threshold   = Config::get('log_threshold'); // ログ閾値
        $date_format = Config::get('log_date_format');

        $this->log_path = ($log_path != '') ? $log_path : ME_APP_LOG_DIR;

        // 書き込み権限が無ければログ出力をOFFにする
        if (!is_dir($this->log_path) or !is_writable($this->log_path)) {
            $this->enable = false;
        }

        if (!$logging) {
            $this->enable = false;
        }

        if (is_numeric($threshold)) {
            $this->threshold = $threshold;
        }

        if ($date_format != '') {
            $this->date_format = $date_format;
        }
    }


    /**
     * ファイルに出力
     *
     * <pre>
     * ファイルにログを出力する。
     * 出力対象ファイルは$type.log。
     * </pre>
     *
     * @param string $type 出力タイプ
     * @param string $text 本文
     *
     * @return None
     * @access public
     */
    public function write($file, $level, $message)
    {
        if ($this->enable === false) {
            return false;
        }

        $level = strtoupper($level);

        if (!isset($this->levels[$level]) or ($this->levels[$level] > $this->threshold)) {
            return false;
        }

        $filepath = $this->log_path.'/' . $file . '_'.date('Ymd').'.log';

        $handle = @fopen($filepath, 'a');
        if (!$handle) {
            return false;
        }

        $line = sprintf("[%s][%s] %s\n", date($this->date_format), $level, $message);

        flock($handle, LOCK_EX);
        fwrite($handle, $line);
        flock($handle, LOCK_UN);

        fclose($handle);

        return true;
    }
}

