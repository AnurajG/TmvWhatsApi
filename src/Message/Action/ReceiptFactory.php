<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\Node;

class ReceiptFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * @param  ActionInterface           $action
     * @return Node
     * @throws \InvalidArgumentException
     */
    public function createNode(ActionInterface $action)
    {
        if (!$action instanceof Receipt) {
            throw new \InvalidArgumentException("Action class not valid");
        }

        $node = new Node();
        $node->setName('receipt')
            ->setAttribute('id', $action->getId())
            ->setAttribute('to', $action->getTo());

        return $node;
    }
}
