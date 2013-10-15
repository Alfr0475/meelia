<?php

require_once ME_CORE_DIR . '/util.class.php';

class UtilTest extends PHPUnit_Framework_TestCase
{
    function test_camelizeUcc(){
        $this->assertEquals('Test', Util::camelizeUcc('test'));
        $this->assertEquals('TestTest', Util::camelizeUcc('testTest'));
        $this->assertEquals('Testtest', Util::camelizeUcc('testtest'));
        $this->assertEquals('TestTest', Util::camelizeUcc('test_test'));
        $this->assertEquals('TestTest', Util::camelizeUcc('test__test'));
        $this->assertEquals('Test', Util::camelizeUcc('_test'));
        $this->assertEquals('Test', Util::camelizeUcc('test_'));
        $this->assertEquals('Test', Util::camelizeUcc('_test_'));
    }

    function test_camelizeLcc(){
        $this->assertEquals('test', Util::camelizeLcc('test'));
        $this->assertEquals('testTest', Util::camelizeLcc('testTest'));
        $this->assertEquals('testTest', Util::camelizeLcc('test_test'));
        $this->assertEquals('testTest', Util::camelizeLcc('test__test'));
        $this->assertEquals('test', Util::camelizeLcc('_test'));
        $this->assertEquals('test', Util::camelizeLcc('test_'));
        $this->assertEquals('test', Util::camelizeLcc('_test_'));
    }

    function test_toSnakeCase(){
        $this->assertEquals('camel_case', Util::toSnakeCase('CamelCase'));
        $this->assertEquals('camel_case', Util::toSnakeCase('camelCase'));
        $this->assertEquals('camelcase', Util::toSnakeCase('camelcase'));
        $this->assertEquals('camelcase', Util::toSnakeCase('Camelcase'));
        $this->assertEquals('_camel_case', Util::toSnakeCase('_camelCase'));
    }
}
