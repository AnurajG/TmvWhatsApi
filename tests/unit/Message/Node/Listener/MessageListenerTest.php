<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;
use Tmv\WhatsApi\Client;

class MessageListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageListener
     */
    protected $object;

    public function setUp()
    {
        $this->object = new MessageListener();
    }

    public function testAttachAndDetachMethod()
    {
        $this->assertCount(0, $this->object->getListeners());
        $eventManagerMock = m::mock('\Zend\EventManager\EventManagerInterface');
        $eventManagerMock->shouldReceive('attach')->once();
        $this->object->attach($eventManagerMock);
        $this->assertCount(1, $this->object->getListeners());

        $eventManagerMock->shouldReceive('detach')->once()->andReturn(true);
        $this->object->detach($eventManagerMock);
        $this->assertCount(0, $this->object->getListeners());
    }

    public function testOnReceivedNodeMethod()
    {

        $object = $this->object;
        $node = m::mock('Tmv\\WhatsApi\\Message\\Node\\Message');
        $node->shouldReceive('getAttribute')->with('from')->andReturn('from-value');
        $node->shouldReceive('getAttribute')->with('id')->andReturn('id-value');
        $messageMock = m::mock('Tmv\\WhatsApi\\Message\\Received\\MessageInterface');

        $messageReceivedFactoryMock = m::mock('Tmv\\WhatsApi\\Message\\Received\\MessageFactory');
        $messageReceivedFactoryMock->shouldReceive('createMessage')->with($node)->andReturn($messageMock);

        $this->object->setMessageReceivedFactory($messageReceivedFactoryMock);

        $event = m::mock('Zend\\EventManager\\Event');
        $eventManagerMock = m::mock('Zend\\EventManager\\EventManagerInterface');
        $eventManagerMock->shouldReceive('trigger')->twice();

        $client = m::mock('Tmv\\WhatsApi\\Client');

        $this->object->setClient($client);

        $event->shouldReceive('getParam')->with('node')->once()->andReturn($node);
        $client->shouldReceive('getEventManager')->andReturn($eventManagerMock);
        $client->shouldReceive('send')->once();

        $object->onReceivedNode($event);
    }

    protected function tearDown()
    {
        m::close();
    }
}
