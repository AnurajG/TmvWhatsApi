<?php

namespace WhatsAPI\Message\Node;

class SuccessTest extends \WhatsAPITestCase
{
    /**
     * @var Success
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Success();
    }

    public function testGetNameMethod()
    {
        $this->assertEquals('success', $this->object->getName());
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
        $this->object->setAttribute('t', 123);
        $this->assertEquals(123, $this->object->getTimestamp());

        $this->object->setAttribute('creation', 123);
        $this->assertEquals(123, $this->object->getCreation());

        $this->object->setAttribute('expiration', 123);
        $this->assertEquals(123, $this->object->getExpiration());

        $this->object->setAttribute('kind', 123);
        $this->assertEquals(123, $this->object->getKind());

        $this->object->setAttribute('status', 123);
        $this->assertEquals(123, $this->object->getStatus());
    }
}
