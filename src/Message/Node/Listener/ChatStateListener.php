<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class ChatStateListener extends AbstractListener
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
        $this->listeners[] = $events->attach('received.node.chatstate', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        if ($this->isNodeFromMyNumber($node) || $this->isNodeFromGroup($node)) {
            return;
        }

        if ($node->hasChild('composing')) {
            $this->getClient()->getEventManager()->trigger('onMessageComposing',
                $this,
                array(
                    'node' => $node,
                    'from' => Identity::parseJID($node->getAttribute('from')),
                    'id' => $node->getAttribute('id'),
                    'timestamp' => (int) $node->getAttribute('t')
                )
            );
        } elseif ($node->hasChild('paused')) {
            $this->getClient()->getEventManager()->trigger('onMessagePaused',
                $this,
                array(
                    'node' => $node,
                    'from' => Identity::parseJID($node->getAttribute('from')),
                    'id' => $node->getAttribute('id'),
                    'timestamp' => (int) $node->getAttribute('t')
                )
            );
        }
    }
}
