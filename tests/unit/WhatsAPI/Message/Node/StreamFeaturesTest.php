<?php

namespace WhatsAPI\Message\Node;

use \Mockery as m;

class StreamFeaturesTest extends \WhatsAPITestCase
{
    /**
     * @var StreamFeatures
     */
    protected $object;

    public function setUp()
    {
        $this->object = new StreamFeatures();
    }

    public function testGetNameMethod()
    {
        $this->assertEquals('stream:features', $this->object->getName());
    }

    /**
     * @expectedException \WhatsAPI\Exception\InvalidArgumentException
     */
    public function testSetNameMethod()
    {
        $this->object->setName('foo');
    }

    public function testSettersAndGetters()
    {
        $nodeMock = m::mock('\WhatsAPI\Message\Node\Node');
        $nodeMock->shouldReceive('getName')->andReturn('w:profile:picture');
        $nodeFactoryMock = m::mock('\WhatsAPI\Message\Node\NodeFactory');
        $nodeFactoryMock->shouldReceive('fromArray')->once()->andReturn($nodeMock);

        $this->object->setNodeFactory($nodeFactoryMock);

        $this->assertFalse($this->object->hasProfileSubscribe(), "Should be empty");

        $this->object->addProfileSubscribe();
        $this->assertTrue($this->object->hasProfileSubscribe(), "Should be true");

        $this->object->removeProfileSubscribe();
        $this->assertFalse($this->object->hasProfileSubscribe(), "should be false");
    }

    protected function tearDown()
    {
        m::close();
    }
}
