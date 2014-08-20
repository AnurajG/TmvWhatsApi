<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Message\Node\Node;

class ChatStateNodeFactory extends AbstractNodeFactory implements NodeActionFactoryInterface
{
    /**
     * @param  Action\ActionInterface    $action
     * @return Node
     * @throws \InvalidArgumentException
     */
    public function createNode(Action\ActionInterface $action)
    {
        if (!$action instanceof Action\ChatState) {
            throw new \InvalidArgumentException("Action class not valid");
        }

        $state = new Node();
        $state->setName($action->getState());

        $node = new Node();
        $node->setName('chatstate')
            ->setAttribute('to', $this->getJID($action->getTo()))
            ->addChild($state);

        return $node;
    }
}
