<?php

require_once ME_CORE_DATABASE_DIR . '/database_driver.class.php';
require_once ME_CORE_DATABASE_DIR . '/drivers/mysql/database_driver_mysql.class.php';

class DatabaseDriverMysqlTest extends PHPUnit_Extensions_Database_TestCase
{
    private static $pdo = null;
    private $conn = null;

    private $driver;

    function setUp(){
        if (version_compare(PHP_VERSION, '5.4', '>=')) {
            $this->markTestSkipped('This test operates below by php version 5.3.');
        }

        $params = array(
            'hostname' => TEST_DB_HOSTNAME,
            'username' => TEST_DB_USERNAME,
            'password' => TEST_DB_PASSWORD,
            'database' => TEST_DB_DATABASE,
            'charset'  => 'utf8',
            'collate'  => 'utf8_general_ci',
        );

        $con = mysql_connect(TEST_DB_HOSTNAME, TEST_DB_USERNAME, TEST_DB_PASSWORD, true);
        if(!mysql_select_db(TEST_DB_DATABASE, $con)){
            $this->markTestSkipped('not found '.TEST_DB_DATABASE);
        }

        $this->driver = new TestDatabaseDriverMysql($params);
    }

    function getConnection(){
        if($this->conn === null){
            if(self::$pdo == null){
                self::$pdo = new PDO('mysql:host='.TEST_DB_HOSTNAME, TEST_DB_USERNAME, TEST_DB_PASSWORD);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, TEST_DB_DATABASE);
        }

        return $this->conn;
    }

    function getDataSet(){
        // まだデータセットは使わないので適当なクラスをnewしとく
        return new PHPUnit_Extensions_Database_DataSet_DefaultDataSet();
    }

    function test_connect(){
        $con = $this->driver->connect();

        $this->assertNotNull($con);
    }

    function test_escape(){
        $con = $this->driver->connect();
        $this->driver->setConId($con);

        $this->assertEquals("\'test\'", $this->driver->escape("'test'"));
        $this->assertEquals('\"test\"', $this->driver->escape('"test"'));
        $this->assertEquals('\\\n', $this->driver->escape('\n'));
        $this->assertEquals('\\\r', $this->driver->escape('\r'));
        $this->assertEquals('\\\\', $this->driver->escape('\\'));
    }
}

class TestDatabaseDriverMysql extends DatabaseDriverMysql
{
    public function setConId($con_id){
        $this->con_id = $con_id;
    }
}
