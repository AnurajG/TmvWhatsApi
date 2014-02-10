<?php

namespace Tmv\WhatsApi\Message\Listener;

use Tmv\WhatsApi\Message\Event\ReceivedNodeEvent;
use Tmv\WhatsApi\Message\Node\Success;
use Zend\EventManager\EventManagerInterface;

class SuccessListener extends AbstractListener
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
        $events->attach('received.node.success', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(ReceivedNodeEvent $e)
    {
        /** @var Success $node */
        $node = $e->getNode();
        $client = $e->getClient();

        $client->setConnected(true);
        $challengeData = $node->getData();
        file_put_contents($client->getChallengeDataFilepath(), $challengeData);
        $client->getNodeWriter()->setKey($client->getOutputKey());
    }
}
