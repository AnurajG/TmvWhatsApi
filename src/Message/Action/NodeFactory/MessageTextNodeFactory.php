<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Message\Action;
use Tmv\WhatsApi\Message\Node\MessageText as MessageNode;
use Tmv\WhatsApi\Message\Node\Node;

class MessageTextNodeFactory extends AbstractNodeFactory implements NodeActionFactoryInterface
{
    /**
     * @param  Action\ActionInterface    $action
     * @return MessageNode
     * @throws \InvalidArgumentException
     */
    public function createNode(Action\ActionInterface $action)
    {
        if (!$action instanceof Action\MessageText) {
            throw new \InvalidArgumentException("Action class not valid");
        }
        $server = new Node();
        $server->setName('server');

        $x = new Node();
        $x->setName('x')
            ->setAttribute('xmlns', 'jabber:x:event')
            ->addChild($server);

        $notify = new Node();
        $notify->setName('notify')
            ->setAttribute('xmlns', 'urn:xmpp:whatsapp')
            ->setAttribute('name', $action->getFromName());

        $request = new Node();
        $request->setName('request')
            ->setAttribute('xmlns', 'urn:xmpp:receipts');

        $body = new Node();
        $body->setName('body')
            ->setData($action->getBody());

        $node = new MessageNode();
        $node->setName('message')
            ->setAttribute('to', $this->getJID($action->getTo()))
            ->setAttribute('type', 'text')
            ->addChild($x)
            ->addChild($notify)
            ->addChild($request)
            ->addChild($body);

        return $node;
    }
}
