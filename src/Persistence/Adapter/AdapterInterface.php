<?php

namespace Tmv\WhatsApi\Persistence\Adapter;

interface AdapterInterface
{
    /**
     * @param array $config
     * @return $this
     */
    public static function factory(array $config);
    /**
     * @param $data
     * @return $this
     */
    public function set($data);

    /**
     * @return mixed
     */
    public function get();
}
