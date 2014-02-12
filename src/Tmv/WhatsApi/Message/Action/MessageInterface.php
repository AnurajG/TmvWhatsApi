<?php

namespace Tmv\WhatsApi\Message\Action;

interface MessageInterface extends ActionInterface
{

    /**
     * @return string
     */
    public function getFromName();

    /**
     * @return string
     */
    public function getTo();

    /**
     * @return \Tmv\WhatsApi\Message\Node\NodeInterface
     */
    public function getSubNode();
}
 