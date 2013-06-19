<?php

namespace Efficio\Event;

use StdClass;
use Efficio\Event;

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
     * @param StdClass $data, default = null
     * @param string $function, default = callee
     * @return array, array of responses
     */
    protected function signal($key, StdClass $data = null, $function = null)
    {
        $class = get_called_class();
        $response = [];

        if (!$function) {
            $callee = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
            $function = $callee['function'];
        }

        foreach (array_merge(self::$ghandlers, $this->ihandlers) as $handler) {
            if ($handler->handles($key, $class, $function)) {
                $response[] = $handler->trigger([ $data ]);
            }
        }

        return $response;
    }

    /**
     * add an event handler
     * @param string $key
     * @param string $function
     * @param callable $action
     */
    public function listen($key, $function, $action)
    {
        $handler = new Handler;
        $handler->setKey($key);
        $handler->setFunction($function);
        $handler->setClass(get_called_class());
        $handler->setAction($action);

        if (isset($this)) {
            $this->ihandlers[] = $handler;
        } else {
            self::$ghandlers[] = $handler;
        }
    }
}
