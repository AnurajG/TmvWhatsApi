<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Message\Node\Node;

class ReceiptNodeFactory extends AbstractNodeFactory implements NodeActionFactoryInterface
{
    /**
     * @param  Action\ActionInterface    $action
     * @return Node
     * @throws \InvalidArgumentException
     */
    public function createNode(Action\ActionInterface $action)
    {
        if (!$action instanceof Action\Receipt) {
            throw new \InvalidArgumentException("Action class not valid");
        }

        $node = new Node();
        $node->setName('receipt')
            ->setAttribute('id', $action->getId())
            ->setAttribute('to', $action->getTo());

        return $node;
    }
}
