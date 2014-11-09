<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;

class ChastStateListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InjectIdListener
     */
    protected $object;

    public function setUp()
    {
        $this->object = new ChatStateListener();
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testAttach()
    {
        $eventManagerMock = m::mock('Zend\\EventManager\\EventManagerInterface');
        $eventManagerMock->shouldReceive('attach')->once()
            ->with('received.node.chatstate', [$this->object, 'onReceivedNode']);
        $this->object->attach($eventManagerMock);
    }
}
