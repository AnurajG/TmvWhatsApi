<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

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
        $this->listeners[] = $events->attach('received.node.void', array($this, 'onReceivedNodeVoid'));
        $this->listeners[] = $events->attach('received.node.receipt', array($this, 'onReceivedNodeReceipt'));
    }

    public function onReceivedNodeVoid(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        if ($node->getAttribute("class") != "message") {
            return;
        }

        $params = array(
            'id' => $node->getAttribute('id'),
            'node' => $node,
        );
        $this->getClient()->getEventManager()->trigger('onReceiptServer', $this, $params);
    }

    public function onReceivedNodeReceipt(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        $params = array(
            'id' => $node->getAttribute('id'),
            'node' => $node,
        );
        $this->getClient()->getEventManager()->trigger('onReceiptClient', $this, $params);
    }
}
