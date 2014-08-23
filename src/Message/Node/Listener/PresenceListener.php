<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Event\PresenceEvent;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Tmv\WhatsApi\Message\Received\PresenceFactory;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class PresenceListener extends AbstractListener
{
    /**
     * @var PresenceFactory
     */
    protected $presenceFactory;

    /**
     * @param  PresenceFactory $presenceFactory
     * @return $this
     */
    public function setPresenceFactory($presenceFactory)
    {
        $this->presenceFactory = $presenceFactory;

        return $this;
    }

    /**
     * @return PresenceFactory
     */
    public function getPresenceFactory()
    {
        if (!$this->presenceFactory) {
            $this->presenceFactory = new PresenceFactory();
        }

        return $this->presenceFactory;
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
        $this->listeners[] = $events->attach('received.node.presence', array($this, 'onReceivedNode'));
    }

    public function onReceivedNode(Event $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');

        if ($node->getAttribute('status') == 'dirty') {
            // todo: send clear dirty
        }
        if (!$this->isNodeFromMyNumber($node)) {
            // It's not my message
            if (!$this->isNodeFromGroup($node)) {
                $presence = $this->getPresenceFactory()->createPresence($node);
                $event = $this->createPresenceEvent('onPresenceReceived');
                $event->setPresence($presence);
                $event->setParam('presence', $presence);
                $event->setParam('node', $node);
                $this->getClient()->getEventManager()->trigger($event);
            } else {
                // Message from group
                $this->parseGroupPresence($node);
            }
        }
    }

    protected function parseGroupPresence(NodeInterface $node)
    {
        $groupId = Identity::parseJID($node->getAttribute('from'));
        if (null != $node->getAttribute('add')) {
            $added = Identity::parseJID($node->getAttribute('add'));
            $this->getClient()->getEventManager()->trigger('onGroupParticipantAdded',
                $this,
                array(
                    'group' => $groupId,
                    'participant' => $added
                )
            );
        } elseif (null != $node->getAttribute('remove')) {
            $removed = Identity::parseJID($node->getAttribute('remove'));
            $author  = Identity::parseJID($node->getAttribute('author'));
            $this->getClient()->getEventManager()->trigger('onGroupParticipantRemoved',
                $this,
                array(
                    'group' => $groupId,
                    'participant' => $removed,
                    'author' => $author
                )
            );
        }
    }

    /**
     * @param  string        $eventName
     * @return PresenceEvent
     */
    protected function createPresenceEvent($eventName)
    {
        return new PresenceEvent($eventName, $this);
    }
}
