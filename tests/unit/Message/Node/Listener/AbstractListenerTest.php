<?php

namespace Tmv\WhatsApi\Message\Node\Listener;

use \Mockery as m;

class AbstractListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractListenerMock
     */
    protected $object;

    public function setUp()
    {
        $this->object = new AbstractListenerMock();
    }

    public function testAttachAndDetachMethod()
    {
        $eventManagerMock = m::mock('\Zend\EventManager\EventManagerInterface');
        $eventManagerMock->shouldReceive('attach')->once();
        $this->object->attach($eventManagerMock);
    }

    protected function tearDown()
    {
        m::close();
    }
}
