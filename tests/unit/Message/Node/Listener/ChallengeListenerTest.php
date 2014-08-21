<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;

class ChallengeListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChallengeListener
     */
    protected $object;

    public function setUp()
    {
        $this->object = new ChallengeListener();
    }

    public function testAttachAndDetachMethod()
    {
        $this->assertCount(0, $this->object->getListeners());
        $eventManagerMock = m::mock('Zend\\EventManager\\EventManagerInterface');
        $eventManagerMock->shouldReceive('attach')->once();
        $this->object->attach($eventManagerMock);
        $this->assertCount(1, $this->object->getListeners());

        $eventManagerMock->shouldReceive('detach')->once()->andReturn(true);
        $this->object->detach($eventManagerMock);
        $this->assertCount(0, $this->object->getListeners());
    }

    public function testOnReceivedNodeMethod()
    {
        $event = m::mock('Zend\\EventManager\\Event');
        $node = m::mock('Tmv\\WhatsApi\\Message\\Node\\Challenge');
        $client = m::mock('Tmv\\WhatsApi\\Client');

        $this->object->setClient($client);

        $event->shouldReceive('getParam')->with('node')->once()->andReturn($node);
        $client->shouldReceive('setChallengeData')->once()->andReturn('123');
        $node->shouldReceive('getData')->once()->andReturn('123');

        $this->object->onReceivedNode($event);
    }

    protected function tearDown()
    {
        m::close();
    }
}
