<?php

namespace Efficio\Tests\Mocks\Event;

use Efficio\Event\Event;
use Efficio\Event\Signal;
use StdClass;

class Signaler
{
    use Signal;

    public function sendSignal($data = null)
    {
        $data = new StdClass;
        $data->data = $data;
        $this->signal(Event::PRE, __FUNCTION__, $data);
        $this->signal(Event::POST, __FUNCTION__, $data);
    }

    public static function resetGlobalHandlers()
    {
        self::$ghandlers = [];
    }

    public static function getGlobalHandlers()
    {
        return self::$ghandlers;
    }

    public function getInstanceHandlers()
    {
        return $this->ihandlers;
    }

    public static function publicGenHandler($key, $action)
    {
        return self::genHandler($key, $action);
    }
}
