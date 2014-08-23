<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Event\MessageReceivedEvent;
use Tmv\WhatsApi\Message\Action\Receipt;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Tmv\WhatsApi\Message\Received\MessageFactory;
use Tmv\WhatsApi\Message\Received\MessageFactoryInterface;
use Tmv\WhatsApi\Message\Received\MessageMedia;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class MessageListener extends AbstractListener
{

    /**
     * @var bool
     */
    protected $sendAutoReceipt = true;
    /**
     * @var MessageFactoryInterface
     */
    protected $messageReceivedFactory;

    /**
     * @param  boolean $sendAutoReceipt
     * @return $this
     */
    public function setSendAutoReceipt($sendAutoReceipt)
    {
        $this->sendAutoReceipt = $sendAutoReceipt;

        return $this;
    }

    /**
     * @return boolean
     */
    public function shouldSendAutoReceipt()
    {
        return $this->sendAutoReceipt;
    }

    /**
     * @param  MessageFactoryInterface $messageReceivedFactory
     * @return $this
     */
    public function setMessageReceivedFactory(MessageFactoryInterface $messageReceivedFactory)
    {
        $this->messageReceivedFactory = $messageReceivedFactory;

        return $this;
    }

    /**
     * @return MessageFactoryInterface
     */
    public function getMessageReceivedFactory()
    {
        if (!$this->messageReceivedFactory) {
            $this->messageReceivedFactory = new MessageFactory();
        }

        return $this->messageReceivedFactory;
    }

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
        $this->listeners[] = $events->attach('received.node.message', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        $client = $this->getClient();

        $factory = $this->getMessageReceivedFactory();
        $message = $factory->createMessage($node);

        // Generic event
        $event = $this->createMessageReceivedEvent('onMessageReceived');
        $event->setClient($client);
        $event->setMessage($message);
        $client->getEventManager()->trigger($event);

        $type = 'Text';
        if ($message instanceof MessageMedia) {
            $type = 'Media'.ucfirst($message->getType());
        }

        // Type event
        $event = $this->createMessageReceivedEvent(sprintf('onMessage%sReceived', ucfirst($type)));
        $event->setClient($client);
        $event->setMessage($message);
        $client->getEventManager()->trigger($event);

        // check and send receipt
        if ($this->shouldSendAutoReceipt()) {
            $this->sendReceipt($client, $node);
        }
    }

    protected function sendReceipt(Client $client, NodeInterface $node)
    {
        $receipt = new Receipt();
        $receipt->setTo($node->getAttribute('from'));
        $receipt->setId($node->getAttribute('id'));
        $client->send($receipt);

        return $this;
    }

    /**
     * @param  string               $eventName
     * @return MessageReceivedEvent
     */
    protected function createMessageReceivedEvent($eventName)
    {
        return new MessageReceivedEvent($eventName, $this);
    }
}
