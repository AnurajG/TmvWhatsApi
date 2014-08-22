<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Zend\EventManager\ListenerAggregateInterface;

interface ListenerInterface extends ListenerAggregateInterface
{
    /**
     * @param  Client $client
     * @return $this
     */
    public function setClient(Client $client);

    /**
     * @return Client
     */
    public function getClient();
}
