<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Message\Action\MessageReceived;
use Tmv\WhatsApi\Message\Event\ReceivedNodeEvent;
use Tmv\WhatsApi\Message\Node\Message;
use Zend\EventManager\EventManagerInterface;

class MessageListener extends AbstractListener
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
        $events->attach('received.node.message', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(ReceivedNodeEvent $e)
    {
        /** @var Message $node */
        $node = $e->getNode();
        $client = $e->getClient();

        //do not send received confirmation if sender is yourself
        $fromMeString = $client->getPhone()->getPhoneNumber() . '@' . $client::WHATSAPP_SERVER;
        if ($node->getFrom() && strpos($node->getFrom(), $fromMeString) === false
            && ($node->hasChild("request") || $node->hasChild("received"))
        ) {
            $action = MessageReceived::fromMessageNode($node);
            $client->send($action);
        }
    }
}
