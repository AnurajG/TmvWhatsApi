<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Client;
use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Message\Node\Node;

class ClearDirtyNodeFactory extends AbstractNodeFactory implements NodeActionFactoryInterface
{
    /**
     * @param  Action\ActionInterface    $action
     * @return Node
     * @throws \InvalidArgumentException
     */
    public function createNode(Action\ActionInterface $action)
    {
        if (!$action instanceof Action\ClearDirty) {
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
