<?php

namespace Tmv\WhatsApi\Persistence\Adapter;

class MemoryAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $storage;

    /**
     * @param array $config
     * @return $this
     */
    public static function factory(array $config)
    {
        return new self();
    }

    /**
     * @param $data
     * @return $this
     */
    public function set($data)
    {
        $this->storage = $data;

        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->storage;
    }
}
