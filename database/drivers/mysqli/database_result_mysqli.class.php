<?php
/**
 * Meelia Database Result MySQLi
 *
 * <pre>
 * MySQLへのSQL結果から情報を取得する。
 *
 * PHP versions 5
 * </pre>
 *
 * @category  Database
 * @package   Meelia
 * @author    Seiki Koga <seikikoga@gamania.com>
 * @copyright 2011-2012 Gamania Degital Entertainment Co.,Ltd.
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   SVN: $Rev: 22 $
 * @access    public
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/database/drivers/mysql/database_result_mysql.class.php $
 */

/**
 * DatabaseResultMysqli
 *
 * <pre>
 * SQLの結果から情報を取得するメソッド群の実装。
 * </pre>
 *
 * @category Database
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/database/drivers/mysql/database_result_mysql.class.php $
 */
class DatabaseResultMysqli extends DatabaseResult
{
    /**
     * SQL結果からレコード数を取得
     *
     * <pre>
     * SQL結果からレコード数を取得する。
     * </pre>
     *
     * @return integer レコード数
     * @access protected
     */
    protected function countRow()
    {
        return $this->res_id->now_rows;
    }


    /**
     * SQL結果からカラム数を取得
     *
     * <pre>
     * SQL結果からカラム数を取得する。
     * </pre>
     *
     * @return integer カラム数
     * @access protected
     */
    protected function countField()
    {
        return $this->res_id->field_count;
    }


    /**
     * SQL結果を解放する
     *
     * <pre>
     * SQL結果データを解放する。
     * </pre>
     *
     * @return None
     * @access public
     */
    public function free()
    {
        $this->res_id->free_result();
        $this->res_id = false;
    }


    /**
     * SQL結果からカラム名を配列で取得
     *
     * <pre>
     * SQL結果からカラム名を配列で取得する。
     * </pre>
     *
     * @return array カラム名配列
     * @access protected
     */
    protected function fieldArray()
    {
        $field_data = array();
        while ($field = $this->res_id->fetch_field()) {
            $field_data[] = $field->name;
        }

        return $field_data;
    }


    /**
     * SQL結果からカラム情報をオブジェクトで取得
     *
     * <pre>
     * SQL結果からカラム情報をオブジェクトで取得する。
     *
     * array(
     *     StdClass(
     *         name => カラム名,
     *         type => カラムの型,
     *         default => デフォルト値,
     *         max_length => max_length,
     *         primary_key => 主キー名
     *     )
     * );
     * </pre>
     *
     * @return array カラム情報配列
     * @access protected
     */
    protected function fieldObject()
    {
        $field_data = array();

        while ($field = $this->res_id->fetch_field()) {
            $class              = new StdClass();
            $class->name        = $field->name;
            $class->type        = $field->type;
            $class->default     = $field->default;
            $class->max_length  = $field->max_length;
            $class->primary_key = $field->primary_key;

            $field_data[] = $class;
        }

        return $field_data;
    }


    /**
     * SQL結果のインデックス値を設定
     *
     * <pre>
     * SQL結果のインデックス値を設定
     * </pre>
     *
     * @return bool
     * @access protected
     */
    protected function seek($seek = 0)
    {
        return $this->res_id->data_seek($seek);
    }


    /**
     * SQL結果から１レコードずつ配列で取得
     *
     * <pre>
     * SQL結果から１レコードずつを配列で取得する。
     * </pre>
     *
     * @return array レコードデータ
     * @access protected
     */
    protected function fetchAssoc()
    {
        return $this->res_id->fetch_assoc();
    }


    /**
     * SQL結果から１レコードずつオブジェクトで取得
     *
     * <pre>
     * SQL結果から１レコードずつをオブジェクトで取得する。
     * </pre>
     *
     * @return object レコードデータ
     * @access protected
     */
    protected function fetchObject()
    {
        return $this->res_id->fetch_object();
    }
}

