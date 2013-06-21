<?php

namespace Efficio\Event;

/**
 * holds standard event keys
 */
class Event
{
    const PRE = 'pre';
    const POST = 'post';

    /**
     * @var mixed
     */
    private $data;

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        if (func_num_args() !== 1) {
            $this->data = func_get_args();
        } else {
            $this->data = $data;
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
