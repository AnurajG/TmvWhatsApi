<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\NodeInterface;

interface FactoryInterface
{
    /**
     * @param  ActionInterface $action
     * @return NodeInterface
     */
    public function createNode(ActionInterface $action);
}
