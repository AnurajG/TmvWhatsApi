<?php

namespace Tmv\WhatsApi\Event;

use Mockery as m;
use Tmv\WhatsApi\Message\Received\Presence;

class PresenceEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PresenceEvent
     */
    protected $object;

    public function setUp()
    {
        $this->object = new PresenceEvent();
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testSettersAndGettersMethods()
    {
        $presenceMock = m::mock('Tmv\\WhatsApi\\Message\\Received\\Presence');

        $this->object->setPresence($presenceMock);
        $this->assertEquals($presenceMock, $this->object->getPresence());
    }
}
