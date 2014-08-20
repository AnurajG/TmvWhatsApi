<?php

namespace Tmv\WhatsApi\Message\Node;

class Message extends AbstractNode implements MessageIdAwareInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'message';
    }

    /**
     * @param  string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setAttribute('id', $id);

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->getAttribute('t');
    }

    /**
     * @param  int   $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->setAttribute('t', $timestamp);

        return $this;
    }

    /**
     * @param  string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->setAttribute('from', $from);

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->getAttribute('from');
    }
}
