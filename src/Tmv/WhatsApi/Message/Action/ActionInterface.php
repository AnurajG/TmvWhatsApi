<?php

namespace Tmv\WhatsApi\Message\Action;

interface ActionInterface
{
    /**
     * @param string $id
     * @return string
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getId();

    /**
     * @return \Tmv\WhatsApi\Message\Node\NodeInterface
     */
    public function getNode();

    /**
     * @return $this
     */
    public function buildNode();
}
