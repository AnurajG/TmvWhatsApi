<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Entity\Identity;
use Tmv\WhatsApi\Message\Node\Node;

class ChatStateFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * @param  ActionInterface           $action
     * @return Node
     * @throws \InvalidArgumentException
     */
    public function createNode(ActionInterface $action)
    {
        if (!$action instanceof ChatState) {
            throw new \InvalidArgumentException("Action class not valid");
        }

        $state = new Node();
        $state->setName($action->getState());

        $node = new Node();
        $node->setName('chatstate')
            ->setAttribute('to', Identity::createJID($action->getTo()))
            ->addChild($state);

        return $node;
    }
}
