<?php

namespace Efficio\Event;

use Efficio\Event\Event;

/**
 * event handler
 */
class Handler
{
    /**
     * @see Event
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $function;

    /**
     * @var string
     */
    private $class;

    /**
     * @var callable
     */
    private $action;

    /**
     * key setter
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * key getter
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * function setter
     * @param string $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * function getter
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * class setter
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * class getter
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * action setter
     * @param callable $action
     */
    public function setAction(callable $action)
    {
        $this->action = $action;
    }

    /**
     * action getter
     * @return callable
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return boolean
     */
    public function handles($key, $class, $function)
    {
        return strtolower($this->key) === strtolower($key) &&
            strtolower($this->class) === strtolower($class) &&
            strtolower($this->function) === strtolower($function);
    }

    /**
     * trigger the action
     * @param Event $ev
     * @return mixed
     */
    public function trigger(Event $ev)
    {
        return call_user_func($this->action, $ev);
    }
}
