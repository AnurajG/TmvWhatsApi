<?php

namespace Tmv\WhatsApi\Message\Action;

interface ActionInterface
{
    /**
     * @return \Tmv\WhatsApi\Message\Node\NodeInterface
     */
    public function getNode();
}
