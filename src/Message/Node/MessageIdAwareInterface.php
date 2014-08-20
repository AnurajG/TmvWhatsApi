<?php

namespace Tmv\WhatsApi\Message\Node;

interface MessageIdAwareInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param  string $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getTimestamp();

    /**
     * @param  int   $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp);
}
