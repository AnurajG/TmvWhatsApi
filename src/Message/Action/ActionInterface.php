<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\Node;

interface ActionInterface
{

    /**
     * @return Node
     */
    public function createNode();
}
