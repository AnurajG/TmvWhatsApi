<?php

namespace WhatsAPI\Message\Node;

class ChallengeTest extends \WhatsAPITestCase
{
    /**
     * @var Challenge
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Challenge();
    }

    public function testGetNameMethod()
    {
        $this->assertEquals('challenge', $this->object->getName());
    }

    /**
     * @expectedException \WhatsAPI\Exception\InvalidArgumentException
     */
    public function testSetNameMethod()
    {
        $this->object->setName('foo');
    }
}
