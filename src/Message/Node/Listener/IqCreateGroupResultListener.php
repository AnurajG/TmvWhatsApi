<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Entity\Group;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class IqCreateGroupResultListener extends AbstractGroupsListener
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

        if ($node->getAttribute('type') != 'result' || !$this->nodeIdContains($node, 'creategroup-')) {
            return;
        }

        $groupList = $this->getGroupsFromNode($node);
        $client->getEventManager()->trigger('onCreateGroupResult',
            $this,
            [
                'group' => $groupList[0],
            ]
        );
    }
}
