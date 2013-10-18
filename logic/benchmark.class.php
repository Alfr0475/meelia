<?php
/**
 * Meelia Benchmark
 *
 * <pre>
 * ベンチマーク
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Logic
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 52 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/benchmark.class.php $
 */

/**
 * LogicBenchmark
 *
 * <pre>
 * ベンチマーク系のクラス。
 * 実行時間の測定ポイントを作ったりする。
 * </pre>
 *
 * @category Logic
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/logic/benchmark.class.php $
 */
class LogicBenchmark
{
    protected $marker = array();

    /**
     * 測定ポイント設定
     *
     * <pre>
     * 測定ポイントを設定する。
     * 呼ばれた時点でのマイクロ秒を記録する。
     * </pre>
     *
     * @param string $name 測定ポイント名
     *
     * @return None
     * @access public
     */
    public function mark($name)
    {
        $this->marker[$name] = microtime();
    }

    /**
     * 測定ポイント取得
     *
     * <pre>
     * 保持されている測定ポイントデータを全て取得する。
     * </pre>
     *
     * @return array 測定ポイント配列
     * @access public
     */
    public function getAllMark()
    {
        return $this->marker;
    }

    /**
     * 時間計算
     *
     * <pre>
     * 測定ポイント同士の時間差分を計算する。
     * エンドポイントが設定されていない場合は
     * 現時点での時間と計算する。
     * </pre>
     *
     * @param string  $point1   開始ポイント
     * @param string  $point2   終了ポイント
     * @param integer $decimals 小数点の桁数
     *
     * @return None
     * @access public
     */
    public function elapsedTime($point1, $point2, $decimals = 4)
    {
        if (!isset($this->marker[$point1])) {
            return '';
        }

        if (!isset($this->marker[$point2])) {
            $this->marker[$point2] = microtime();
        }

        list($sm, $ss) = explode(' ', $this->marker[$point1]);
        list($em, $es) = explode(' ', $this->marker[$point2]);

        return number_format(($em + $es) - ($sm + $ss), $decimals);
    }
}

