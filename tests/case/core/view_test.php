<?php

require_once ME_CORE_DIR . '/view.class.php';
require_once ME_CORE_VIEW_DIR . '/view_html.class.php';

class ViewTest extends PHPUnit_Framework_TestCase
{
    function test_getViewFile(){
        $_SERVER['REQUEST_METHOD'] = 'execute';
        $controller = new ViewTestController('test');
        $controller->initialize('test', 'hoge');

        $view = new ViewTestView($controller);

        $this->assertEquals(ME_APP_VIEW_DIR . '/test/hoge.php', $view->testMethod());
        $this->assertEquals(ME_APP_VIEW_DIR . '/test/hoge.php', $view->testMethod('hoge'));
        $this->assertEquals(ME_APP_VIEW_DIR . '/test/moge.php', $view->testMethod('moge'));
        $this->assertEquals(ME_APP_VIEW_DIR . '/test/hoge/moge.php', $view->testMethod('hoge/moge'));
        $this->assertEquals(ME_APP_VIEW_DIR . '/hoge.php', $view->testMethod('/hoge'));
        $this->assertEquals(ME_APP_VIEW_DIR . '/hoge/moge.php', $view->testMethod('/hoge/moge'));
    }
}

class ViewTestController extends Controller
{
    public function setName($name)
    {
        $this->name = $name;
    }
}

class ViewTestView extends ViewHtml
{
    public function testMethod($action_path = null)
    {
        return $this->getViewFile($action_path);
    }
}
