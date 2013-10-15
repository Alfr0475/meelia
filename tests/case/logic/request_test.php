<?php

class RequestTest extends PHPUnit_Framework_TestCase
{
    function test_get(){
        $request = Loader::loadLogic('request');

        $request_backup = $_REQUEST;
        $_REQUEST       = array();

        $_REQUEST['hoge']     = 'moge';
        $_REQUEST['hogehoge'] = 'mogemoge';

        $this->assertEquals('moge', $request->get('hoge'));
        $this->assertEquals('mogemoge', $request->get('hogehoge'));
        $this->assertNull($request->get('moge'));

        $_REQUEST = $request_backup;
    }

    function test_get_all(){
        $request = Loader::loadLogic('request');

        $request_backup = $_REQUEST;
        $_REQUEST       = array();

        $_REQUEST['hoge']     = 'moge';
        $_REQUEST['hogehoge'] = 'mogemoge';

        $this->assertCount(2, $request->get());
        $this->assertEquals(array('hoge' => 'moge', 'hogehoge' => 'mogemoge'), $request->get());
    }
}

