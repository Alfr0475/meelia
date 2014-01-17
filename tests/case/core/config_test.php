<?php

use meelia\core\Config;

require_once ME_CORE_DIR . '/config.class.php';

class ConfigTest extends PHPUnit_Framework_TestCase
{
    function test_load(){
        if(!extension_loaded('runkit')){
            $this->markTestSkipped('disabled runkit');
        }

        $config_dir_backup = ME_APP_CONFIG_DIR;
        runkit_constant_redefine('ME_APP_CONFIG_DIR', ME_CORE_TEST_DIR . '/data/app/config');

        $this->assertTrue(Config::load());

        runkit_constant_redefine('ME_APP_CONFIG_DIR', $config_dir_backup);
    }

    function test_get(){
        if(!extension_loaded('runkit')){
            $this->markTestSkipped('disabled runkit');
        }

        $config_dir_backup = ME_APP_CONFIG_DIR;
        runkit_constant_redefine('ME_APP_CONFIG_DIR', ME_CORE_TEST_DIR . '/data/app/config');

        Config::load(true);

        $this->assertEquals('hoge', Config::get('test_data1'));
        $this->assertEquals('moge', Config::get('test_data2'));
        $this->assertNull(Config::get('test_data3'));
        $this->assertNull(Config::get('data1'));
        $this->assertNull(Config::get('data2'));

        runkit_constant_redefine('ME_APP_CONFIG_DIR', $config_dir_backup);
    }

    function test_get_all(){
        if(!extension_loaded('runkit')){
            $this->markTestSkipped('disabled runkit');
        }

        $config_dir_backup = ME_APP_CONFIG_DIR;
        runkit_constant_redefine('ME_APP_CONFIG_DIR', ME_CORE_TEST_DIR . '/data/app/config');

        Config::load(true);

        $all_config = Config::get();

        $this->assertTrue(array_key_exists('test_data1', $all_config));
        $this->assertEquals('hoge', $all_config['test_data1']);
        $this->assertTrue(array_key_exists('test_data2', $all_config));
        $this->assertEquals('moge', $all_config['test_data2']);

        runkit_constant_redefine('ME_APP_CONFIG_DIR', $config_dir_backup);
    }

    /**
     * @depends test_get
     */
    function test_set(){
        ConfigTestChild::resetConfig();

        Config::set('test1', 'hoge');
        Config::set('test2', 'moge');
        Config::set('test3', array('hoge', 'moge'));

        $this->assertEquals('hoge', Config::get('test1'));
        $this->assertEquals('moge', Config::get('test2'));
        $this->assertEquals(array('hoge', 'moge'), Config::get('test3'));

        ConfigTestChild::resetConfig();
    }
}

class ConfigTestChild extends Config
{
    public static function resetConfig(){
        self::$config_array = array();
    }
}
