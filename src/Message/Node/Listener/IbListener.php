<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Message\Action\ClearDirty;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use RuntimeException;

class IbListener extends AbstractListener
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
        $this->listeners[] = $events->attach('received.node.ib', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        foreach ($node->getChildren() as $child) {
            switch ($child->getName()) {
                case "dirty":
                    $action = new ClearDirty(array($child->getAttribute("type")));
                    $this->getClient()->send($action);
                    break;

                case "offline":

                    break;

                default:
                    throw new RuntimeException("ib handler for ".$child->getName()." not implemented");
            }
        }
    }
}
