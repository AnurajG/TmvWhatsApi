<?php

namespace WhatsAPI\Message\Node;

class NodeFactoryTest extends \WhatsAPITestCase
{
    /**
     * @var NodeFactory
     */
    protected $object;

    public function setUp()
    {
        $this->object = new NodeFactory();
    }

    public function testFromUnknownNode()
    {
        $this->assertInstanceOf(
            '\WhatsAPI\Message\Node\Node',
            $this->object->fromArray(array('name' => 'baz')),
            'Node instance'
        );
    }

    public function testFromChallengeNode()
    {
        $this->assertInstanceOf(
            '\WhatsAPI\Message\Node\Challenge',
            $this->object->fromArray(array('name' => 'challenge')),
            'Challenge instance'
        );
    }

    public function testFromIqNode()
    {
        $this->assertInstanceOf(
            '\WhatsAPI\Message\Node\Iq',
            $this->object->fromArray(array('name' => 'iq')),
            'Iq instance'
        );
    }

    public function testFromStreamFeaturesNode()
    {
        $this->assertInstanceOf(
            '\WhatsAPI\Message\Node\StreamFeatures',
            $this->object->fromArray(array('name' => 'stream:features')),
            'StreamFeatures instance'
        );
    }

    public function testFromSuccessNode()
    {
        $this->assertInstanceOf(
            '\WhatsAPI\Message\Node\Success',
            $this->object->fromArray(array('name' => 'success')),
            'Success instance'
        );
    }

    /**
     * @expectedException \WhatsAPI\Exception\InvalidArgumentException
     */
    public function testWithoutNameException()
    {
        $this->object->fromArray(array());
    }
}
