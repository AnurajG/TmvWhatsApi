<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Entity\SyncResult;
use Tmv\WhatsApi\Message\Node\NodeInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class IqSyncResultListener extends AbstractListener
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

        if ($node->getAttribute('type') != 'result' || !$node->hasChild('sync')) {
            return;
        }

        $syncNode = $node->getChild('sync');

        $existingUsers = $syncNode->hasChild('in') ? $this->getUsers($syncNode->getChild('in')) : [];
        $notExistingUsers = $syncNode->hasChild('out') ? $this->getUsers($syncNode->getChild('out')) : [];

        $index = (int)$syncNode->getAttribute("index");
        $sid = (int)$syncNode->getAttribute("sid");
        $result = new SyncResult($index, $sid, $existingUsers, $notExistingUsers);

        $params = [
            'result' => $result
        ];
        $client->getEventManager()->trigger('onSyncContactResult', $client, $params);
    }

    /**
     * @param NodeInterface $node
     * @return array
     */
    protected function getUsers(NodeInterface $node)
    {
        if (!$node) {
            return [];
        }
        $users = [];
        foreach ($node->getChildren() as $child) {
            $existingUsers[$child->getData()] = $child->getAttribute("jid");
        }
        return $users;
    }
}
