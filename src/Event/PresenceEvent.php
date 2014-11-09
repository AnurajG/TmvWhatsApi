<?php

namespace Tmv\WhatsApi\Event;

use Tmv\WhatsApi\Message\Received\Presence;

class PresenceEvent extends AbstractPublicEvent
{
    /**
     * @var Presence
     */
    protected $presence;

    /**
     * @param  Presence $presence
     * @return $this
     */
    public function setPresence(Presence $presence)
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * @return Presence
     */
    public function getPresence()
    {
        return $this->presence;
    }
}
