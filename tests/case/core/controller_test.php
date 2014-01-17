<?php

use meelia\core\Controller;
use meelia\core\View;
use meelia\core\Config;

require_once ME_CORE_DIR . '/controller.class.php';
require_once ME_CORE_DIR . '/view.class.php';

class ControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller = null;

    function setUp(){
        $_SERVER['REQUEST_METHOD'] = 'execute';
        Config::set('app_view_mapping', array('html' => 'ControllerTestView'));
        $this->controller = new ControllerTestController();
        $this->controller->initialize('test', 'test');
    }

    function test_definendum_method(){
        $this->assertTrue(method_exists('meelia\core\Controller', 'beforeProcess'));
        $this->assertTrue(method_exists('meelia\core\Controller', 'afterProcess'));
    }

    function test_existAction(){
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($this->controller->existAction());
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue($this->controller->existAction());
        $_SERVER['REQUEST_METHOD'] = 'HOGE';
        $this->assertFalse($this->controller->existAction());
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertFalse($this->controller->existAction());
    }

    function test_reservedAction(){
        $this->assertTrue($this->controller->reservedAction('add'));
        $this->assertTrue($this->controller->reservedAction('view'));
        $this->assertFalse($this->controller->reservedAction('beforeProcess'));
        $this->assertFalse($this->controller->reservedAction('afterProcess'));
    }

    function test_set(){
        $view = $this->controller->getView();

        $this->controller->set(array('test' => 'hoge'));
        $this->assertEquals(array('test' => 'hoge'), $view->getVars());
        $view->resetVars();

        $this->controller->set('test', 'hoge');
        $this->assertEquals(array('test' => 'hoge'), $view->getVars());
        $view->resetVars();

        $this->controller->set(array('test' => 'hoge', 'test2' => 'moge'));
        $this->assertEquals(array('test' => 'hoge', 'test2' => 'moge'), $view->getVars());
        $view->resetVars();

        $this->controller->set('test', 'hoge');
        $this->controller->set('test');
        $this->assertEquals(array('test' => null), $view->getVars());
        $view->resetVars();
    }
}

class ControllerTestController extends Controller
{
    public function executeGet()
    {
    }

    public function executePost()
    {
    }
}

class ControllerTestView extends View
{
    public function render($action_path = null)
    {
    }

    public function fetch($action_path = null)
    {
    }

    public function resetVars(){
        $this->vars = array();
    }
}
