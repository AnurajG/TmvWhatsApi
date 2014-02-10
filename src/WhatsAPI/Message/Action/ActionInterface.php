<?php

namespace WhatsAPI\Message\Action;

interface ActionInterface
{
    /**
     * @return \WhatsAPI\Message\Node\NodeInterface
     */
    public function getNode();
}
