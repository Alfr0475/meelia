<?php

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
        $this->assertTrue(method_exists('Controller', 'beforeProcess'));
        $this->assertTrue(method_exists('Controller', 'afterProcess'));
    }

    function test_existAction(){
        $this->assertTrue($this->controller->existAction('add'));
        $this->assertFalse($this->controller->existAction('add_test'));
        $this->assertFalse($this->controller->existAction('view'));
        $this->assertFalse($this->controller->existAction('hoge'));
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
    public function executeAdd()
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
