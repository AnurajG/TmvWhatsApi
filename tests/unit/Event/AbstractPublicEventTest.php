<?php

namespace Tmv\WhatsApi\Event;

use Mockery as m;

class AbstractPublicEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractPublicEventMock
     */
    protected $object;

    public function setUp()
    {
        $this->object = new AbstractPublicEventMock();
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testSettersAndGettersMethods()
    {
        $clientMock = m::mock('Tmv\\WhatsApi\\Client');

        $this->object->setClient($clientMock);
        $this->assertEquals($clientMock, $this->object->getClient());
    }
}
