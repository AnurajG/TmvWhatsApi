<?php

namespace Tmv\WhatsApi\Message\Action;

use Tmv\WhatsApi\Client;

/**
 * Class ChatState
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class ChatState extends AbstractAction
{
    const STATE_COMPOSING = 'composing';
    const STATE_PAUSED = 'paused';

    /**
     * @var string
     */
    protected $to;
    /**
     * @var string
     */
    protected $state;

    /**
     * @param string $to
     * @param string $state
     */
    public function __construct($to = null, $state = null)
    {
        $this->setTo($to);
        $this->setState($state);
    }

    /**
     * @param string $to
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
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
