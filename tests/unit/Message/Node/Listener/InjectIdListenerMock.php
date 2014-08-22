<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

class InjectIdListenerMock extends InjectIdListener
{
    /**
     * @param string $receivedId
     * @return $this
     */
    public function setReceivedId($receivedId)
    {
        $this->receivedId = $receivedId;
        return $this;
    }

    /**
     * @return string
     */
    public function getReceivedId()
    {
        return $this->receivedId;
    }
}
 