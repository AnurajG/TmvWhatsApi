<?php

namespace Tmv\WhatsApi\Message\Action;

/**
 * Abstract Class Message
 *
 * @package Tmv\WhatsApi\Message\Action
 */
abstract class AbstractMessage extends AbstractAction implements MessageInterface
{

    /**
     * @var string
     */
    protected $to;
    /**
     * @var string
     */
    protected $fromName = '';
    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @param  string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  string $fromName
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param  int   $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = (int) $timestamp;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        if (!$this->timestamp) {
            $this->timestamp = time();
        }

        return $this->timestamp;
    }
}
