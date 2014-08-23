<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\Node;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class IqListener extends AbstractListener
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
        $this->listeners[] = $events->attach('received.node.iq', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');

        if ($this->isPing($node)) {
            $this->sendPong($node);
        }
        switch ($node->getAttribute('type')) {
            case 'error':
                // todo: handle iq error
                break;
            case 'result':
                // todo: handle iq result
                break;
        }
        if ($node->hasChild('sync')) {
            // todo: handle sync result
        }
    }

    /**
     * @param  NodeInterface $node
     * @return bool
     */
    protected function isPing(NodeInterface $node)
    {
        return $node->getAttribute('type') == 'get' && $node->getAttribute('xmlns') == "urn:xmpp:ping";
    }

    /**
     * @param NodeInterface $pingNode
     */
    protected function sendPong(NodeInterface $pingNode)
    {
        $pongNode = new Node();
        $pongNode->setName('iq');
        $pongNode->setAttribute('to', Client::WHATSAPP_SERVER);
        $pongNode->setAttribute('to', $pingNode->getAttribute('id'));
        $pongNode->setAttribute('type', 'result');

        $this->getClient()->sendNode($pongNode);
    }
}
