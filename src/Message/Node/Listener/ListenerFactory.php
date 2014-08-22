<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use InvalidArgumentException;
use RuntimeException;

class ListenerFactory
{
    /**
     * @param  string                   $name
     * @param  Client                   $client
     * @return ListenerInterface
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function factory($name, Client $client)
    {
        $name = ucfirst($name);
        $className = __NAMESPACE__.'\\'.$name.'Listener';

        if (!class_exists($className)) {
            throw new InvalidArgumentException('Missing listener class.');
        }

        $instance = new $className();
        if (!$instance instanceof ListenerInterface) {
            throw new RuntimeException(sprintf("Listener '%s' is not valid", $name));
        }
        $instance->setClient($client);

        return $instance;
    }
}
