<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Message\Event\ReceivedNodeEvent;
use Tmv\WhatsApi\Message\Listener\AbstractListener;
use Tmv\WhatsApi\Message\Node\Challenge;
use Zend\EventManager\EventManagerInterface;

class ChallengeListener extends AbstractListener
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
        $events->attach('received.node.challenge', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(ReceivedNodeEvent $e)
    {
        /** @var Challenge $node */
        $node = $e->getNode();
        $client = $e->getClient();
        $client->setChallengeData($node->getData());
    }
}
