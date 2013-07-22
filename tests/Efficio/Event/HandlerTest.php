<?php

namespace Efficio\Tests\Event;

use Efficio\Event\Event;
use Efficio\Event\Handler;
use Efficio\Tests\Mocks\Event\ClassTest;
use Efficio\Tests\Mocks\Event\ClassClassTest;
use Efficio\Tests\Mocks\Event\ClassClassClassTest;
use Efficio\Tests\Mocks\Event\ClassTraitTest;
use Efficio\Tests\Mocks\Event\ClassClassTraitTest;
use Efficio\Tests\Mocks\Event\ClassInterfaceTest;
use Efficio\Tests\Mocks\Event\ClassClassInterfaceTest;
use PHPUnit_Framework_TestCase;

require_once 'tests/mocks/classes.php';

class HandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Handler
     */
    public $handler;

    public function setUp()
    {
        $this->handler = new Handler;
    }

    public function testKeyGetterAndSetter()
    {
        $this->handler->setKey(Event::PRE);
        $this->assertEquals(Event::PRE, $this->handler->getKey());
    }

    public function testFunctionGetterAndSetter()
    {
        $this->handler->setFunction(__FUNCTION__);
        $this->assertEquals(__FUNCTION__, $this->handler->getFunction());
    }

    public function testClassGetterAndSetter()
    {
        $this->handler->setClass(__CLASS__);
        $this->assertEquals(__CLASS__, $this->handler->getClass());
    }

    public function testActionGetterAndSetter()
    {
        $action = function() { echo ''; };
        $this->handler->setAction($action);
        $this->assertEquals($action, $this->handler->getAction());
    }

    public function testHandlerChecker()
    {
        $this->handler->setKey(Event::PRE);
        $this->handler->setClass(__CLASS__);
        $this->handler->setFunction(__FUNCTION__);

        $this->assertTrue($this->handler->handles(Event::PRE, __CLASS__, __FUNCTION__));
        $this->assertFalse($this->handler->handles(Event::PRE . 1, __CLASS__, __FUNCTION__), 'Invalid key');
        $this->assertFalse($this->handler->handles(Event::PRE, __CLASS__ . 1, __FUNCTION__), 'Invalid class');
        $this->assertFalse($this->handler->handles(Event::PRE, __CLASS__, __FUNCTION__ . 1), 'Invalid function');
    }

    public function testHandlerIsCaseInsensitiveToKeyClassAndFunctionNames()
    {
        $this->handler->setKey(Event::PRE);
        $this->handler->setClass(__CLASS__);
        $this->handler->setFunction(__FUNCTION__);

        $this->assertTrue($this->handler->handles(
            strtoupper(Event::PRE),
            strtolower(__CLASS__),
            strtolower(__FUNCTION__)
        ));
    }

    public function testTrigger()
    {
        $ev = new Event;
        $ev->setData(1, 2);
        $action = function(Event $ev) {
            $data = $ev->getData();
            return [ $data[1], $data[0] ];
        };
        $this->handler->setAction($action);

        $this->assertEquals([2, 1], $this->handler->trigger($ev));
    }

    public function testHandlerRecognicesBaseClasses()
    {
        $handler = new Handler;
        $handler->setKey(Event::PRE);
        $handler->setFunction(__FUNCTION__);
        $handler->setClass('Efficio\Tests\Mocks\Event\ClassTest');
        $this->assertTrue($handler->handles(
            Event::PRE, 'Efficio\Tests\Mocks\Event\ClassTest', __FUNCTION__));
    }

    public function testHandlerRecognicesExtendedClasses()
    {
        $handler = new Handler;
        $handler->setKey(Event::PRE);
        $handler->setFunction(__FUNCTION__);
        $handler->setClass('Efficio\Tests\Mocks\Event\ClassTest');
        $this->assertTrue($handler->handles(
            Event::PRE, 'Efficio\Tests\Mocks\Event\ClassClassTest', __FUNCTION__));
    }

    public function testHandlerRecognicesExtendedClassesOfClasses()
    {
        $handler = new Handler;
        $handler->setKey(Event::PRE);
        $handler->setFunction(__FUNCTION__);
        $handler->setClass('Efficio\Tests\Mocks\Event\ClassTest');
        $this->assertTrue($handler->handles(
            Event::PRE, 'Efficio\Tests\Mocks\Event\ClassClassClassTest', __FUNCTION__));
    }

    public function testHandlerRecognicesBaseTraits()
    {
        $handler = new Handler;
        $handler->setKey(Event::PRE);
        $handler->setFunction(__FUNCTION__);
        $handler->setClass('Efficio\Tests\Mocks\Event\TraitTest');
        $this->assertTrue($handler->handles(
            Event::PRE, 'Efficio\Tests\Mocks\Event\ClassTraitTest', __FUNCTION__));
    }

    public function testHandlerRecognicesExtendedTraits()
    {
        $handler = new Handler;
        $handler->setKey(Event::PRE);
        $handler->setFunction(__FUNCTION__);
        $handler->setClass('Efficio\Tests\Mocks\Event\TraitTest');
        $this->assertTrue($handler->handles(
            Event::PRE, 'Efficio\Tests\Mocks\Event\ClassClassTraitTest', __FUNCTION__));
    }

    public function testHandlerRecognicesImplementedInterfaces()
    {
        $handler = new Handler;
        $handler->setKey(Event::PRE);
        $handler->setFunction(__FUNCTION__);
        $handler->setClass('Efficio\Tests\Mocks\Event\InterfaceTest');
        $this->assertTrue($handler->handles(
            Event::PRE, 'Efficio\Tests\Mocks\Event\ClassInterfaceTest', __FUNCTION__));
    }

    public function testHandlerRecognicesImplementedExtendedInterfaces()
    {
        $handler = new Handler;
        $handler->setKey(Event::PRE);
        $handler->setFunction(__FUNCTION__);
        $handler->setClass('Efficio\Tests\Mocks\Event\InterfaceTest');
        $this->assertTrue($handler->handles(
            Event::PRE, 'Efficio\Tests\Mocks\Event\ClassClassInterfaceTest', __FUNCTION__));
    }
}
