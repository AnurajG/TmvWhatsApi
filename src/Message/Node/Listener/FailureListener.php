<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Event\LoginFailedEvent;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class FailureListener extends AbstractListener
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
        $this->listeners[] = $events->attach('received.node.failure', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        $client = $this->getClient();

        // triggering public event
        $event = new LoginFailedEvent('onLoginFailed', $this, array('node' => $node));
        $event->setClient($this->getClient());
        $client->getEventManager()->trigger($event);
    }
}
