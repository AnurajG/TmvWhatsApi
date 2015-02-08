<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\Node;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class IqPingListener extends AbstractListener
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

        if ($this->isPing($node)) {
            $this->sendPong($client, $node);
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
     * @param Client        $client
     * @param NodeInterface $pingNode
     */
    protected function sendPong(Client $client, NodeInterface $pingNode)
    {
        $pongNode = new Node();
        $pongNode->setName('iq');
        $pongNode->setAttribute('to', Client::WHATSAPP_SERVER);
        $pongNode->setAttribute('id', $pingNode->getAttribute('id'));
        $pongNode->setAttribute('type', 'result');

        $client->sendNode($pongNode);
    }
}
