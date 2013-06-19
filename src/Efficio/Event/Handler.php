<?php

namespace Efficio\Event;

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
        return $this->key === $key &&
            $this->class === $class &&
            $this->function === $function;
    }

    /**
     * trigger the action
     * @param array $args, default = array()
     * @return mixed
     */
    public function trigger(array $args = array())
    {
        return call_user_func_array($this->action, $args);
    }
}
