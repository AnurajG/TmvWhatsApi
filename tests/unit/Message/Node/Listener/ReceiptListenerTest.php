<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;

class ReceiptListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ReceiptListener
     */
    protected $object;

    public function setUp()
    {
        $this->object = new ReceiptListener();
    }

    public function testAttachAndDetachMethod()
    {
        $this->assertCount(0, $this->object->getListeners());
        $eventManagerMock = m::mock('Zend\\EventManager\\EventManagerInterface');
        $eventManagerMock->shouldReceive('attach')->twice();
        $this->object->attach($eventManagerMock);
        $this->assertCount(2, $this->object->getListeners());

        $eventManagerMock->shouldReceive('detach')->twice()->andReturn(true);
        $this->object->detach($eventManagerMock);
        $this->assertCount(0, $this->object->getListeners());
    }

    public function testOnReceivedNodeVoid()
    {
        $eventMock = m::mock('Zend\\EventManager\\Event');
        $nodeMock = m::mock('Tmv\\WhatsApi\\Message\\Node\\NodeInterface');
        $eventManagerMock = m::mock('Zend\\EventManager\\EventManagerInterface');
        $client = m::mock('Tmv\\WhatsApi\\Client');
        $client->shouldReceive('getEventManager')->once()->andReturn($eventManagerMock);

        $eventManagerMock->shouldReceive('trigger')->once();
        $nodeMock->shouldReceive('getAttribute')->with('class')->once()->andReturn('message');
        $eventMock->shouldReceive('getParam')->with('node')->once()->andReturn($nodeMock);

        $this->object->setClient($client);

        $this->object->onReceivedNodeVoid($eventMock);
    }

    public function testOnReceivedNodeReceipt()
    {
        $eventMock = m::mock('Zend\\EventManager\\Event');
        $nodeMock = m::mock('Tmv\\WhatsApi\\Message\\Node\\NodeInterface');
        $eventManagerMock = m::mock('Zend\\EventManager\\EventManagerInterface');
        $client = m::mock('Tmv\\WhatsApi\\Client');
        $client->shouldReceive('getEventManager')->once()->andReturn($eventManagerMock);

        $eventManagerMock->shouldReceive('trigger')->once();
        $eventMock->shouldReceive('getParam')->with('node')->once()->andReturn($nodeMock);

        $this->object->setClient($client);

        $this->object->onReceivedNodeReceipt($eventMock);
    }

    protected function tearDown()
    {
        m::close();
    }
}
