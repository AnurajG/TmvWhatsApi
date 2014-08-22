<?php

namespace Tmv\WhatsApi\Event;

use Tmv\WhatsApi\Client;
use Zend\EventManager\Event;

abstract class AbstractPublicEvent extends Event
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param  \Tmv\WhatsApi\Client $client
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return \Tmv\WhatsApi\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
