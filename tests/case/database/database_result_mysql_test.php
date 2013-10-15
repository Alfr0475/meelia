<?php

require_once ME_CORE_DATABASE_DIR . '/database_result.class.php';
require_once ME_CORE_DATABASE_DIR . '/drivers/mysql/database_result_mysql.class.php';

class DatabaseResultMysqlTest extends PHPUnit_Framework_TestCase
{
    private $con = false;

    function setUp(){
        $this->con = mysql_connect(TEST_DB_HOSTNAME, TEST_DB_USERNAME, TEST_DB_PASSWORD, true);

        if(!mysql_select_db(TEST_DB_DATABASE, $this->con)){
            $this->markTestSkipped('not found '.TEST_DB_DATABASE);
        }
    }

    function test_count(){
        $res = mysql_query('select * from result_test');

        $res_obj = new DatabaseResultMysql();
        $res_obj->setConId($this->con);
        $res_obj->setResId($res);

        $this->assertEquals(3, $res_obj->count());
        $this->assertEquals(3, $res_obj->count('field'));
    }

    function test_result_object(){
        $res = mysql_query('select * from result_test');

        $res_obj = new DatabaseResultMysql();
        $res_obj->setConId($this->con);
        $res_obj->setResId($res);

        $result = $res_obj->result();
        $this->assertEquals('test1', $result[0]->name);
        $this->assertEquals('test2', $result[1]->name);
    }

    function test_result_array(){
        $res = mysql_query('select * from result_test');

        $res_obj = new DatabaseResultMysql();
        $res_obj->setConId($this->con);
        $res_obj->setResId($res);

        $result = $res_obj->result('array');
        $this->assertEquals('test1', $result[0]['name']);
        $this->assertEquals('test2', $result[1]['name']);
    }

    function test_row_object(){
        $res = mysql_query('select * from result_test');

        $res_obj = new DatabaseResultMysql();
        $res_obj->setConId($this->con);
        $res_obj->setResId($res);

        $row = $res_obj->row();
        $this->assertEquals('test1', $row->name);

        $row = $res_obj->row(1);
        $this->assertEquals('test2', $row->name);
    }

    function test_row_array(){
        $res = mysql_query('select * from result_test');

        $res_obj = new DatabaseResultMysql();
        $res_obj->setConId($this->con);
        $res_obj->setResId($res);

        $row = $res_obj->row(0, 'array');
        $this->assertEquals('test1', $row['name']);

        $row = $res_obj->row(1, 'array');
        $this->assertEquals('test2', $row['name']);
    }

    function test_seek(){
        $res = mysql_query('select * from result_test');

        $res_obj = new DatabaseResultMysql();
        $res_obj->setConId($this->con);
        $res_obj->setResId($res);

        $row = $res_obj->row();
        $this->assertEquals('test1', $row->name);

        $row = $res_obj->next();
        $this->assertEquals('test2', $row->name);

        $row = $res_obj->next();
        $this->assertEquals('test3', $row->name);

        $row = $res_obj->previous();
        $this->assertEquals('test2', $row->name);

        $row = $res_obj->previous();
        $this->assertEquals('test1', $row->name);

        $row = $res_obj->last();
        $this->assertEquals('test3', $row->name);

        $row = $res_obj->first();
        $this->assertEquals('test1', $row->name);
    }
}

