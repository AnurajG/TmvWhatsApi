<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class IqPropsResultListener extends AbstractListener
{
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('received.node.iq', [$this, 'onReceivedNode']);
    }

    /**
     * @param EventInterface $e
     */
    public function onReceivedNode(EventInterface $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        /** @var Client $client */
        $client = $e->getTarget();

        if ($node->getAttribute('type') != 'result' || !$node->hasChild('props')) {
            return;
        }

        $props = $node->getChild('props');

        $properties = [];

        foreach ($props->getChildren() as $child) {
            $properties[$child->getAttribute('name')] = $child->getAttribute('value');
        }

        $params = [
            'version' => $props->getAttribute('version'),
            'properties' => $properties
        ];

        $client->getEventManager()->trigger('onGetServerPropertiesResult', $client, $params);
    }
}
