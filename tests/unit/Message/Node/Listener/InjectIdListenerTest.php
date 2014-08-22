<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;

class InjectIdListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InjectIdListenerMock
     */
    protected $object;

    public function setUp()
    {
        $this->object = new InjectIdListenerMock();
    }

    public function testAttachAndDetachMethod()
    {
        $this->assertCount(0, $this->object->getListeners());
        $eventManagerMock = m::mock('Zend\\EventManager\\EventManagerInterface');
        $eventManagerMock->shouldReceive('attach')->times(3);
        $this->object->attach($eventManagerMock);
        $this->assertCount(3, $this->object->getListeners());

        $eventManagerMock->shouldReceive('detach')->times(3)->andReturn(true);
        $this->object->detach($eventManagerMock);
        $this->assertCount(0, $this->object->getListeners());
    }

    public function testOnSendingNodeNodeMethod()
    {
        $eventMock = m::mock('Zend\\EventManager\\Event');
        $nodeMock = m::mock(
            'Tmv\\WhatsApi\\Message\\Node\\NodeInterface',
            'Tmv\\WhatsApi\\Message\\Node\\MessageIdAwareInterface'
        );
        $client = m::mock('Tmv\\WhatsApi\\Client');

        $this->object->setClient($client);

        $nodeMock->shouldReceive('setId')
            ->once()
            ->with(sprintf('%s-%s-%s', 'testname', time(), $this->object->getMessageCounter()));
        $nodeMock->shouldReceive('setTimestamp')
            ->once()
            ->with(time());
        $nodeMock->shouldReceive('getName')->once()->andReturn('testname');

        $eventMock->shouldReceive('getParam')->with('node')->once()->andReturn($nodeMock);

        $eventMock->shouldReceive('setParam')->with('node', $nodeMock);

        $this->object->onSendingNode($eventMock);
    }

    public function testOnNodeSentNodeMethod()
    {
        $eventMock = m::mock('Zend\\EventManager\\Event');
        $nodeMock = m::mock(
            'Tmv\\WhatsApi\\Message\\Node\\NodeInterface',
            'Tmv\\WhatsApi\\Message\\Node\\MessageIdAwareInterface'
        );
        $client = m::mock('Tmv\\WhatsApi\\Client');
        $client->shouldReceive('pollMessages');

        $this->object->setClient($client);

        $eventMock->shouldReceive('getParam')->with('node')->once()->andReturn($nodeMock);
        $nodeMock->shouldReceive('getId')->once()->andReturn('testid');

        // Setting received with to avoid timeout wait
        $this->object->setReceivedId('testid');

        $this->object->onNodeSent($eventMock);
    }

    public function testOnNodeReceivedNodeMethod()
    {
        $eventMock = m::mock('Zend\\EventManager\\Event');
        $nodeMock = m::mock('Tmv\\WhatsApi\\Message\\Node\\NodeInterface');

        $eventMock->shouldReceive('getParam')->with('node')->once()->andReturn($nodeMock);
        $nodeMock->shouldReceive('hasAttribute')->with('id')->once()->andReturn(true);
        $nodeMock->shouldReceive('getAttribute')->with('id')->once()->andReturn('testid');

        $this->object->onNodeReceived($eventMock);
        $this->assertEquals('testid', $this->object->getReceivedId());
    }

    protected function tearDown()
    {
        m::close();
    }
}
