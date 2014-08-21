<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Node\Node;

class ClearDirtyFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * @param  ActionInterface    $action
     * @return Node
     * @throws \InvalidArgumentException
     */
    public function createNode(ActionInterface $action)
    {
        if (!$action instanceof ClearDirty) {
            throw new \InvalidArgumentException("Action class not valid");
        }

        $clean = new Node();
        $clean->setName('clean')
            ->setAttribute('xmlns', 'urn:xmpp:whatsapp:dirty');

        foreach ($action->getCategories() as $category) {
            $categoryNode = new Node();
            $categoryNode->setName('category')
                ->setAttribute('name', $category);
            $clean->addChild($categoryNode);
        }

        $node = new Node();
        $node->setName('iq')
            ->setAttribute('type', 'set')
            ->setAttribute('to', Client::WHATSAPP_HOST)
            ->setAttribute('id', $action->getId())
            ->addChild($clean);

        return $node;
    }
}
