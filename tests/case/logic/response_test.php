<?php

class ResponseTest extends PHPUnit_Framework_TestCase
{
    public function test_setHeader()
    {
        $response = Loader::loadLogic('response');

        $ref_response_class  = new ReflectionClass($response);

        $ref_response_var_headers = $ref_response_class->getProperty('headers');
        $ref_response_var_headers->setAccessible(true);

        $this->assertEquals(array(), $ref_response_var_headers->getValue($response));
        $response->setHeader('test1');
        $this->assertEquals(array(array('test1', true)), $ref_response_var_headers->getValue($response));
        $response->setHeader('test2', false);
        $this->assertEquals(array(array('test1', true), array('test2', false)), $ref_response_var_headers->getValue($response));

        $ref_response_var_headers->setValue($response, array());
    }

    public function test_setContentType()
    {
        $response = Loader::loadLogic('response');

        $ref_response_class  = new ReflectionClass($response);

        $ref_response_var_headers = $ref_response_class->getProperty('headers');
        $ref_response_var_headers->setAccessible(true);

        $response->setContentType('text/html');
        $headers = $ref_response_var_headers->getValue($response);
        $this->assertEquals('Content-Type: text/html', $headers[0][0]);
        $this->assertTrue($headers[0][1]);

        $response->setContentType('text/json');
        $headers = $ref_response_var_headers->getValue($response);
        $this->assertEquals('Content-Type: text/json', $headers[1][0]);
        $this->assertTrue($headers[1][1]);

        $ref_response_var_headers->setValue($response, array());
    }
}
