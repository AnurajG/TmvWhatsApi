<?php

namespace Tmv\WhatsApi\Event;

use Mockery as m;

class MessageReceivedEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageReceivedEvent
     */
    protected $object;

    public function setUp()
    {
        $this->object = new MessageReceivedEvent();
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testSettersAndGettersMethods()
    {
        $messageMock = m::mock('Tmv\\WhatsApi\\Message\\Received\\MessageInterface');

        $this->object->setMessage($messageMock);
        $this->assertEquals($messageMock, $this->object->getMessage());
    }
}
