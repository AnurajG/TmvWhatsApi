<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class IqPricingResultListener extends AbstractListener
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

        if ($node->getAttribute('type') != 'result' || !$node->hasChild('pricing')) {
            return;
        }

        $pricing = $node->getChild('pricing');

        $expiration = $pricing->getAttribute('expiration');

        if ($expiration) {
            $datetime = new \DateTime();
            $datetime->setTimestamp((int)$pricing->getAttribute('expiration'));
            $expiration = $datetime;
        }

        $params = [
            'price' => $pricing->getAttribute('price'),
            'cost' => $pricing->getAttribute('cost'),
            'currency' => $pricing->getAttribute('currency'),
            'expiration' => $expiration,
        ];
        $client->getEventManager()->trigger('onGetServerPricingResult', $client, $params);
    }
}
