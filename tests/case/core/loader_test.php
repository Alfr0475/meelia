<?php

require_once ME_CORE_DIR . '/loader.class.php';

class LoaderTest extends PHPUnit_Framework_TestCase
{
    function test_loadController(){
        if(!extension_loaded('runkit')){
            $this->markTestSkipped('disabled runkit');
        }

        $controller_dir_backup = ME_APP_CONTROLLER_DIR;
        runkit_constant_redefine('ME_APP_CONTROLLER_DIR', ME_CORE_TEST_DIR . '/data/app/controller');

        $loadcontroller  = Loader::loadController('loader_test');
        $loadcontroller2 = Loader::loadController('loader_test');
        $loadcontroller3 = Loader::loadController('loader_test', true);

        $this->assertTrue(class_exists('ControllerLoaderTest'));
        $this->assertEquals('ControllerLoaderTest', get_class($loadcontroller));
        $this->assertTrue($loadcontroller === $loadcontroller2);
        $this->assertFalse($loadcontroller === $loadcontroller3);

        runkit_constant_redefine('ME_APP_CONTROLLER_DIR', $controller_dir_backup);
    }

    function test_loadModel(){
        if(!extension_loaded('runkit')){
            $this->markTestSkipped('disabled runkit');
        }

        $model_dir_backup = ME_APP_MODEL_DIR;
        runkit_constant_redefine('ME_APP_MODEL_DIR', ME_CORE_TEST_DIR . '/data/app/model');

        $loadmodel  = Loader::loadModel('loader_test');
        $loadmodel2 = Loader::loadModel('loader_test');
        $loadmodel3 = Loader::loadModel('loader_test', true);

        $this->assertTrue(class_exists('ModelLoaderTest'));
        $this->assertEquals('ModelLoaderTest', get_class($loadmodel));
        $this->assertTrue($loadmodel === $loadmodel2);
        $this->assertFalse($loadmodel === $loadmodel3);

        runkit_constant_redefine('ME_APP_MODEL_DIR', $model_dir_backup);
    }
}
