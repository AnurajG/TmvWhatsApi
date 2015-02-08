<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Message\Node\Node;

interface ActionInterface
{
    /**
     * @internal
     * @return Node
     */
    public function createNode();

    /**
     * Validate the action parameters
     *
     * @internal
     * @return bool
     */
    public function isValid();
}
