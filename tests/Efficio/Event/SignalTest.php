<?php

namespace Efficio\Tests\Event;

use Efficio\Event\Event;
use Efficio\Event\Signal;
use Efficio\Tests\Mocks\Event\Signaler;
use PHPUnit_Framework_TestCase;
use StdClass;

require_once './tests/mocks/Signaler.php';

class SignalTest extends PHPUnit_Framework_TestCase
{
    public $signaler;

    public function setUp()
    {
        $this->signaler = new Signaler;
    }

    public function tearDown()
    {
        Signaler::resetGlobalHandlers();
    }

    public function testClassesStartOutWithNoGlobalHandlers()
    {
        $this->assertEquals([], Signaler::getGlobalHandlers());
    }

    public function testClassesStartOutWithNoInstanceHandlers()
    {
        $this->assertEquals([], $this->signaler->getInstanceHandlers());
    }

    public function testAddingInstanceListeners()
    {
        $this->signaler->on(Event::PRE . '.' . __FUNCTION__, function(Event $e) {
            // ...
        });

        $this->assertEquals(1, count($this->signaler->getInstanceHandlers()));
    }

    public function testAddingGlobalListeners()
    {
        Signaler::listen(Event::PRE . '.' . __FUNCTION__, function(Event $e) {
            // ...
        });

        $this->assertEquals(1, count($this->signaler->getGlobalHandlers()));
    }

    public function testHandlerGenerator()
    {
        $key = 'key';
        $function = __FUNCTION__;
        $action = function(Event $ev) { return 1; };
        $handler = Signaler::publicGenHandler($key . '.' . $function, $action);
        $this->assertEquals($key, $handler->getKey(), 'comparing key');
        $this->assertEquals($function, $handler->getFunction(), 'comparing function');
        $this->assertEquals($action, $handler->getAction(), 'comparing action');
        $this->assertEquals(get_class($this->signaler), $handler->getClass(), 'comparing class');
    }

    public function testSingleInstanceHandlerIsTriggered()
    {
        $called = 0;

        $this->signaler->on('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->sendSignal();

        $this->assertEquals(1, $called);
    }

    public function testMultipleInstanceHandlersAreTriggered()
    {
        $called = 0;

        $this->signaler->on('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->on('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->sendSignal();

        $this->assertEquals(2, $called);
    }

    public function testSingleGlobalHandlerIsTriggered()
    {
        $called = 0;

        Signaler::listen('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->sendSignal();

        $this->assertEquals(1, $called);
    }

    public function testMultipleGlobalHandlersAreTriggered()
    {
        $called = 0;

        Signaler::listen('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        Signaler::listen('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->sendSignal();

        $this->assertEquals(2, $called);
    }

    public function testAMixOfInstanceAndGlobalHandlersAreTriggered()
    {
        $called = 0;

        Signaler::listen('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        Signaler::listen('post.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->on('pre.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->on('post.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->sendSignal();

        $this->assertEquals(4, $called);
    }

    public function testHandlersOfMismatchingKeysAreNotTriggered()
    {
        $called = 0;

        $this->signaler->on('000000000.sendsignal', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->sendSignal();

        $this->assertEquals(0, $called);
    }
    public function testHandlersOfMismatchingFunctionsAreNotTriggered()
    {
        $called = 0;

        $this->signaler->on('pre.000000000', function(Event $ev) use(& $called) {
            $called++;
        });

        $this->signaler->sendSignal();

        $this->assertEquals(0, $called);
    }
}
