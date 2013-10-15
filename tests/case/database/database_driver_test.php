<?php

class DatabaseDriverTest extends PHPUnit_Framework_TestCase
{
    private $driver;

    function test_loadResultDriver(){
        $params = array(
            'db_driver' => 'mysql',
            'hostname' => TEST_DB_HOSTNAME,
            'username' => TEST_DB_USERNAME,
            'password' => TEST_DB_PASSWORD,
            'database' => TEST_DB_DATABASE,
            'charset'  => 'utf8',
            'collate'  => 'utf8_general_ci'
        );

        $driver = new DatabaseDriverMysql($params);
        $ref_method = new ReflectionMethod($driver, 'loadResultDriver');
        $ref_method->setAccessible(true);
        $this->assertEquals('DatabaseResultMysql', get_class($ref_method->invoke($driver)));
    }
}

