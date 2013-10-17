<?php
/**
 * Meelia Database Result
 *
 * <pre>
 * データベースリザルトのベース。
 * 各データベースリザルトは継承して作成する。
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
 * @link      $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/database/database_result.class.php $
 */

/**
 * DatabaseResult
 *
 * <pre>
 * SQL発行結果情報を扱うインターフェース。
 * リザルトクラスで各データベースの機能を実装する。
 * </pre>
 *
 * @category Database
 * @package  Meelia
 * @author   Seiki Koga <seikikoga@gamania.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @access   public
 * @link     $HeadURL: svn+ssh://127.167.180.69/var/svn/develop/PSS/meelia/trunk/database/database_result.class.php $
 */
abstract class DatabaseResult
{
    protected $con_id = false;
    protected $res_id = false;

    protected $result_object = array();
    protected $result_array  = array();
    protected $current_row   = 0;


    /**
     * 接続リソースの設定。
     *
     * <pre>
     * データベース接続リソースを設定する。
     * </pre>
     *
     * @param resource $con_id 接続リソース
     *
     * @return None
     * @access public
     */
    public function setConId($con_id)
    {
        $this->con_id = $con_id;
    }


    /**
     * 結果リソースの設定。
     *
     * <pre>
     * データベース結果リソースを設定する。
     * </pre>
     *
     * @param resource $res_id 結果リソース
     *
     * @return None
     * @access public
     */
    public function setResId($res_id)
    {
        $this->res_id = $res_id;
    }


    /**
     * 結果の取得
     *
     * <pre>
     * SQLの結果データを取得する。
     * 引数を指定する事で配列かオブジェクトかの選択が可能。
     * </pre>
     *
     * @param string $type 返り値型の選択
     *
     * @return mixed SQLの結果データ
     * @access public
     */
    public function result($type = 'object')
    {
        if ($type == 'object') {
            return $this->resultObject();
        } else {
            return $this->resultArray();
        }
    }


    /**
     * 結果をオブジェクトで取得
     *
     * <pre>
     * SQLの結果データをオブジェクトで取得する。
     * </pre>
     *
     * @return object SQLの結果データ
     * @access private
     */
    private function resultObject()
    {
        if (count($this->result_object) > 0) {
            return $this->result_object;
        }

        if ($this->res_id === false || $this->count() == 0) {
            return array();
        }

        $this->seek(0);
        while ($row = $this->fetchObject()) {
            $this->result_object[] = $row;
        }

        return $this->result_object;
    }


    /**
     * 結果を配列で取得
     *
     * <pre>
     * SQLの結果データを配列で取得する。
     * </pre>
     *
     * @return array SQLの結果データ
     * @access private
     */
    private function resultArray()
    {
        if (count($this->result_array) > 0) {
            return $this->result_array;
        }

        if ($this->res_id === false || $this->count() == 0) {
            return array();
        }

        $this->seek(0);
        while ($row = $this->fetchAssoc()) {
            $this->result_array[] = $row;
        }

        return $this->result_array;
    }


    /**
     * SQLの結果レコードを取得
     *
     * <pre>
     * SQLの結果レコードデータを取得する。
     * $seekでインデックス値を、$typeで返り値の型を指定出来る。
     * </pre>
     *
     * @param integer $seek 結果データインデックス
     * @param string  $type 返り値型の選択
     *
     * @return mixed SQLの結果レコードデータ
     * @access public
     */
    public function row($seek = 0, $type = 'object')
    {
        if ($type == 'object') {
            return $this->rowObject($seek);
        } else {
            return $this->rowArray($seek);
        }
    }


    /**
     * SQLの結果レコードをオブジェクトで取得
     *
     * <pre>
     * SQLの結果レコードデータをオブジェクトで取得する。
     * $seekでインデックス値を指定出来る。
     * </pre>
     *
     * @param integer $seek 結果データインデックス
     *
     * @return object SQLの結果レコードデータ
     * @access private
     */
    private function rowObject($seek = 0)
    {
        $result = $this->resultObject();

        if (count($result) == 0) {
            return $result;
        }

        if ($seek != $this->current_row && isset($result[$seek])) {
            $this->current_row = $seek;
        }

        return $result[$this->current_row];
    }


    /**
     * SQLの結果レコードを配列で取得
     *
     * <pre>
     * SQLの結果レコードデータを配列で取得する。
     * $seekでインデックス値を指定出来る。
     * </pre>
     *
     * @param integer $seek 結果データインデックス
     *
     * @return array SQLの結果レコードデータ
     * @access private
     */
    private function rowArray($seek)
    {
        $result = $this->resultArray();

        if (count($result) == 0) {
            return $result;
        }

        if ($seek != $this->current_row && isset($result[$seek])) {
            $this->current_row = $seek;
        }

        return $result[$this->current_row];
    }


    /**
     * SQLの結果をカウントする
     *
     * <pre>
     * SQLの結果をカウントする。
     * $typeでレコード数かカラム数を指定できる。
     * </pre>
     *
     * @param string $type カウント対象指定
     *
     * @return integer カウント結果
     * @access public
     */
    public function count($type = 'row')
    {
        if ($type == 'row') {
            return $this->countRow();
        } else {
            return $this->countField();
        }
    }


    /**
     * SQL結果の先頭データを取得する
     *
     * <pre>
     * SQL結果の先頭データを取得する。
     * 引数を指定する事で配列かオブジェクトかの選択が可能。
     * </pre>
     *
     * @param string $type 返り値型の選択
     *
     * @return mixed 結果データ
     * @access public
     */
    public function first($type = 'object')
    {
        $result = $this->result($type);

        if (count($result) == 0) {
            return $result;
        }

        return $result[0];
    }


    /**
     * SQL結果の末尾データを取得する
     *
     * <pre>
     * SQL結果の末尾データを取得する。
     * 引数を指定する事で配列かオブジェクトかの選択が可能。
     * </pre>
     *
     * @param string $type 返り値型の選択
     *
     * @return mixed 結果データ
     * @access public
     */
    public function last($type = 'object')
    {
        $result = $this->result($type);

        if (count($result) == 0) {
            return $result;
        }

        return $result[count($result) - 1];
    }


    /**
     * SQL結果の次のインデックスデータを取得する
     *
     * <pre>
     * SQL結果の次のインデックスデータを取得する。
     * 引数を指定する事で配列かオブジェクトかの選択が可能。
     * </pre>
     *
     * @param string $type 返り値型の選択
     *
     * @return mixed 結果データ
     * @access public
     */
    public function next($type = 'object')
    {
        $result = $this->result($type);

        if (count($result) == 0) {
            return $result;
        }

        if (isset($result[$this->current_row + 1])) {
            $this->current_row++;
        }

        return $result[$this->current_row];
    }


    /**
     * SQL結果の前のインデックスデータを取得する
     *
     * <pre>
     * SQL結果の前のインデックスデータを取得する。
     * 引数を指定する事で配列かオブジェクトかの選択が可能。
     * </pre>
     *
     * @param string $type 返り値型の選択
     *
     * @return mixed 結果データ
     * @access public
     */
    public function previous($type = 'object')
    {
        $result = $this->result($type);

        if (count($result) == 0) {
            return $result;
        }

        if (isset($result[$this->current_row - 1])) {
            $this->current_row--;
        }

        return $result[$this->current_row];
    }


    /**
     * SQL結果からカラム情報を取得
     *
     * <pre>
     * SQL結果からカラムの情報を取得する。
     * object指定ならカラムの情報をオブジェクトで取得。
     * その他の指定ならカラム名の配列を取得する。
     * </pre>
     *
     * @param string $type 取得データの選択
     *
     * @return array 結果データ
     * @access public
     */
    public function field($type = 'object')
    {
        if ($type == 'object') {
            return $this->fieldObject();
        } else {
            return $this->fieldArray();
        }
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
    abstract public function free();


    /**
     * SQL結果からレコード数を取得する
     *
     * <pre>
     * SQL結果からレコード数を取得する。
     * </pre>
     *
     * @return integer レコード数
     * @access protected
     */
    abstract protected function countRow();


    /**
     * SQL結果からカラム数を取得する
     *
     * <pre>
     * SQL結果からカラム数を取得する。
     * </pre>
     *
     * @return integer カラム数
     * @access protected
     */
    abstract protected function countField();


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
    abstract protected function seek($seek = 0);


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
    abstract protected function fieldArray();


    /**
     * SQL結果からカラム情報をオブジェクトで取得
     *
     * <pre>
     * SQL結果からカラム情報をオブジェクトで取得
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
    abstract protected function fieldObject();


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
    abstract protected function fetchAssoc();


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
    abstract protected function fetchObject();
}

