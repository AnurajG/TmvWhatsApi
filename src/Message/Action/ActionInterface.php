<?php

namespace Tmv\WhatsApi\Message\Action;

interface ActionInterface
{
    /**
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getId();
}
