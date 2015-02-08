<?php

namespace Tmv\WhatsApi\Message\Action;

interface TimestampAwareInterface
{
    /**
     * @internal
     * @param  int   $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp);

    /**
     * @internal
     * @return string
     */
    public function getTimestamp();
}
