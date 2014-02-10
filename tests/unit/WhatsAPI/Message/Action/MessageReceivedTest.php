<?php

namespace WhatsAPI\Message\Action;

class MessageReceivedTest extends \WhatsAPITestCase
{
    /**
     * @var MessageReceived
     */
    protected $object;

    public function setUp()
    {
        $this->object = new MessageReceived(
            'number@whatsapp.com',
            1,
            'response'
        );
    }

    public function testGetters()
    {
        $this->assertEquals('number@whatsapp.com', $this->object->getTo());
        $this->assertEquals(1, $this->object->getId());
        $this->assertEquals('response', $this->object->getResponse());
    }
}
