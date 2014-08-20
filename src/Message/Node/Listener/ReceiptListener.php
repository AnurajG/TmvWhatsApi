<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Event\ReceivedNodeEvent;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class ReceiptListener extends AbstractListener
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
        $this->listeners[] = $events->attach(array('received.node.receipt', 'received.node.void'),
            array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(ReceivedNodeEvent $e)
    {

    }
}
