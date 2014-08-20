<?php

namespace Tmv\WhatsApi\Message\Action\NodeFactory;

use Tmv\WhatsApi\Message\Action\ActionInterface;
use Tmv\WhatsApi\Message\Node\NodeInterface;

interface NodeActionFactoryInterface
{
    /**
     * @param  ActionInterface $action
     * @return NodeInterface
     */
    public function createNode(ActionInterface $action);
}
