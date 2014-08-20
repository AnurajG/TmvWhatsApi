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
        $event = m::mock('\Tmv\WhatsApi\Message\Event\ReceivedNodeEvent');
        $node = m::mock('\Tmv\WhatsApi\Message\Node\Message');
        $eventManagerMock = m::mock('\Zend\EventManager\EventManagerInterface');
        $client = m::mock('\Tmv\WhatsApi\Client');
        $phoneMock = m::mock('\Tmv\WhatsApi\Entity\Phone');
        $nodeBody = m::mock('\Tmv\WhatsApi\Message\Node\NodeInterface');

        $identityMock = m::mock('Tmv\\WhatsApi\\Entity\\Identity');
        $identityMock->shouldReceive('getPhone')->andReturn($phoneMock);;

        $event->shouldReceive('getNode')->once()->andReturn($node);
        $event->shouldReceive('getClient')->once()->andReturn($client);
        $client->shouldReceive('getEventManager')->andReturn($eventManagerMock);
        $client->shouldReceive('getIdentity')->once()->andReturn($identityMock);
        $phoneMock->shouldReceive('getPhoneNumber')->once()->andReturn('0123456789');

        $node->shouldReceive('getFrom')->times(3)->andReturn('somethingelse@s.us');
        $node->shouldReceive('hasChild')->with('request')->once()->andReturn(false);
        $node->shouldReceive('hasChild')->with('received')->once()->andReturn(false);
        $node->shouldReceive('getChild')->with('body')->twice()->andReturn($nodeBody);
        $node->shouldReceive('getAttribute')->with('from')->once()->andReturn('somethingelse@s.us');
        $node->shouldReceive('getAttribute')->with('type')->twice()->andReturn('the type');
        $node->shouldReceive('getAttribute')->with('id')->once()->andReturn('the id');
        $node->shouldReceive('getAttribute')->with('t')->once()->andReturn(123);
        $nodeBody->shouldReceive('getData')->once()->andReturn('the body');


        $eventManagerMock->shouldReceive('trigger')->once();

        $this->object->onReceivedNode($event);
    }

    protected function tearDown()
    {
        m::close();
    }
}
