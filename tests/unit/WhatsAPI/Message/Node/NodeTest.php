<?php

namespace WhatsAPI\Message\Node;

class NodeTest extends \WhatsAPITestCase
{
    /**
     * @var Iq
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Node();
    }

    public function testGetNameMethod()
    {
        $this->assertNull($this->object->getName());
    }

    public function testSetNameMethod()
    {
        $this->object->setName('foo');
        $this->assertEquals('foo', $this->object->getName());
    }

    /**
     * @expectedException \WhatsAPI\Exception\InvalidArgumentException
     */
    public function testSetNameMethodException()
    {
        $this->object->setName('foo');
        $this->object->setName('baz');
    }
}
