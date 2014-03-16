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
        $f_ok = strtolower($this->function) === strtolower($function);
        $k_ok = strtolower($this->key) === strtolower($key);
        $c_ok = false;

        if ($f_ok && $k_ok && class_exists($class)) {
            if (strtolower($this->class) === strtolower($class)) {
                // same class
                $c_ok = true;
            } else if (is_subclass_of($class, $this->class)) {
                // extends class and implemented interface
                $c_ok = true;
            } else {
                do {
                    if (in_array($this->class, class_uses($class))) {
                        $c_ok = true;
                        break;
                    }
                } while($class = get_parent_class($class));
            }
        }

        return $f_ok && $k_ok && $c_ok;
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
