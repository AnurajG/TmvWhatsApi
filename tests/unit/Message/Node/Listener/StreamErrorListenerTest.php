<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;
use RuntimeException;

class StreamErrorListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NotificationListener
     */
    protected $object;

    public function setUp()
    {
        $this->object = new StreamErrorListener();
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

    /**
     * @expectedException RuntimeException
     */
    public function testOnReceivedNode()
    {
        $eventMock = m::mock('Zend\\EventManager\\Event');
        $nodeMock = m::mock('Tmv\\WhatsApi\\Message\\Node\\NodeInterface');

        $nodeMock->shouldReceive('hasChild')->with('system-shutdown')->once()->andReturn(true);

        $eventMock->shouldReceive('getParam')->with('node')->once()->andReturn($nodeMock);

        $this->object->onReceivedNode($eventMock);
    }

    protected function tearDown()
    {
        m::close();
    }
}
