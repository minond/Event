<?php

namespace Efficio\Tests\Event;

use Efficio\Event;
use Efficio\Event\Handler;
use PHPUnit_Framework_TestCase;

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

    public function testTrigger()
    {
        $action = function($a, $b) {
            return [ $b, $a ];
        };
        $this->handler->setAction($action);

        $this->assertEquals([2, 1], $this->handler->trigger([1, 2]));
    }
}
