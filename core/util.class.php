<?php
/**
 * Meelia Util
 *
 * <pre>
 * 便利機能群。
 * 様々なとこで使うメソッドはここに記述。
 * 大きくなってきたら用途毎にクラスを作る。
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Core
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 46 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/util.class.php $
 */

/**
 * Util
 *
 * <pre>
 * 便利機能群。
 * 様々なとこで使うメソッドはこのクラスに書く。
 * インスタンスは作らずにstaticで扱う。
 * </pre>
 *
 * @category Core
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/core/util.class.php $
 */
class Util
{
    /**
     * CamelCase化
     *
     * <pre>
     * 文字列をCamelCase形式にする。
     * アッパーキャメルケース(UCC)
     * </pre>
     *
     * @param string $string 対象文字列
     *
     * @return string CamelCase化された文字列
     * @access public
     */
    public static function camelizeUcc($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    /**
     * camelCase化
     *
     * <pre>
     * 文字列をcamelCase形式にする。
     * ローワーキャメルケース(LCC)
     * </pre>
     *
     * @param string $string 対象文字列
     *
     * @return string camelCase化された文字列
     * @access public
     */
    public static function camelizeLcc($string)
    {
        return lcfirst(self::camelizeUcc($string));
    }

    /**
     * snake_case変換
     *
     * <pre>
     * 渡された文字列をsnake_caseに変換する。
     * </pre>
     *
     * @param string $string 文字列
     *
     * @return string snake_case化された文字列
     * @access public
     */
    public static function toSnakeCase($string)
    {
        return preg_replace('/[A-Z]/e', "'_'.strtolower('$0')", lcfirst($string));
    }

    /**
     * クラス/アクション指定からURLの絶対パス生成
     *
     * <pre>
     * クラス/アクションを指定してURLを生成する。
     * mod_rewriteを使ってる時に使う感じ。
     * </pre>
     *
     * @param string $relative_path 相対パス
     *
     * @return string 絶対パス
     * @access public
     */
    public static function absoluteUrl($relative_path)
    {
        return Config::get('app_base_url') . $relative_path;
    }

    /**
     * URLリダイレクト
     *
     * <pre>
     * URLを指定してリダイレクトを行う。
     * 何かが出力される前に呼ぶ事。
     * </pre>
     *
     * @param string $url リダイレクト先URL
     *
     * @return None
     * @access public
     */
    public static function redirect($url)
    {
        header('Location: '.$url);
        exit();
    }
}

