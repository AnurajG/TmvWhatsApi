<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Event\LoginSuccessEvent;
use Tmv\WhatsApi\Message\Action\Presence;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
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
        $this->listeners[] = $events->attach('received.node.success', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        $client = $this->getClient();

        $client->setConnected(true);
        $client->writeChallengeData($node->getData());
        $client->getConnection()->getNodeWriter()->setKey($client->getConnection()->getOutputKey());

        $client->send(new Presence($client->getIdentity()->getNickname()));

        // triggering public event
        $event = new LoginSuccessEvent('onConnected', $client, array('node' => $node));
        $event->setClient($this->getClient());
        $client->getEventManager()->trigger($event);
    }
}
