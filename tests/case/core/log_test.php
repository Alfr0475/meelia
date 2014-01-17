<?php

use meelia\core\Config;
use meelia\core\CoreLog;

require_once ME_CORE_DIR . '/log.class.php';
require_once ME_CORE_TEST_DIR . '/vendor/vfsStream/vfsStream.php';

class LogTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers CoreLog::write
     */
    function test_write(){
        if(!extension_loaded('runkit')){
            $this->markTestSkipped('disabled runkit');
        }

        // @codeCoverageIgnoreStart
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory(''));
        $vfs = vfsStreamWrapper::getRoot();

        $log_dir_backup = ME_APP_LOG_DIR;
        runkit_constant_redefine('ME_APP_LOG_DIR', vfsStream::url($vfs->getName()));

        $log_file_name = 'log_'.date('Ymd').'.log';

        $test_file1 = vfsStream::newFile($log_file_name);
        $vfs->addChild($test_file1);
        // @codeCoverageIgnoreEnd

        Config::set('log_logging', true);
        Config::set('log_threshold', 4);
        $log = new CoreLog();
        $log->write('log', 'debug', 'hogehoge');
        Config::set('log_logging', false);
        Config::set('log_threshold', null);
        $this->assertTrue(filesize(ME_APP_LOG_DIR . '/' . $log_file_name) > 0);

        runkit_constant_redefine('ME_APP_LOG_DIR', $log_dir_backup);
    }
}
