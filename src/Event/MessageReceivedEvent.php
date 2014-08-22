<?php

namespace Tmv\WhatsApi\Event;

use Tmv\WhatsApi\Message\Received\MessageInterface;

class MessageReceivedEvent extends AbstractPublicEvent
{

    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @param  MessageInterface $message
     * @return $this
     */
    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }
}
