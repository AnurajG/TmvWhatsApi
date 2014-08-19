<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Message\Node\Node;

class PresenceNodeFactory extends AbstractNodeFactory implements NodeActionFactoryInterface
{
    /**
     * @param Action\ActionInterface $action
     * @return Node
     * @throws \InvalidArgumentException
     */
    public function createNode(Action\ActionInterface $action)
    {
        if (!$action instanceof Action\Presence) {
            throw new \InvalidArgumentException("Action class not valid");
        }

        $node = new Node();
        $node->setName('presence')
            ->setAttribute('name', $action->getName());

        return $node;
    }
}
