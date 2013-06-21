<?php

namespace Efficio\Event;

use StdClass;
use Efficio\Event\Event;

/**
 * enables:
 * $this->signal('pre', ...);
 * Class::listen('pre', 'render', function...);
 * $instance->listen('pre', 'render', function...);
 */
trait Signal
{
    /**
     * class event handlers
     * @var Handler[]
     */
    protected static $ghandlers = [];

    /**
     * instance event handlers
     * @var Handler[]
     */
    protected $ihandlers = [];

    /**
     * trigger event
     * @see Event
     * @param string $key
     * @param string $function
     * @param StdClass $data, default = null
     * @return array, array of responses
     */
    protected function signal($key, $function, StdClass $data = null)
    {
        $class = get_called_class();
        $response = [];
        $event = new Event;
        $event->setData($data);

        foreach (array_merge(self::$ghandlers, $this->ihandlers) as $handler) {
            if ($handler->handles($key, $class, $function)) {
                $response[] = $handler->trigger($event);
            }
        }

        return $response;
    }

    /**
     * add an instance event handler
     * @param string $key
     * @param callable $action
     */
    public function on($key, $action)
    {
        $this->ihandlers[] = self::genHandler($key, $action);
    }

    /**
     * add a global event handler
     * @param string $key
     * @param callable $action
     */
    public static function listen($key, $action)
    {
        self::$ghandlers[] = self::genHandler($key, $action);
    }

    /**
     * @param string $key
     * @param callable $action
     * @return Handler
     */
    protected static function genHandler($key, $action)
    {
        list($key, $function) = explode('.', $key, 2);
        $handler = new Handler;
        $handler->setKey($key);
        $handler->setFunction($function);
        $handler->setClass(get_called_class());
        $handler->setAction($action);
        return $handler;
    }
}
