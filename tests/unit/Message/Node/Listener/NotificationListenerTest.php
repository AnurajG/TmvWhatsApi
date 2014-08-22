<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;

class NotificationListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NotificationListener
     */
    protected $object;

    public function setUp()
    {
        $this->object = new NotificationListener();
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

    public function testOnReceivedNode()
    {
        $eventMock = m::mock('Zend\\EventManager\\Event');
        $nodeMock = m::mock('Tmv\\WhatsApi\\Message\\Node\\NodeInterface');
        $client = m::mock('Tmv\\WhatsApi\\Client');
        $client->shouldReceive('sendNode')->once();

        $this->object->setClient($client);

        $nodeMock->shouldReceive('getAttribute')->with('type')->twice()->andReturn('status');
        $nodeMock->shouldReceive('hasAttribute')->with('to')->once()->andReturn(true);
        $nodeMock->shouldReceive('getAttribute')->with('to')->once()->andReturn('test-to');
        $nodeMock->shouldReceive('hasAttribute')->with('participant')->once()->andReturn(true);
        $nodeMock->shouldReceive('getAttribute')->with('participant')->once()->andReturn('test-participant');
        $nodeMock->shouldReceive('getAttribute')->with('from')->once()->andReturn('test-from');
        $nodeMock->shouldReceive('getAttribute')->with('id')->once()->andReturn('test-id');

        $nodeMock->shouldReceive('getName')->once()->andReturn('notification');

        $eventMock->shouldReceive('getParam')->with('node')->once()->andReturn($nodeMock);

        $this->object->onReceivedNode($eventMock);
    }

    protected function tearDown()
    {
        m::close();
    }
}
