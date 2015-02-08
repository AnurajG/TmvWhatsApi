<?php

namespace Tmv\WhatsApi\Message\Action;

interface IdAwareInterface
{
    /**
     * @internal
     * @param  string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @internal
     * @return string
     */
    public function getId();
}
