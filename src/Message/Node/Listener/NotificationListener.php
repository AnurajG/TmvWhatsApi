<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Message\Node\Node;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use RuntimeException;
use Tmv\WhatsApi\Client;

class NotificationListener extends AbstractListener
{
    /**
     * @var array|callable[]
     */
    protected $handlers;

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
        $this->listeners[] = $events->attach('received.node.notification', [$this, 'onReceivedNode']);
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

        // @todo: handle notifications public events

        $type = $node->getAttribute("type");
        $handlers = $this->getHandlers();
        if (!isset($handlers[$type])) {
            throw new RuntimeException(sprintf("Notification '%s' not implemented", $type));
        }

        call_user_func($handlers[$type], $e);

        $this->sendNotificationAck($client, $node);
    }

    /**
     * @return array|\callable[]
     */
    protected function getHandlers()
    {
        if (!$this->handlers) {
            $this->registerNotificationHandlers();
        }
        return $this->handlers;
    }

    /**
     * @param array|\callable[] $handlers
     * @return $this
     */
    protected function setHandlers($handlers)
    {
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * @todo: handle notifications. Need a refactoring
     * @return $this
     */
    protected function registerNotificationHandlers()
    {
        $this->handlers = [
            'status' => function () {},
            'picture' => function () {},
            'contacts' => function () {},
            'participant' => function () {},
            'subject' => function () {},
            'encrypt' => function () {},
            'w:gp2' => function () {},
            'account' => function () {},
            'features' => function () {},
        ];

        return $this;
    }

    /**
     * @param Client        $client
     * @param NodeInterface $node
     */
    protected function sendNotificationAck(Client $client, NodeInterface $node)
    {
        $ackNode = new Node();
        $ackNode->setName('ack');

        if ($node->hasAttribute("to")) {
            $ackNode->setAttribute('from', $node->getAttribute("to"));
        }
        if ($node->hasAttribute("participant")) {
            $ackNode->setAttribute('participant', $node->getAttribute("participant"));
        }

        $ackNode->setAttribute('to', $node->getAttribute("from"));
        $ackNode->setAttribute('class', $node->getName());
        $ackNode->setAttribute('id', $node->getAttribute("id"));
        $ackNode->setAttribute('type', $node->getAttribute("type"));
        $client->sendNode($ackNode);
    }
}
