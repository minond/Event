<?php

namespace Efficio\Tests\Event;

use Efficio\Event\Event;
use PHPUnit_Framework_TestCase;

class EventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Event
     */
    public $event;

    public function setUp()
    {
        $this->event = new Event;
    }

    public function testPassingMultipleArgumentToDataSetter()
    {
        $this->event->setData(1, 2, 3);
        $this->assertEquals([1, 2, 3], $this->event->getData());
    }

    public function testPassingASingleArgumentToDataSetter()
    {
        $this->event->setData([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $this->event->getData());
    }
}
