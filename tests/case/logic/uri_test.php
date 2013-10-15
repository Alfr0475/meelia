<?php

require_once ME_CORE_LOGIC_DIR . '/uri.class.php';

class UriTest extends PHPUnit_Framework_TestCase
{
    private $uri;

    public function setUp(){
        $this->uri = new LogicUri();
    }

    public function test_fetchUriString(){
        $_SERVER['REQUEST_URI'] = '/path/to/index.php/hoge/moge?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('hoge/moge', $this->uri->getUriString());
        $this->assertEquals('hoge=moge', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array('hoge' => 'moge'), $_GET);

        $_SERVER['REQUEST_URI'] = '/path/to/hoge/moge?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('hoge/moge', $this->uri->getUriString());
        $this->assertEquals('hoge=moge', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array('hoge' => 'moge'), $_GET);

        $_SERVER['REQUEST_URI'] = '/path/to/index.php?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('/', $this->uri->getUriString());
        $this->assertEquals('hoge=moge', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array('hoge' => 'moge'), $_GET);

        $_SERVER['REQUEST_URI'] = '/path/to?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('/', $this->uri->getUriString());
        $this->assertEquals('hoge=moge', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array('hoge' => 'moge'), $_GET);

        $_SERVER['REQUEST_URI'] = '/path/to/index.php/hoge/moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('hoge/moge', $this->uri->getUriString());
        $this->assertEquals('', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array(), $_GET);

        $_SERVER['REQUEST_URI'] = '/path/to/hoge/moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('hoge/moge', $this->uri->getUriString());
        $this->assertEquals('', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array(), $_GET);

        $_SERVER['REQUEST_URI'] = '/path/to/index.php';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('/', $this->uri->getUriString());
        $this->assertEquals('', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array(), $_GET);

        $_SERVER['REQUEST_URI'] = '/path/to';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('/', $this->uri->getUriString());
        $this->assertEquals('', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array(), $_GET);

        $_SERVER['REQUEST_URI'] = '/index.php';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('/', $this->uri->getUriString());
        $this->assertEquals('', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array(), $_GET);

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $this->uri->fetchUriString();
        $this->assertEquals('/', $this->uri->getUriString());
        $this->assertEquals('', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array(), $_GET);
    }

    public function test_setEnvironmentVariable(){
        $ref_class  = new ReflectionClass($this->uri);
        $ref_method = $ref_class->getMethod('setEnvironmentVariable');
        $ref_method->setAccessible(true);

        $ref_method->invokeArgs($this->uri, array(array('', 'hoge=moge')));
        $this->assertEquals('hoge=moge', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array('hoge' => 'moge'), $_GET);

        $ref_method->invokeArgs($this->uri, array(array('', '')));
        $this->assertEquals('', $_SERVER['QUERY_STRING']);
        $this->assertEquals(array(), $_GET);
    }

    public function test_parseArgument(){
        $ref_class  = new ReflectionClass($this->uri);
        $ref_method = $ref_class->getMethod('parseArgument');
        $ref_method->setAccessible(true);

        $_SERVER['REQUEST_URI'] = '/path/to/index.php/hoge/moge?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('/hoge/moge?hoge=moge', $ref_method->invoke($this->uri));

        $_SERVER['REQUEST_URI'] = '/path/to/hoge/moge?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('/hoge/moge?hoge=moge', $ref_method->invoke($this->uri));

        $_SERVER['REQUEST_URI'] = '/path/to/index.php?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('?hoge=moge', $ref_method->invoke($this->uri));

        $_SERVER['REQUEST_URI'] = '/path/to?hoge=moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('?hoge=moge', $ref_method->invoke($this->uri));

        $_SERVER['REQUEST_URI'] = '/path/to/index.php/hoge/moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('/hoge/moge', $ref_method->invoke($this->uri));

        $_SERVER['REQUEST_URI'] = '/path/to/hoge/moge';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('/hoge/moge', $ref_method->invoke($this->uri));

        $_SERVER['REQUEST_URI'] = '/path/to/index.php';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('', $ref_method->invoke($this->uri));

        $_SERVER['REQUEST_URI'] = '/path/to';
        $_SERVER['SCRIPT_NAME'] = '/path/to/index.php';
        $this->assertEquals('', $ref_method->invoke($this->uri));
    }

    public function test_fetchUriSuffix()
    {
        $ref_class  = new ReflectionClass($this->uri);
        $ref_method = $ref_class->getMethod('fetchUriSuffix');
        $ref_method->setAccessible(true);

        $ref_uri_string = $ref_class->getProperty('uri_string');
        $ref_uri_string->setAccessible(true);

        $ref_uri_suffix = $ref_class->getProperty('uri_suffix');
        $ref_uri_suffix->setAccessible(true);

        $ref_uri_string->setValue($this->uri, 'hoge/moge');
        $ref_method->invoke($this->uri);
        $this->assertEquals('html', $ref_uri_suffix->getValue($this->uri));
        $ref_uri_suffix->setValue($this->uri, 'html');

        $ref_uri_string->setValue($this->uri, 'hoge/moge.json');
        $ref_method->invoke($this->uri);
        $this->assertEquals('json', $ref_uri_suffix->getValue($this->uri));
        $ref_uri_suffix->setValue($this->uri, 'html');

        $ref_uri_string->setValue($this->uri, 'hoge/moge.JSON');
        $ref_method->invoke($this->uri);
        $this->assertEquals('json', $ref_uri_suffix->getValue($this->uri));
        $ref_uri_suffix->setValue($this->uri, 'html');

        $ref_uri_string->setValue($this->uri, '');
        $ref_method->invoke($this->uri);
        $this->assertEquals('html', $ref_uri_suffix->getValue($this->uri));
        $ref_uri_suffix->setValue($this->uri, 'html');

        $ref_uri_string->setValue($this->uri, '.json');
        $ref_method->invoke($this->uri);
        $this->assertEquals('json', $ref_uri_suffix->getValue($this->uri));
        $ref_uri_suffix->setValue($this->uri, 'html');
    }

    public function test_removeUriSuffix()
    {
        $ref_class  = new ReflectionClass($this->uri);
        $ref_method = $ref_class->getMethod('removeUriSuffix');
        $ref_method->setAccessible(true);

        $ref_uri_string = $ref_class->getProperty('uri_string');
        $ref_uri_string->setAccessible(true);

        $ref_uri_suffix = $ref_class->getProperty('uri_suffix');
        $ref_uri_suffix->setAccessible(true);

        $ref_uri_string->setValue($this->uri, 'hoge/moge.html');
        $ref_uri_suffix->setValue($this->uri, 'html');
        $ref_method->invoke($this->uri);
        $this->assertEquals('hoge/moge', $ref_uri_string->getValue($this->uri));

        $ref_uri_string->setValue($this->uri, 'hoge/moge');
        $ref_uri_suffix->setValue($this->uri, 'html');
        $ref_method->invoke($this->uri);
        $this->assertEquals('hoge/moge', $ref_uri_string->getValue($this->uri));
    }

    public function test_explodeSegments()
    {
        $ref_class  = new ReflectionClass($this->uri);
        $ref_method = $ref_class->getMethod('explodeSegments');
        $ref_method->setAccessible(true);

        $ref_uri_string = $ref_class->getProperty('uri_string');
        $ref_uri_string->setAccessible(true);

        $ref_segments = $ref_class->getProperty('segments');
        $ref_segments->setAccessible(true);

        $ref_uri_string->setValue($this->uri, 'hoge/moge/gere');
        $ref_method->invoke($this->uri);
        $this->assertEquals(array('hoge', 'moge', 'gere'), $ref_segments->getValue($this->uri));
        $ref_segments->setValue($this->uri, array());

        $ref_uri_string->setValue($this->uri, 'hoge//moge');
        $ref_method->invoke($this->uri);
        $this->assertEquals(array('hoge', 'moge'), $ref_segments->getValue($this->uri));
        $ref_segments->setValue($this->uri, array());

        $ref_uri_string->setValue($this->uri, 'hoge/');
        $ref_method->invoke($this->uri);
        $this->assertEquals(array('hoge'), $ref_segments->getValue($this->uri));
        $ref_segments->setValue($this->uri, array());

        $ref_uri_string->setValue($this->uri, '/hoge');
        $ref_method->invoke($this->uri);
        $this->assertEquals(array('hoge'), $ref_segments->getValue($this->uri));
        $ref_segments->setValue($this->uri, array());
    }

    public function test_getSegments()
    {
        $ref_class  = new ReflectionClass($this->uri);
        $ref_method = $ref_class->getMethod('explodeSegments');
        $ref_method->setAccessible(true);

        $ref_uri_string = $ref_class->getProperty('uri_string');
        $ref_uri_string->setAccessible(true);

        $ref_segments = $ref_class->getProperty('segments');
        $ref_segments->setAccessible(true);

        $ref_uri_string->setValue($this->uri, 'hoge/moge/gere');
        $ref_method->invoke($this->uri);
        $this->assertEquals('hoge', $this->uri->getSegments(0));
        $this->assertEquals('moge', $this->uri->getSegments(1));
        $this->assertEquals('gere', $this->uri->getSegments(2));
        $this->assertNull($this->uri->getSegments(3));
        $ref_segments->setValue($this->uri, array());
    }

    public function test_getSegmentsAll()
    {
        $ref_class  = new ReflectionClass($this->uri);
        $ref_method = $ref_class->getMethod('explodeSegments');
        $ref_method->setAccessible(true);

        $ref_uri_string = $ref_class->getProperty('uri_string');
        $ref_uri_string->setAccessible(true);

        $ref_segments = $ref_class->getProperty('segments');
        $ref_segments->setAccessible(true);

        $ref_uri_string->setValue($this->uri, 'hoge/moge/gere');
        $ref_method->invoke($this->uri);
        $this->assertEquals(array('hoge', 'moge', 'gere'), $this->uri->getSegmentsAll());
        $ref_segments->setValue($this->uri, array());
    }
}

