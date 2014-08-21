<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;

interface ListenerInterface
{
    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client);

    /**
     * @return Client
     */
    public function getClient();
}
 