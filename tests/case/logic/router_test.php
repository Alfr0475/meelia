<?php

require_once ME_CORE_DIR . '/loader.class.php';
require_once ME_CORE_LOGIC_DIR . '/router.class.php';

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function test_reccurenceParseUri()
    {
        if(!extension_loaded('runkit')){
            $this->markTestSkipped('disabled runkit');
        }

        $controller_dir_backup = ME_APP_CONTROLLER_DIR;
        runkit_constant_redefine('ME_APP_CONTROLLER_DIR', ME_CORE_TEST_DIR . '/data/app/controller');

        $router = new LogicRouter();
        $uri    =& Loader::loadLogic('uri');

        $ref_uri_class  = new ReflectionClass($uri);
        $ref_uri_method = $ref_uri_class->getMethod('explodeSegments');
        $ref_uri_method->setAccessible(true);

        $ref_uri_var_uri_string = $ref_uri_class->getProperty('uri_string');
        $ref_uri_var_segments   = $ref_uri_class->getProperty('segments');

        $ref_uri_var_uri_string->setAccessible(true);
        $ref_uri_var_segments->setAccessible(true);

        $ref_router_class  = new ReflectionClass($router);
        $ref_router_method = $ref_router_class->getMethod('recurrenceParseUri');
        $ref_router_method->setAccessible(true);

        $ref_router_var_directory = $ref_router_class->getProperty('directory');
        $ref_router_var_class     = $ref_router_class->getProperty('class');
        $ref_router_var_method    = $ref_router_class->getProperty('method');
        $ref_router_var_args      = $ref_router_class->getProperty('args');

        $ref_router_var_directory->setAccessible(true);
        $ref_router_var_class->setAccessible(true);
        $ref_router_var_method->setAccessible(true);
        $ref_router_var_args->setAccessible(true);


        $ref_uri_var_uri_string->setValue($uri, 'router_test1/test/hoge/moge');
        $ref_uri_method->invoke($uri);
        $ref_router_method->invokeArgs($router, array($uri->getSegmentsAll()));

        $this->assertEquals('', $router->getDirectory());
        $this->assertEquals('router_test1', $router->getClass());
        $this->assertEquals('test', $router->getMethod());
        $this->assertEquals(array('hoge', 'moge'), $router->getArgs());

        $ref_router_var_directory->setValue($router, '');
        $ref_router_var_class->setValue($router, '');
        $ref_router_var_method->setValue($router, 'index');
        $ref_router_var_args->setValue($router, array());
        $ref_uri_var_uri_string->setValue($uri, '');
        $ref_uri_var_segments->setValue($uri, array());


        $ref_uri_var_uri_string->setValue($uri, 'routertest1/router_test2/test/hoge/moge');
        $ref_uri_method->invoke($uri);
        $ref_router_method->invokeArgs($router, array($uri->getSegmentsAll()));

        $this->assertEquals('routertest1', $router->getDirectory());
        $this->assertEquals('router_test2', $router->getClass());
        $this->assertEquals('test', $router->getMethod());
        $this->assertEquals(array('hoge', 'moge'), $router->getArgs());

        $ref_router_var_directory->setValue($router, '');
        $ref_router_var_class->setValue($router, '');
        $ref_router_var_method->setValue($router, 'index');
        $ref_router_var_args->setValue($router, array());
        $ref_uri_var_uri_string->setValue($uri, '');
        $ref_uri_var_segments->setValue($uri, array());


        $ref_uri_var_uri_string->setValue($uri, 'routertest1/routertest2/router_test3/test/hoge/moge');
        $ref_uri_method->invoke($uri);
        $ref_router_method->invokeArgs($router, array($uri->getSegmentsAll()));

        $this->assertEquals('routertest1/routertest2', $router->getDirectory());
        $this->assertEquals('router_test3', $router->getClass());
        $this->assertEquals('test', $router->getMethod());
        $this->assertEquals(array('hoge', 'moge'), $router->getArgs());

        $ref_router_var_directory->setValue($router, '');
        $ref_router_var_class->setValue($router, '');
        $ref_router_var_method->setValue($router, 'index');
        $ref_router_var_args->setValue($router, array());
        $ref_uri_var_uri_string->setValue($uri, '');
        $ref_uri_var_segments->setValue($uri, array());


        $ref_uri_var_uri_string->setValue($uri, 'routertest1/router_test2');
        $ref_uri_method->invoke($uri);
        $ref_router_method->invokeArgs($router, array($uri->getSegmentsAll()));

        $this->assertEquals('routertest1', $router->getDirectory());
        $this->assertEquals('router_test2', $router->getClass());
        $this->assertEquals('index', $router->getMethod());
        $this->assertEquals(array(), $router->getArgs());

        $ref_router_var_directory->setValue($router, '');
        $ref_router_var_class->setValue($router, '');
        $ref_router_var_method->setValue($router, 'index');
        $ref_router_var_args->setValue($router, array());
        $ref_uri_var_uri_string->setValue($uri, '');
        $ref_uri_var_segments->setValue($uri, array());


        runkit_constant_redefine('ME_APP_CONTROLLER_DIR', $controller_dir_backup);
    }

    public function test_configureDefaultController()
    {
        $router = new LogicRouter();

        $ref_router_class  = new ReflectionClass($router);
        $ref_router_method = $ref_router_class->getMethod('configureDefaultController');
        $ref_router_method->setAccessible(true);

        $ref_router_var_default_controller = $ref_router_class->getProperty('default_controller');
        $ref_router_var_directory          = $ref_router_class->getProperty('directory');
        $ref_router_var_class              = $ref_router_class->getProperty('class');

        $ref_router_var_default_controller->setAccessible(true);
        $ref_router_var_directory->setAccessible(true);
        $ref_router_var_class->setAccessible(true);


        $ref_router_var_default_controller->setValue($router, 'hoge/moge/gere');
        $ref_router_method->invoke($router);
        $this->assertEquals('hoge/moge', $router->getDirectory());
        $this->assertEquals('gere', $router->getClass());
        $ref_router_var_default_controller->setValue($router, '');
        $ref_router_var_directory->setValue($router, '');
        $ref_router_var_class->setValue($router, '');


        $ref_router_var_default_controller->setValue($router, 'hoge/moge');
        $ref_router_method->invoke($router);
        $this->assertEquals('hoge', $router->getDirectory());
        $this->assertEquals('moge', $router->getClass());
        $ref_router_var_default_controller->setValue($router, '');
        $ref_router_var_directory->setValue($router, '');
        $ref_router_var_class->setValue($router, '');


        $ref_router_var_default_controller->setValue($router, 'hoge');
        $ref_router_method->invoke($router);
        $this->assertEquals('', $router->getDirectory());
        $this->assertEquals('hoge', $router->getClass());
        $ref_router_var_default_controller->setValue($router, '');
        $ref_router_var_directory->setValue($router, '');
        $ref_router_var_class->setValue($router, '');
    }
}

