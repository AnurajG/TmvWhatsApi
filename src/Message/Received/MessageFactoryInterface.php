<?php

namespace Tmv\WhatsApi\Message\Received;

use Tmv\WhatsApi\Message\Node\NodeInterface;

interface MessageFactoryInterface
{
    /**
     * @param  NodeInterface             $node
     * @return MessageMedia|MessageText
     * @throws \InvalidArgumentException
     */
    public function createMessage(NodeInterface $node);
}
