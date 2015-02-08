<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
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
        $this->listeners[] = $events->attach('received.node.iq', [$this, 'onReceivedNode']);
    }

    public function onReceivedNode(EventInterface $e)
    {
        /** @var NodeInterface $node */
        $node = $e->getParam('node');
        /** @var Client $client */
        $client = $e->getTarget();
        switch ($node->getAttribute('type')) {
            case 'error':
                // todo: handle iq error
                break;
            case 'result':
                // todo: handle iq result

                // group responses
                $this->processGroupsResult($client, $node);
                break;
        }
        if ($node->hasChild('sync')) {
            // todo: handle sync result
        }
    }

    /**
     * @param  Client        $client
     * @param  NodeInterface $node
     * @return $this
     */
    protected function processGroupsResult(Client $client, NodeInterface $node)
    {
        switch (true) {
            case ($this->nodeIdContains($node, 'creategroup-')):
                // todo
                break;

            case ($this->nodeIdContains($node, 'endgroup-')):
                // todo
                break;

            case ($this->nodeIdContains($node, 'getgroupparticipants-')):
                // todo
                break;

            case ($this->nodeIdContains($node, 'getgroups-')):
                // in its listener
                break;

            case ($this->nodeIdContains($node, 'getgroupinfo-')):
                // in its listener
                break;
        }

        return $this;
    }
}
