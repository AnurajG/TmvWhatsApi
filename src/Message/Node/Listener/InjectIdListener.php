<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\MessageIdAwareInterface;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class InjectIdListener extends AbstractListener
{
    /**
     * @var int
     */
    protected $messageCounter = 1;
    /**
     * @var string
     */
    protected $receivedId;

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
        $this->listeners[] = $events->attach('node.send.pre', array($this, 'onSendingNode'));
        $this->listeners[] = $events->attach('node.send.post', array($this, 'onNodeSent'));
        $this->listeners[] = $events->attach('node.received', array($this, 'onNodeReceived'));
    }

    public function onSendingNode(Event $e)
    {
        $node = $e->getParam('node');
        if ($node instanceof NodeInterface && $node instanceof MessageIdAwareInterface) {
            $node->setId($node->getName() . '-' . time() . '-' . $this->messageCounter++);
            $node->setTimestamp(time());
            $e->setParam('node', $node);
        }
    }

    public function onNodeSent(Event $e)
    {
        $node = $e->getParam('node');
        if ($node instanceof NodeInterface && $node instanceof MessageIdAwareInterface) {
            $this->waitForServer($e->getTarget(), $node->getId());
        }
    }

    public function onNodeReceived(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        $this->receivedId = $node->hasAttribute('id') ? $node->getAttribute('id') : null;
    }

    /**
     * @param Client $client
     * @param string $id
     * @param int    $timeout
     */
    public function waitForServer(Client $client, $id, $timeout = 5)
    {
        $time = time();
        do {
            $client->pollMessages();
        } while ($this->receivedId !== $id && time() - $time < $timeout);
    }
}
