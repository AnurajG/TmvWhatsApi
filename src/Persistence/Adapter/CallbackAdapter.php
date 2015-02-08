<?php

namespace Tmv\WhatsApi\Persistence\Adapter;

class CallbackAdapter implements AdapterInterface
{
    /**
     * @var callable
     */
    protected $callbackSet;
    /**
     * @var callable
     */
    protected $callbackGet;

    /**
     * @param array $config
     * @return $this
     */
    public static function factory(array $config)
    {
        if (!isset($config['callback_set'])) {
            throw new \InvalidArgumentException(sprintf("Missing configuration key: %s", 'callback_set'));
        }
        if (!isset($config['callback_get'])) {
            throw new \InvalidArgumentException(sprintf("Missing configuration key: %s", 'callback_get'));
        }
        return new self($config['callback_set'], $config['callback_get']);
    }

    /**
     * @param callable $setCallback
     * @param callable $getCallback
     */
    public function __construct(callable $setCallback, callable $getCallback)
    {
        $this->setCallbackSet($setCallback);
        $this->setCallbackGet($getCallback);
    }

    /**
     * @return callable
     */
    public function getCallbackSet()
    {
        return $this->callbackSet;
    }

    /**
     * @param callable $setCallback
     * @return $this
     */
    public function setCallbackSet(callable $setCallback)
    {
        $this->callbackSet = $setCallback;
        return $this;
    }

    /**
     * @return callable
     */
    public function getCallbackGet()
    {
        return $this->callbackGet;
    }

    /**
     * @param callable $getCallback
     * @return $this
     */
    public function setCallbackGet(callable $getCallback)
    {
        $this->callbackGet = $getCallback;
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function set($data)
    {
        call_user_func($this->getCallbackSet(), $data);

        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return call_user_func($this->getCallbackGet());
    }
}
