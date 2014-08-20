<?php

namespace Tmv\WhatsApi\Message\Action;

/**
 * Class MessageText
 * Send a text message
 *
 * @package Tmv\WhatsApi\Message\Action
 */
class MessageText extends AbstractMessage
{

    /**
     * @var string
     */
    protected $body = '';

    /**
     * @param string $from
     * @param string $to
     */
    public function __construct($from = null, $to = null)
    {
        $this->setFromName($from);
        $this->setTo($to);
    }

    /**
     * @param  string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
