<?php

require_once ME_CORE_DATABASE_DIR . '/database_driver.class.php';
require_once ME_CORE_DATABASE_DIR . '/drivers/mysqli/database_driver_mysqli.class.php';

class DatabaseDriverMysqliTest extends PHPUnit_Framework_TestCase
{
    private $driver = null;
    private $conn   = null;

    function setUp(){
        $con = mysqli_connect(TEST_DB_HOSTNAME, TEST_DB_USERNAME, TEST_DB_PASSWORD);
        if(!mysqli_select_db($con, TEST_DB_DATABASE)){
            $this->markTestSkipped('not found '.TEST_DB_DATABASE);
        }

        $params = array(
            'hostname' => TEST_DB_HOSTNAME,
            'username' => TEST_DB_USERNAME,
            'password' => TEST_DB_PASSWORD,
            'database' => TEST_DB_DATABASE,
            'charset'  => 'utf8',
            'collate'  => 'utf8_general_ci',
        );

        $this->driver = new DatabaseDriverMysqliDatabaseDriverMysqliTest($params);
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

class DatabaseDriverMysqliDatabaseDriverMysqliTest extends DatabaseDriverMysqli
{
    public function setConId($con_id){
        $this->con_id = $con_id;
    }
}
